<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Abstract blog posts category model
 * 
 * @package   CMS/Common
 * @category  Model
 * @author    WinterSilence
 */
abstract class Model_CMS_Blog_Category extends ORM //ORM_MPTT
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
	//protected $_table_columns = array(

} // End Model_Blog_Category