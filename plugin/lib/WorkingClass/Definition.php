<?php

namespace WorkingClass;

/**
 * The Definition is a representation of the PHP Class as in data structure form.
 *
 * Just using the PHP Class directly would be nice, but there's a lot of
 * logic needed to parse the DocBlock.  Instead we parse the PHP Class
 * (including the DocBlock with additional metadata) and
 */
abstract class Definition implements \JsonSerializable
{
	// Common to all definitions.
	//
	public $classname;
	public $parent;
	public $name;
	public $label;
	public $description;
	public $attributes = [];

	static $tags = [
		'min'		=> Tag\Min::class,
		'max'		=> Tag\Max::class,
		'minlength'	=> Tag\MinLength::class,
		'maxlength'	=> Tag\MaxLength::class,
		'pattern'	=> Tag\Pattern::class,
		'required'	=> Tag\Required::class,
		'step'		=> Tag\Step::class,
		'placeholder'	=> Tag\Placeholder::class
	];

	static $docblock_factory = null;

	public function __construct($classname, $name, $parent = null)
	{
		$this->name = $name;
		$this->classname = $classname;
		$this->parent = $parent;
	}

	function checkRecursion($classname)
	{
		if ($this->classname == $classname) {
			return true;
		}
		if ($parent) {
			return $parent->checkRecursion($classname);
		}
		return false;
	}

	static function docBlockFactory()
	{
		if (!static::$docblock_factory) {
			static::$docblock_factory = \phpDocumentor\Reflection\DocBlockFactory::createInstance(static::$tags);
		}
		return static::$docblock_factory;
	}

	function parseComment($comment)
	{
		$docblock = static::docBlockFactory()->create($comment);
		$this->parseDescription($docblock);
		$this->parseAttributes($docblock);
	}

	function parseDescription($docblock) {
		if ($docblock->getSummary()) {
			$this->label = $docblock->getSummary();
		} else {
			$this->label = $this->name;
		}
		if ($docblock->getDescription()) {
			$this->description = (string)$docblock->getDescription();
		}
	}

	function parseAttributes($docblock)
	{
		foreach (static::$tags as $name => $classname) {
			$items = $docblock->getTagsByName($name);
			if (count($items) == 1) {
				$this->attributes[$name] = (string)($items[0]);
			} else if (count($items > 1)) {
				// FIXME: Throw an exception.  We only supports
				// one tag of each type.
			}
		}
	}

	public function jsonSerialize()
	{
		return [
			'class' => $this->classname,
			'name' => $this->name,
			'label' => $this->label,
			'description' => $this->description,
			'attributes' => $this->attributes
		];
	}

	public function __toString()
	{
		return json_encode($this, JSON_PRETTY_PRINT);
	}
}
