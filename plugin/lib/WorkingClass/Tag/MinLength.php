<?php

namespace WorkingClass\Tag;

use \phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use \phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;

final class MinLength extends BaseTag implements StaticMethod
{
	protected $name = 'minlength';
	public function __construct(int $minlength)
	{
		$this->minlength = $minlength;
	}
	public static function create($body)
	{
		return new static($body);
	}
	public function __toString()
	{
		return (string)$this->minlength;
	}
}
