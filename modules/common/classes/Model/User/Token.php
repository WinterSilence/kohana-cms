<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Auth User Token
 *
 * @package    Kohana/Auth
 * @author     Kohana Team
 * @copyright  (c) 2007-2013 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Model_User_Token extends Model_Auth_User_Token
{
	/**
	 * Table name
	 * @var string
	 */
	protected $_table_name = 'user_tokens';

	/**
	 * Table columns
	 * @var array
	 */
	protected $_table_columns = array(
		'id' => array(
			'type' => 'int',
			'max' => '4294967295',
			'column_name' => 'id',
			'data_type' => 'int unsigned',
			'ordinal_position' => 1,
			'display' => '11',
			'extra' => 'auto_increment',
			'key' => 'PRI',
			'privileges' => 'select,insert,update,references',
		),
		'user_id' => array(
			'type' => 'int',
			'max' => '4294967295',
			'column_name' => 'user_id',
			'data_type' => 'int unsigned',
			'ordinal_position' => 2,
			'display' => '11',
			'key' => 'MUL',
			'privileges' => 'select,insert,update,references',
		),
		'user_agent' => array(
			'type' => 'string',
			'column_name' => 'user_agent',
			'data_type' => 'varchar',
			'ordinal_position' => 3,
			'character_maximum_length' => '40',
			'collation_name' => 'utf8_general_ci',
			'privileges' => 'select,insert,update,references',
		),
		'token' => array(
			'type' => 'string',
			'column_name' => 'token',
			'data_type' => 'varchar',
			'ordinal_position' => 4,
			'character_maximum_length' => '40',
			'collation_name' => 'utf8_general_ci',
			'key' => 'UNI',
			'privileges' => 'select,insert,update,references',
		),
		'created' => array(
			'type' => 'int',
			'max' => '4294967295',
			'column_name' => 'created',
			'data_type' => 'int unsigned',
			'ordinal_position' => 5,
			'display' => '10',
			'privileges' => 'select,insert,update,references',
		),
		'expires' => array(
			'type' => 'int',
			'max' => '4294967295',
			'column_name' => 'expires',
			'data_type' => 'int unsigned',
			'ordinal_position' => 6,
			'display' => '10',
			'key' => 'MUL',
			'privileges' => 'select,insert,update,references',
		),
	);
	
} // End Model_User_Token