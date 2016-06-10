<?php

namespace WorkingClass\Property;

class Email extends Property
{
	function renderForm($indent, $value = null)
	{
		$sp = str_repeat(' ', $indent);
		return    $sp . '<input type="email"' . "\n"
			. $this->renderElementAttributes($sp, $this->attributes)
			. $this->renderElementValue($indent, $value)
			. $sp . '  >' . "\n";
	}
}
