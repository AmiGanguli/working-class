<?php

namespace WorkingClass;

/* FIXME: The Tag classes are boilerplate and the list
 * of supported tags is repeated.  phpDocumentor supports
 * a TagFactory class that should allow us to remove a lot
 * of this repition, but the docs are sparse.  Need to dig
 * through the code and figure out how this works.
 */

class Visitor
{
	public $callback;
	public $docblock_factory;

	public $current_class = null; 
	public $parent;

	static $builtins = [
		'bool'		=> Property\Bool::class,
		'boolean'	=> Property\Bool::class,
		'color'		=> Property\Color::class,
		'date'		=> Property\Date::class,
		'datetime'	=> Property\DateTime::class,
		'email'		=> Property\Email::class,
		'int'		=> Property\Integer::class,
		'month'		=> Property\Month::class,
		'number'	=> Property\Number::class,
		'range'		=> Property\Range::class,
		'tel'		=> Property\Tel::class,
		'text'		=> Property\Text::class,
		'string'	=> Property\Text::class,
		'time'		=> Property\Time::class,
		'url'		=> Property\Url::class,
		'week'		=> Property\Week::class,
	];

	function __construct($callback, $parent = null)
	{
		$this->callback = $callback;
		$this->parent = $parent;
		$this->docblock_factory = \phpDocumentor\Reflection\DocBlockFactory::createInstance([
			'min'		=> Tag\Min::class,
			'max'		=> Tag\Max::class,
			'minlength'	=> Tag\MinLength::class,
			'maxlength'	=> Tag\MaxLength::class,
			'pattern'	=> Tag\Pattern::class,
			'required'	=> Tag\Required::class,
			'step'		=> Tag\Step::class,
			'placeholder'	=> Tag\Placeholder::class
		]);
	}
	static function setDocblockAttributes($docblock, &$attributes, $names)
	{
		foreach ($names as $name) {
			$items = $docblock->getTagsByName($name);
			if ($items) {
				$attributes[$name] = (string)($items[0]);
			}
		}
	}
	function hasVisited($classname)
	{
		if ($this->current_class == $classname) {
			return true;
		}
		if (!$this->parent) {
			return false;
		}
		return $this->parent->hasVisited($classname);
	}
	function visitProperty($property, $defaults)
	{
		$property_name = $property->getName();
		$property_attributes[$property_name] = [];
		$is_repeater = false;
		$min_repeat = null;
		$max_repeat = null;
		$property_attributes = [];

		$comment = $property->getDocComment();
		if ($comment) {
			$docblock = $this->docblock_factory->create($comment);

			/* The docblock syntax is a little odd in that (it appears)
			 * that the @var tag contains redundant information (the
			 * name of the property), and you can declare several of
			 * them together.  Here we try to accomodate this somewhat
			 * by parsing multiple @var tags in each docblock and saving
			 * the type information for use in later lookups.
			 */
			$vars = $docblock->getTagsByName('var');
			foreach ($vars as $var) {
				list($type, $name, $description) = preg_split("/\s+/", $var, 3);
				$name = substr($name, 1);
				$property_attributes[$name]['type'] = $type;
				$property_attributes[$name]['description'] = $description;
			}

			/* The other tags don't work like var - they are
			 * only associated with the current property..
			 */
			static::setDocblockAttributes(
				$docblock,
				$property_attributes[$property_name],
				[
					'min',
					'max',
					'minlength',
					'maxlength',
					'pattern',
					'required',
					'step',
					'placeholder'
				]
			);
		}
		if (isset($property_attributes[$property_name])) {
			$attributes = $property_attributes[$property_name];
			$tlen = $attributes['type'];
			if (
				preg_match(
					'/^([^\[]+)\[(\d*)-?(\d*)\]/',
					$attributes['type'],
					$matches
				)
			) {
				$is_repeater = true;
				$attributes['type'] = $matches[1];
				if ($matches[2]) {
					$min_repeat = $matches[2];
				}
				if ($matches[3]) {
					$max_repeat = $matches[3];
				}
			}
		} else {
			$attributes = [];
		}
		$attributes['name'] = $name;
		if (isset($defaults[$name])) {
			$attributes['default'] = $defaults[$name];
		}
		if ($is_repeater) {
			$this->callback->startRepeater([
				'min_repeat' => $min_repeat,
				'max_repeat' => $max_repeat
			]);
		}
		$property_object = null;
		if (isset(static::$builtins[$attributes['type']])) {
			$property_object = new static::$builtins[$attributes['type']]($attributes);
		} else {
			if ($this->hasVisited($attributes['type'])) {
				echo "Recursive inclusion of " . $attributes['type'] . "\n";
			} else {
				$visitor = new Visitor($this->callback, $this);
				$visitor->visit($attributes['type']);
			}
		}
		$this->callback->startProperty($property_object);
		$this->callback->endProperty();
		if ($is_repeater) {
			$this->callback->endRepeater();
		}
	}

	function visit($classname)
	{
		$this->current_class = $classname;
		$reflection = new \ReflectionClass($classname);
		$comment = $reflection->getDocComment();

		$attributes = [
			'name'	=> $classname
		];
		if ($comment) {
			$docblock = $this->docblock_factory->create($comment);
			if ($docblock->getSummary()) {
				$attributes['summary'] = $docblock->getSummary();
			}
			if ($docblock->getDescription()) {
				$attributes['description'] = $docblock->getDescription();
			}
		}
		$this->callback->startClass($attributes);

		$properties = $reflection->getProperties();
		$defaults = $reflection->getDefaultProperties();
		foreach ($properties as $property) {
			$this->visitProperty($property, $defaults);
		}

		$this->callback->endClass();
	}
}
