<?php

namespace WorkingClass\Tag;

use \phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use \phpDocumentor\Reflection\DocBlock\Tags\Factory\StaticMethod;

final class Placeholder extends BaseTag implements StaticMethod
{
	protected $name = 'placeholder';
	public function __construct(string $placeholder)
	{
		$this->placeholder = $placeholder;
	}
	public static function create($body)
	{
		return new static($body);
	}
	public function __toString()
	{
		return (string)$this->placeholder;
	}
}
