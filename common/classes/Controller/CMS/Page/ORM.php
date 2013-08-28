<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Basic page controller with ORM model
 *
 * @package     CMS/Common
 * @category    Controller
 * @author      WinterSilence
 */
abstract class Controller_CMS_Page_ORM extends Controller_Page
{
	/**
	 * Name of ORM model.
	 * If not specified, based on controller name.
	 * 
	 * @var mixed(string|ORM)
	 */
	public $model = NULL;

	/**
	 * Model fields with request params for find
	 * For switch off auto model loading set NULL in this property.
	 * 
	 * @var mixed(array|NULL)
	 */
	public $model_params = array('slug' => 'id');

	/**
	 * A list of actions that don't need create a model
	 *
	 * @var array
	 */
	public $deny_create_model_actions = array();

	/**
	 * A list of actions that don't need auto loading(find by id) a model
	 * 
	 * @var array
	 */
	public $deny_load_model_actions = array();

	/**
	 * 
	 * 
	 * @return  void
	 */
	protected function _auto_model()
	{
		if (in_array($this->request->action(), $this->deny_create_model_actions))
		{
			return NULL;
		}
		
		if ( ! $this->model)
		{
			/*
			 * Auto detect model name
			 * Example: Controller_Page_Blog_Category = Blog_Category(Model_Blog_Category)
			 */
			$this->model = substr(get_class($this), 16);
		}
		
		if (in_array($this->request->action(), $this->deny_load_model_actions))
		{
			// Create model
			$this->model = ORM::factory($this->model);
		}
		else
		{
			if ($this->model_params)
			{
				foreach ($this->model_params as $key => $value)
				{
					$this->model_params[$key] = $this->param($value, $value);
				}
			}
			
			// Create model and find item
			$this->model = ORM::factory($this->model, $this->model_params);
			
			// Checking loading model
			if ( ! $this->model->loaded())
			{
				throw HTTP_Exception::factory(404, 'Model :model not loaded', 
					array(':model' => $this->model->object_name()));
			}
		}
	}

	/**
	 * Automatically executed before the controller action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{
		parent::before();
		
		// Auto create ORM model
		$this->_auto_model();
	}

	/**
	 * Automatically executed after the controller action. Can be used to apply
	 * transformation to the response, add extra output, and execute
	 * other custom code.
	 * 
	 * @return  void
	 */
	public function after()
	{
		// Delete model object
		unset($this->model);
		
		parent::after();
	}

} // End Controller_Page_ORM