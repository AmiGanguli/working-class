<?php

namespace WorkingClass\Tag;

use \phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use \phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;

final class Required extends BaseTag implements StaticMethod
{
	protected $name = 'required';
	public function __construct(bool $required)
	{
		$this->required = $required;
	}
	public static function create($body)
	{
		return new static($body);
	}
	public function __toString()
	{
		return (string)$this->required;
	}
}
