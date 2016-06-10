<?php
namespace test;

/**
 * This is a test class.
 */
class Goodbye
{
	/**
	* This is a Summary.
	*
	* This is a description.
	*
	* @label This is a label.
	* @description This is a description.
	* @var int[]	$first_name 	This is some stexst
	* @min 0
	* @max 20
	* @minlength 2
	* @maxlength 3
	* @pattern ^abd$
	*/
	public $first_name = 'A First Name';

	/**
	* This is a a second summary.
	*
	* This is another description.
	*
	* @label This is a label.
	* @description This is a description.
	* @var string	$last_name 	This is some more text
	*/
	public $last_name;

	/**
	* This is a repeater of some sort.
	*
	* @var \test\Hello[] $a_repeater A lot of hellos.
	*/
	public $a_repeater;

	/**
	* @var int $an_int This is a number.
	* @min 0
	* @max 20
	* @minlength 2
	* @maxlength 3
	* @pattern ^abd$
	* @required true
	* @step 3
	*/
	public $an_int = 3;
}
