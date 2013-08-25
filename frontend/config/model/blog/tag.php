<?php defined('SYSPATH') OR die('No direct script access.');
return array(
	'id' =>	array(
		'type' => 'int',
		'min' => '0',
		'max' => '4294967295',
		'column_name' => 'id',
		'column_default' => NULL,
		'data_type' => 'int unsigned',
		'is_nullable' => FALSE,
		'ordinal_position' => 1,
		'display' => '10',
		'extra' => 'auto_increment',
		'key' => 'PRI',
		'privileges' => 'select,insert,update,references',
	),
	'name' =>	array(
		'type' => 'string',
		'column_name' => 'name',
		'column_default' => NULL,
		'data_type' => 'varchar',
		'is_nullable' => FALSE,
		'ordinal_position' => 2,
		'character_maximum_length' => '255',
		'collation_name' => 'utf8_general_ci',
		'key' => 'UNI',
		'privileges' => 'select,insert,update,references',
	),
);