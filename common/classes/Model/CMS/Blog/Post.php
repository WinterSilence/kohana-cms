<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Abstract blog post model
 * 
 * @package   CMS/Common
 * @category  Model
 * @author    WinterSilence
 */
abstract class Model_CMS_Blog_Post extends ORM
{
	/**
	 * Table name
	 * @var string
	 */
	protected $_table_name = 'blog_posts';

	/**
	 * A post has many tags and categories
	 * @var array Relationhips
	 */
	protected $_has_many = array(
		'categories' => array(
			'model'       => 'Blog_Category',
			'through'     => 'blog_posts_categories',
			'foreign_key' => 'post_id',
			'far_key'     => 'category_id',
		),
		'tags' => array(
			'model'       => 'Blog_Tag',
			'through'     => 'blog_posts_tags',
			'foreign_key' => 'post_id',
			'far_key'     => 'tag_id',
		),
	);

	/**
	 * Post author
	 * @var array
	 */
	protected $_has_one = array(
		'user' => array(
			'model'       => 'User',
			'foreign_key' => 'user_id',
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
	//protected $_table_columns = array(

} // End Model_Blog_Post