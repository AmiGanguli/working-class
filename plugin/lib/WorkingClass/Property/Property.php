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

	static function parseDocProp($docprop)
	{
		$comment = $docprop->getDocComment();
		$name = $docprop->getName();
		if (!$comment) {
			throw new \Exception("Parsing $name: missing docblock");
		}
		$docblock = (\WorkingClass\Definition::docBlockFactory())->create($comment);
		$vars = $docblock->getTagsByName('var');
		if (count($vars) == 0) {
			throw new \Exception("Parsing $name: missing @var");
		}
		if (count($vars) > 1) {
			throw new \Exception("Parsing $name: only one @var allowed");
		}
		list($type, $var_name, $description) = preg_split("/\s+/", $vars[0], 3);
		$var_name = substr($var_name, 1);
		if ($var_name != $name) {
			throw new \Exception("Parsing $name: @var must match property name($var_name != $name)");
		}
		list($repeater, $type) = Repeater::parse($name, $type);
		if (isset(static::$builtins[$type])) {
			$type = static::$builtins[$type];
		}
		/** !!!!! We need to instantiate the property with the type Name
		    so that, if this is a complex object, we recurse and parse the child
		    object.  Also need to catch recursive types somewhere.
		    */
//		$property = new $type($name);
//		$property->parseDescription($docblock);
//		$property->parseAttributes($docblok);

		if ($repeater) {
			$repeater->child = $property;
			$property = $repeater;
		}
		return $property;
	}
}
