<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Frontend controller for information pages
 * 
 */
class Controller_Page_Info extends Controller_Page_ORM
{
	/**
	 * Name of ORM model.
	 * If not specified, based on controller name.
	 * 
	 * @var mixed(string|ORM)
	 */
	public $model = 'Info_Page';

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
	 * Show all information pages
	 * 
	 * @return  void
	 */
	public function action_list()
	{
		$this->content->pages = $this->model->find_all()->as_array();
	}

	/**
	 * Show current information page
	 * 
	 * @return  void
	 */
	public function action_page()
	{
		$this->content->page = $this->model->as_array();
	}

} // End Controller_Page_Info