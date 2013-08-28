<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	'native' => array(
		'name'      => 'session_native',
		'lifetime'  => Date::DAY,
	),
	'cookie' => array(
		'name'      => 'session_cookie',
		'encrypted' => TRUE,
		'lifetime'  => Date::DAY,
	),
	'database' => array(
		'name'      => 'session_database',
		'encrypted' => TRUE,
		'lifetime'  => Date::DAY,
		'group'     => 'default',
		'table'     => 'sessions',
		'columns' => array(
			'session_id'  => 'id',
			'last_active' => 'last_active',
			'contents'    => 'content',
		),
		'gc'        => 500,
	),
);