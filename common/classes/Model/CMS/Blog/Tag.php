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
	//protected $_table_columns = array(

} // End Model_Blog_Tag