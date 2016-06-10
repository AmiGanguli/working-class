<?php

namespace WorkingClass\Tag;

use \phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use \phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;

final class MaxLength extends BaseTag implements StaticMethod
{
	protected $name = 'maxlength';
	public function __construct(int $maxlength)
	{
		$this->maxlength = $maxlength;
	}
	public static function create($body)
	{
		return new static($body);
	}
	public function __toString()
	{
		return (string)$this->maxlength;
	}
}
