<?php

/*
Plugin Name: WorkingClass
Description: Store and display structured content in WordPress.

*/

namespace WorkingClass;

class Autoloader
{
	protected $base_dir;

	public function __construct($base_dir)
	{
		$this->base_dir = $base_dir;
	}
	public function __invoke($classname)
	{
		$filename = $this->base_dir . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
		if (file_exists($filename)) {
			include($filename);
			if (class_exists($classname)) {
				return true;
			}
		}
		return false;
	}
}

$dirname = dirname(__FILE__);
spl_autoload_register(new Autoloader($dirname . DIRECTORY_SEPARATOR . 'lib'));
spl_autoload_register(new Autoloader($dirname . DIRECTORY_SEPARATOR . 'classes'));
require_once($dirname . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

if (class_exists('WP_CLI')) {
	require_once('wp-cli.php');
}
