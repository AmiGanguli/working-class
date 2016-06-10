<?php

namespace WorkingClass\Tag;

use \phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use \phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;

final class Min extends BaseTag implements StaticMethod
{
	protected $name = 'min';
	public function __construct(int $min)
	{
		$this->min = $min;
	}
	public static function create($body)
	{
		return new static($body);
	}
	public function __toString()
	{
		return (string)$this->min;
	}
}
