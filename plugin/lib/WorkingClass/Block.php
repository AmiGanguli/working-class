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
		$block = new Block($classname, $name, $parent);

		$reflection = new \ReflectionClass($classname);
		//$block->parseComment($reflection->getDocComment());
		//$block->parseProperties($reflection);
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
		$property = Property\Property::parseDocProp($docprop);
	}
/*
	function parseProperty($docblock_factory, $property, $defaults)
	{
		$property_name = $property->getName();
		$is_repeater = false;
		$min_repeat = null;
		$max_repeat = null;
		$property_attributes = [];

		$comment = $property->getDocComment();
		if ($comment) {
			$docblock = $this->docblock_factory->create($comment);
			$vars = $docblock->getTagsByName('var');
			foreach ($vars as $var) {
				list($type, $name, $description) = preg_split("/\s+/", $var, 3);
				$name = substr($name, 1);
				if ($name == $property_name) {
					$property_attributes[$name]['type'] = $type;
					$property_attributes[$name]['description'] = $description;
				} else {
					// FIXME: Throw an exception.  Var property names
					// should match this property.  DocBlock supports a
					// diffferent syntax, but I decided that supporting
					// it would result in very confusing sematics.  Better
					// just to insist that the DocBlock corresponds to the
					// property it's annotating.
				}
			}

			// Other tags are simpler than var
			//
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

*/
}
