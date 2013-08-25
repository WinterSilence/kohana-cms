<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	'default' => array(
		// Where to save compiled css and js files
		'compile' => array(
			'css' => 'media/css/assets/',
			'js'  => 'media/js/assets/',
		),
		// Where to get source css and js files
		'source' => array(
			'css' => DOCROOT.'media/css/',
			'js'  => DOCROOT.'media/js/',
		),
		// LESS default directories to import from
		'less' => array(),
	),
);