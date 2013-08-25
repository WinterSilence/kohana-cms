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
	
}