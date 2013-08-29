<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Frontend blog  controller
 * 
 */
class Controller_Page_Blog extends Controller_Page_ORM
{
	/**
	 * Name of ORM model.
	 * If not specified, based on controller name.
	 * 
	 * @var mixed(string|ORM)
	 */
	public $model = 'Blog_Post';

	/**
	 * Model fields with request params for find
	 * For switch off auto model loading set this property empty
	 * 
	 * @var array
	 */
	public $model_params = array('slug' => 'id'); // , 'active' => 1

	/**
	 * A list of actions that don't need create a model
	 *
	 * @var array
	 */
	public $deny_create_model_actions = array('category', 'tag');

	/**
	 * A list of actions that don't need auto loading(find by id) a model
	 * 
	 * @var array
	 */
	public $deny_load_model_actions = array('list');
	
	
	/**
	 * Show all posts
	 * 
	 * @return  void
	 */
	public function action_list()
	{
		$this->content->posts = $this->model->find_all();
	}

	/**
	 * Show current post
	 * 
	 * @return  void
	 */
	public function action_post()
	{
		$this->content->post = $this->model;
		$this->content->post_tags = $this->model->tags->find_all();
		$this->content->post_categories = $this->model->categories->find_all();
		/*
		$cat = ORM::factory('Blog_Category');
		$cat->name = 'Root';
		$cat->slug = 'root';
		$cat->make_root();
		*/
	}

	/**
	 * Show category info and posts
	 * 
	 * @return  void
	 */
	public function action_category()
	{
		// Load category
		$category = ORM::factory('Blog_Category', array('slug' => $this->param('id'), 'active' => 1));
		// Checking loading model
		if ( ! $category->loaded())
		{
			throw HTTP_Exception::factory(404, 'Blog category :name not exists', 
				array(':name' => $this->param('id')));
		}
		$this->content->category = $category->as_array();
		
		$this->config['breadcrumbs'] += array('' => $category->name);
		
		if ($category->meta_title)
			$this->config['meta_tags']['title'] = $category->meta_title;
		else
			$this->config['meta_tags']['title'] += array($category->name);
		
		if ($category->meta_description)
			$this->config['meta_tags']['description'] = $category->meta_description;
		
		if ($category->meta_keywords)
			$this->config['meta_tags']['keywords'] = $category->meta_keywords;
		
		// Load category posts
		$this->content->posts = $category->posts->where('active', '=', 1)->find_items();
	}

	/**
	 * Show category info and posts
	 * 
	 * @return  void
	 */
	public function action_tag()
	{
		// Current category info
		$model = ORM::factory('Blog_Tag');
		$tag = $model
			->where($model->primary_key(), '=', $this->param('id'))
			->and_where('active', '=', 1)
			->find();
		// Checking loading model
		if ( ! $category->loaded())
		{
			throw HTTP_Exception::factory(404, 'Blog tag :name not exists', 
				array(':name' => $this->param('id')));
		}
		$this->content->tag = $tag;
		$this->content->tag_posts = $tag->posts->where('active', '=', 1)->find_items();
	}

}