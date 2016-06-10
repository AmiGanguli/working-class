<?php

namespace WorkingClass\Tag;

use \phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use \phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;

final class Pattern extends BaseTag implements StaticMethod
{
	protected $name = 'pattern';
	public function __construct(string $pattern)
	{
		$this->pattern = $pattern;
	}
	public static function create($body)
	{
		return new static($body);
	}
	public function __toString()
	{
		return (string)$this->pattern;
	}
}
