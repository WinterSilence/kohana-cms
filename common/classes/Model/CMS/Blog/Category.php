<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Abstract blog posts category model
 * 
 * @package   CMS/Common
 * @category  Model
 * @author    WinterSilence
 */
abstract class Model_CMS_Blog_Category extends ORM_MPTT
{
	/**
	 * Table name
	 * @var string
	 */
	protected $_table_name = 'blog_categories';

	/**
	 * A category has many posts
	 * @var array Relationhips
	 */
	protected $_has_many = array(
		'posts' => array(
			'model'       => 'Blog_Post',
			'through'     => 'blog_posts_categories',
			'foreign_key' => 'category_id',
			'far_key'     => 'post_id',
		),
	);

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
			'display' => '10',
			'extra' => 'auto_increment',
			'key' => 'PRI',
			'privileges' => 'select,insert,update,references',
		),
		'parent_id' => array(
			'type' => 'int',
			'min' => '0',
			'max' => '4294967295',
			'column_name' => 'parent_id',
			'column_default' => NULL,
			'data_type' => 'int unsigned',
			'is_nullable' => TRUE,
			'ordinal_position' => 2,
			'display' => '11',
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
			'key' => 'MUL',
			'privileges' => 'select,insert,update,references',
		),
		'active' => array(
			'type' => 'int',
			'min' => '0',
			'max' => '1',
			'column_name' => 'active',
			'column_default' => '0',
			'data_type' => 'tinyint unsigned',
			'is_nullable' => TRUE,
			'ordinal_position' => 4,
			'display' => '1',
			'privileges' => 'select,insert,update,references',
		),
		'lft' => array(
			'type' => 'int',
			'min' => '0',
			'max' => '4294967295',
			'column_name' => 'lft',
			'column_default' => NULL,
			'data_type' => 'int unsigned',
			'is_nullable' => FALSE,
			'ordinal_position' => 5,
			'display' => '11',
			'privileges' => 'select,insert,update,references',
		),
		'rgt' => array(
			'type' => 'int',
			'min' => '0',
			'max' => '4294967295',
			'column_name' => 'rgt',
			'column_default' => NULL,
			'data_type' => 'int unsigned',
			'is_nullable' => FALSE,
			'ordinal_position' => 6,
			'display' => '11',
			'privileges' => 'select,insert,update,references',
		),
		'lvl' => array(
			'type' => 'int',
			'min' => '0',
			'max' => '4294967295',
			'column_name' => 'lvl',
			'column_default' => NULL,
			'data_type' => 'int unsigned',
			'is_nullable' => FALSE,
			'ordinal_position' => 7,
			'display' => '4',
			'privileges' => 'select,insert,update,references',
		),
		'scope' => array(
			'type' => 'int',
			'min' => '0',
			'max' => '4294967295',
			'column_name' => 'scope',
			'column_default' => NULL,
			'data_type' => 'int unsigned',
			'is_nullable' => FALSE,
			'ordinal_position' => 9,
			'display' => '11',
			'privileges' => 'select,insert,update,references',
		),
		'description' => array(
			'type' => 'string',
			'character_maximum_length' => '16777215',
			'column_name' => 'description',
			'column_default' => NULL,
			'data_type' => 'mediumtext',
			'is_nullable' => TRUE,
			'ordinal_position' => 8,
			'collation_name' => 'utf8_general_ci',
			'privileges' => 'select,insert,update,references',
		),
		'date_add' => array(
			'type' => 'string',
			'column_name' => 'date_add',
			'column_default' => NULL,
			'data_type' => 'datetime',
			'is_nullable' => TRUE,
			'ordinal_position' => 9,
			'privileges' => 'select,insert,update,references',
		),
		'date_update' => array(
			'type' => 'string',
			'column_name' => 'date_update',
			'column_default' => NULL,
			'data_type' => 'datetime',
			'is_nullable' => TRUE,
			'ordinal_position' => 10,
			'privileges' => 'select,insert,update,references',
		),
		'sort_order' => array(
			'type' => 'int',
			'min' => '0',
			'max' => '16777215',
			'column_name' => 'sort_order',
			'column_default' => '0',
			'data_type' => 'mediumint unsigned',
			'is_nullable' => TRUE,
			'ordinal_position' => 11,
			'display' => '5',
			'privileges' => 'select,insert,update,references',
		),
		'meta_title' => array(
			'type' => 'string',
			'column_name' => 'meta_title',
			'column_default' => NULL,
			'data_type' => 'varchar',
			'is_nullable' => TRUE,
			'ordinal_position' => 12,
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
			'ordinal_position' => 13,
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
			'ordinal_position' => 14,
			'character_maximum_length' => '255',
			'collation_name' => 'utf8_general_ci',
			'privileges' => 'select,insert,update,references',
		),
	);

} // End Model_Blog_Category