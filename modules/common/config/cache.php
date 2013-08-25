<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	'apc' => array(
		'driver'           => 'apc',
		'default_expire'   => Date::HOUR,
	),
	'file' => array(
		'driver'           => 'file',
		'cache_dir'        => Kohana::$cache_dir.DS.'cms',
		'default_expire'   => Date::HOUR,
		'ignore_on_delete' => array('.gitignore', '.git', '.svn'),
	),
);