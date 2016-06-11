<?php

namespace WorkingClass;

/**
 *
 */
class Block extends Definition
{

	public $properties = [];

	static function parse($classname, $name, $parent = null)
	{
		if ($parent && $parent->checkRecursion($classname)) {
			throw new \Exception("Parsing $classname: illegal recursive definition.");
		}

		$block = new Block($classname, $name, $parent);

		$reflection = new \ReflectionClass($classname);
		$block->parseComment($reflection->getDocComment());
		$block->parseProperties($reflection);

		return $block;
	}

	function parseProperties($reflection)
	{
		$properties = $reflection->getProperties();
		$defaults = $reflection->getDefaultProperties();
		foreach ($properties as $docprop) {
			$this->properties[] = $this->parseProperty($docprop, $defaults);
		}
	}

	function parseProperty($docprop, $defaults)
	{
		$property = Property\Property::parseDocProp($docprop, $this);
		if (isset($defaults[$property->name])) {
			$property->attributes['default'] = $defaults[$property->name];
		}
		return $property;
	}

	public function jsonSerialize()
	{
		$ret = parent::jsonSerialize();
		$ret['properties'] = [];
		foreach ($this->properties as $property) {
			$ret['properties'][] = $property->jsonSerialize();
		}
		return $ret;
	}
}
