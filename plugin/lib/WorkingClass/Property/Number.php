<?php

namespace WorkingClass\Property;

class Number extends Property
{
	function renderForm($indent, $value = null)
	{
		$sp = str_repeat(' ', $indent);
		return    $sp . '<input type="number"' . "\n"
			. $this->renderElementAttributes($sp, $this->attributes)
			. $this->renderElementValue($indent, $value)
			. $sp . '  >' . "\n";
	}
}
