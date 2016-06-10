<?php

namespace WorkingClass;

class FormRenderer
{
	public $indent = 0;
	public $value = null;
	public $template;
	private $value_stack = [];
	public $form = '';

	function __construct($template, $value = null)
	{
		$this->value = $value;
		$this->template = $template;
		$this->value_stack = [$value];
	}
	function startClass($attributes)
	{
		$this->value_stack[] =
		$html_class = preg_replace('/\W+/','-',strtolower(strip_tags($attributes['name'])));
		$this->form .= str_repeat(' ', $this->indent) . '<div class="' . $html_class . "\">\n";
		$this->indent += 2;
	}
	function endClass()
	{
		$this->indent -= 2;
		echo str_repeat(' ', $this->indent) . "</div>\n";
	}
	function startProperty($property_object)
	{
		if ($property_object) {
			echo $property_object->renderForm($this->indent, $value);
		}
		$this->indent += 2;
	}
	function endProperty()
	{
		$this->indent -= 2;
	}
	function startRepeater($attributes)
	{
		echo str_repeat(' ', $this->indent)
			. '<div class="workingclass-repeater">'
			. "\n"
			;
		$this->indent += 2;
	}
	function endRepeater()
	{
		$this->indent -= 2;
		echo str_repeat(' ', $this->indent)
			. '</div>'
			. "\n"
			;
	}
}
