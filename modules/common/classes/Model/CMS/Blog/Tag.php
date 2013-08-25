<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Abstract blog post tag model
 * 
 * @package   CMS/Common
 * @category  Model
 * @author    WinterSilence
 */
abstract class Model_CMS_Blog_Tag extends ORM
{
	/**
	 * Table name
	 * @var string
	 */
	protected $_table_name = 'blog_tags';

	/**
	 * A category has many posts
	 * @var array Relationhips
	 */
	protected $_has_many = array(
		'posts' => array(
			'model'       => 'Blog_Post',
			'through'     => 'blog_posts_tags',
			'foreign_key' => 'tag_id',
			'far_key'     => 'post_id',
		),
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
		'name' => array(
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

} // End Model_Blog_Tag