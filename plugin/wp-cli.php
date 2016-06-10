<?php

class Dumper
{
	public $indent = 0;

	function printAttributes($attributes)
	{
		foreach ($attributes as $key => $value) {
			echo str_repeat(' ', $this->indent) . ' -- ' . $key . ': ' . $value . "\n";
		}
	}
	function startClass($attributes)
	{
		echo str_repeat(' ', $this->indent) . 'class ' . $attributes['name'] . "\n";
		$this->printAttributes($attributes);
		$this->indent += 2;
	}
	function endClass()
	{
		$this->indent -= 2;
		echo str_repeat(' ', $this->indent) . "/class\n";
	}
	function startProperty($attributes)
	{
		echo str_repeat(' ', $this->indent) . 'property ' . $attributes['name'] . "\n";
		$this->printAttributes($attributes);
		$this->indent += 2;
	}
	function endProperty()
	{
		$this->indent -= 2;
		echo str_repeat(' ', $this->indent) . "/property\n";
	}
}

class WorkingClass_Command extends WP_CLI_Command {

	/**
	 * Prints a greeting.
	 *
	 * ## OPTIONS
	 *
	 * <name>
	 * : The name of the person to greet.
	 *
	 */
	function hello( $args, $assoc_args ) {
		list( $name ) = $args;

		WP_CLI::success( "Hello, $name!" );
	}

	function load( $args, $assoc_args ) {
		list ( $classname ) = $args;
		$ob = new $classname;
	}

	function reflect( $args, $assoc_args ) {
		list ( $classname ) = $args;
		$dumper = new Dumper;
		$visitor = new \WorkingClass\Visitor($dumper);
		$visitor->visit($classname);
	}

	function printform( $args, $assoc_args ) {
		list ( $classname ) = $args;
		$value = new \test\Hello;
		$value->first_name = "Amitavo";
		$value->last_name = "Ganguli";
		$template = 'test.tpl';
		$dumper = new \WorkingClass\FormRenderer($template, $value);
		$visitor = new \WorkingClass\Visitor($dumper);
		$visitor->visit($classname);
	}

	function parse( $args, $assoc_args ) {
		list ( $classname ) = $args;
		echo \WorkingClass\Block::parse($classname, $classname) . "\n";
	}

}

WP_CLI::add_command( 'workingclass', 'WorkingClass_Command' );
