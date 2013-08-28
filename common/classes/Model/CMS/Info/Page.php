<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Abstract Info page model
 * 
 * @package   CMS/Common
 * @category  Model
 * @author    WinterSilence
 */
abstract class Model_CMS_Info_Page extends ORM
{
	/**
	 * Table name
	 * @var string
	 */
	protected $_table_name = 'info_pages';

	/**
	 * Table columns
	 * @var array
	 */
	protected $_table_columns = array(
		'id' => array(
			'type' => 'int',
			'min' => '0',
			'max' => '4294967295',
			'column_name' => 'id',
			'column_default' => NULL,
			'data_type' => 'int unsigned',
			'is_nullable' => FALSE,
			'ordinal_position' => 1,
			'display' => '11',
			'extra' => 'auto_increment',
			'key' => 'PRI',
			'privileges' => 'select,insert,update,references',
		),
		'name' => array(
			'type' => 'string',
			'column_name' => 'name',
			'column_default' => NULL,
			'data_type' => 'varchar',
			'is_nullable' => FALSE,
			'ordinal_position' => 2,
			'character_maximum_length' => '255',
			'collation_name' => 'utf8_general_ci',
			'privileges' => 'select,insert,update,references',
		),
		'slug' => array(
			'type' => 'string',
			'column_name' => 'slug',
			'column_default' => NULL,
			'data_type' => 'varchar',
			'is_nullable' => FALSE,
			'ordinal_position' => 3,
			'character_maximum_length' => '255',
			'collation_name' => 'utf8_general_ci',
			'key' => 'UNI',
			'privileges' => 'select,insert,update,references',
		),
		'content' => array(
			'type' => 'string',
			'character_maximum_length' => '65535',
			'column_name' => 'content',
			'column_default' => NULL,
			'data_type' => 'text',
			'is_nullable' => TRUE,
			'ordinal_position' => 4,
			'collation_name' => 'utf8_general_ci',
			'privileges' => 'select,insert,update,references',
		),
		'date_add' => array(
			'type' => 'string',
			'column_name' => 'date_add',
			'column_default' => NULL,
			'data_type' => 'datetime',
			'is_nullable' => TRUE,
			'ordinal_position' => 5,
			'privileges' => 'select,insert,update,references',
		),
		'date_update' => array(
			'type' => 'string',
			'column_name' => 'date_update',
			'column_default' => NULL,
			'data_type' => 'datetime',
			'is_nullable' => TRUE,
			'ordinal_position' => 6,
			'privileges' => 'select,insert,update,references',
		),
		'active' => array(
			'type' => 'int',
			'min' => '0',
			'max' => '1',
			'column_name' => 'active',
			'column_default' => '0',
			'data_type' => 'tinyint unsigned',
			'is_nullable' => FALSE,
			'ordinal_position' => 7,
			'display' => '1',
			'privileges' => 'select,insert,update,references',
		),
		'meta_title' => array(
			'type' => 'string',
			'column_name' => 'meta_title',
			'column_default' => NULL,
			'data_type' => 'varchar',
			'is_nullable' => TRUE,
			'ordinal_position' => 8,
			'character_maximum_length' => '255',
			'collation_name' => 'utf8_general_ci',
			'privileges' => 'select,insert,update,references',
		),
		'meta_description' => array(
			'type' => 'string',
			'column_name' => 'meta_description',
			'column_default' => NULL,
			'data_type' => 'varchar',
			'is_nullable' => TRUE,
			'ordinal_position' => 9,
			'character_maximum_length' => '255',
			'collation_name' => 'utf8_general_ci',
			'privileges' => 'select,insert,update,references',
		),
		'meta_keywords' => array(
			'type' => 'string',
			'column_name' => 'meta_keywords',
			'column_default' => NULL,
			'data_type' => 'varchar',
			'is_nullable' => TRUE,
			'ordinal_position' => 10,
			'character_maximum_length' => '255',
			'collation_name' => 'utf8_general_ci',
			'privileges' => 'select,insert,update,references',
		),
	);

	/**
	 * A post has many tags and categories
	 * @var array Relationhips
	 */
	/*
	protected $_has_many = array(
		'tags' => array(
			'model'   => 'Tag',
			'through' => 'posts_tags',
		),
	);
	*/

	/**
	 * Auto-update columns for updates
	 * @var string
	 */
	 protected $_updated_column = array(
		'column' => 'date_update',
		'format' => 'Y-m-d H:i:s',
	);

	/**
	 * Auto-update columns for creation
	 * @var string
	 */
	protected $_created_column = array(
		'column' => 'date_add',
		'format' => 'Y-m-d H:i:s',
	);

} // End Model_Info_Page