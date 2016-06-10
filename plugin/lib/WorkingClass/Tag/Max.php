<?php

namespace WorkingClass\Tag;

use \phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use \phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;

final class Max extends BaseTag implements StaticMethod
{
	protected $name = 'max';
	public function __construct(int $max)
	{
		$this->max = $max;
	}
	public static function create($body)
	{
		return new static($body);
	}
	public function __toString()
	{
		return (string)$this->max;
	}
}
