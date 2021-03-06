<?php

namespace WorkingClass\Property;

class Repeater extends Property
{
	public $min_repeat = 0;
	public $max_repeat = 0;
	public $child = null;

	static function parse($type, $name, $parent = null)
	{
		$repeater = null;
		if (
			preg_match(
				'/^([^\[]+)\[(\d*)-?(\d*)\]/',
				$type,
				$matches
			)
		) {
			$type = $matches[1];
			$repeater = new Repeater(static::class, $name, $parent);
			if ($matches[2]) {
				$repeater->min_repeat = $matches[2];
			}
			if ($matches[3]) {
				$repeater->max_repeat = $matches[3];
			}
		}
		return [$repeater, $type];
	}

	public function jsonSerialize()
	{
		$ret = parent::jsonSerialize();
		$ret['child'] = $this->child;
		return $ret;
	}

	function renderForm($indent, $value = null)
	{
		$sp = str_repeat(' ', $indent);
		return $sp . '<div class="workingclass-repeat"' . "\n"
			. $sp . '  min-repeat="' . $min_repeat . "\"\n"
			. $sp . '  max-repeat="' . $max_repeat . "\"\n"
			. $sp . '  >' . "\n"
			. $child->renderForm($indent + 2)
			. $sp . '</div>';
	}

	function checkRecursion($classname)
	{
		if ($this->min_repeat == 0) {
			return false;
		}
		return parent::checkRecursion($classname);
	}
}
