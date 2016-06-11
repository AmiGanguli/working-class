<?php

namespace WorkingClass\Property;

class Property extends \WorkingClass\Definition
{
	static $builtins = [
		'bool'		=> Bool::class,
		'boolean'	=> Bool::class,
		'color'		=> Color::class,
		'date'		=> Date::class,
		'datetime'	=> DateTime::class,
		'email'		=> Email::class,
		'int'		=> Integer::class,
		'month'		=> Month::class,
		'number'	=> Number::class,
		'range'		=> Range::class,
		'tel'		=> Tel::class,
		'text'		=> Text::class,
		'string'	=> Text::class,
		'time'		=> Time::class,
		'url'		=> Url::class,
		'week'		=> Week::class,
	];

	static function parse($classname, $name, $parent = null)
	{
		return new $classname($classname, $name, $parent);
	}

	public function renderElementAttributes($indent, $attributes)
	{
		$ret = '';
		foreach ($this->allowedAttributes() as $attribute)
		{
			if (isset($this->attributes[$attribute])) {
				$ret .= $indent . '  '
				      . $tag . '="'
				      . htmlspecialchars($this->attributes[$attribute])
				      . "\"\n";
			}
		}
		return $ret;
	}
	public function renderElementValue($indent, $value)
	{
		if (!$value) {
			return '';
		}
		return $indent . 'value="' . htmlspecialchars($value) . '"';
	}

	static function parseDocProp($docprop, $parent = null)
	{
		$comment = $docprop->getDocComment();
		$name = $docprop->getName();

		// DocBlock comments are required.
		//
		if (!$comment) {
			throw new \Exception("Parsing $name: missing docblock");
		}

		$docblock = (\WorkingClass\Definition::docBlockFactory())->create($comment);
		$vars = $docblock->getTagsByName('var');

		// There must be exactly one @var tag.
		//
		if (count($vars) == 0) {
			throw new \Exception("Parsing $name: missing @var");
		}
		if (count($vars) > 1) {
			throw new \Exception("Parsing $name: only one @var allowed");
		}

		// The name of the property given in the @var must match
		// the name of the property in the code.
		//
		list($type, $var_name, $description) = preg_split("/\s+/", $vars[0], 3);
		$var_name = substr($var_name, 1);
		if ($var_name != $name) {
			throw new \Exception("Parsing $name: @var must match property name($var_name != $name)");
		}

		// Give the repeater class a shot at this property.  If it's
		// not a repeater, the parser will return null.
		//
		list($property, $type) = Repeater::parse($type, $name, $this);

		// Map the builtin types to the corresponding classes.
		//
		if (isset(static::$builtins[$type])) {
			$type = static::$builtins[$type];
		}

		if (method_exists($type, 'parse')) {
			$definition_type = $type;
		} else {
			$definition_type = '\WorkingClass\Block';
		}

		if ($property) {
			$property->child = $definition_type;
		} else {
			$property = $definition_type::parse($type, $name, $parent);
		}
		$property->parseDescription($docblock);
		$property->parseAttributes($docblock);

		return $property;
	}
}
