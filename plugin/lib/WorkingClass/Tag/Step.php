<?php

namespace WorkingClass\Tag;

use \phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use \phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;

final class Step extends BaseTag implements StaticMethod
{
	protected $name = 'step';
	public function __construct(int $step)
	{
		$this->step = $step;
	}
	public static function create($body)
	{
		return new static($body);
	}
	public function __toString()
	{
		return (string)$this->step;
	}
}
