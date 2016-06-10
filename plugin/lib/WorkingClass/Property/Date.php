<?php

namespace WorkingClass\Property;

class Date extends Property
{
	function renderForm($indent, $value = null)
	{
		$sp = str_repeat(' ', $indent);
		return    $sp . '<input type="date"' . "\n"
			. $this->renderElementAttributes($sp, $this->attributes)
			. $this->renderElementValue($indent, $value)
			. $sp . '  >' . "\n";
	}
}
