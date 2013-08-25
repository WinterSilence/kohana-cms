<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	// Where to save compiled css and js files
	'compile_dirs' => array(
		'css' => DOCROOT.'media/css/assets/',
		'js'  => DOCROOT.'media/js/assets/',
	),
	// Where to get source css and js files
	'source_dirs' => array(
		'css' => DOCROOT.'media/css/',
		'js'  => DOCROOT.'media/js/',
	),
	// LESS default directories to import from
	'import_dirs' => array(),
);