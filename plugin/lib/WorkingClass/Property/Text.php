<?php

namespace WorkingClass\Property;

class Text extends Property
{
	function __construct($classname)
	{
		Property::__construct($classname);
	}
	function renderForm($indent, $value = null)
	{
		$sp = str_repeat(' ', $indent);
		return    $sp . '<input type="text"' . "\n"
			. $this->renderElementAttributes($sp, $this->attributes)
			. $this->renderElementValue($indent, $value)
			. $sp . '  >' . "\n";
	}
}
