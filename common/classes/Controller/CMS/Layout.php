<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Abstract layout controller
 *
 * @package   CMS/Common
 * @category  Controller
 * @author    WinterSilence
 */ 
abstract class Controller_CMS_Layout extends Controller_Basic
{
	/**
	 * Content wrapper layout
	 * @var mixed(string|View)
	 */
	public $wrapper = 'layout/wrapper';

	/**
	 * Cache content?
	 * @var boolean   
	 */
	public $auto_cache = TRUE;

	/**
	 * Content cached?
	 * @var boolean  
	 */
	protected $_cached = FALSE;

	/**
	 * Controller id
	 * @var string  
	 */
	protected $_tag = NULL;

	/**
	 * Automatically executed before the controller action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{
		parent::before();
		
		// Try load content from cache
		if (Kohana::$caching AND $this->auto_cache)
		{
			$this->_tag = sha1($this->request->url());
			
			if ($this->content = $this->cache()->get($this->_tag))
			{
				$this->response->body($this->content);
				
				if ($this->request->is_initial())
				{
					$this->check_cache($this->_tag);
				}
				
				$this->_cached = TRUE;
			}
		}
		
		// Set content as View
		if ($this->auto_render AND ! $this->_cached)
		{
			$this->content = SView::factory($this->content);
		}
		
		// TODO: fix Assets module add Config module
		//var_export($this->config);
		// Add assets CSS\JS files
		$this->assets()->set('css', Arr::path($this->config, 'assets.css', array()))
					   ->set('js', Arr::path($this->config, 'assets.js', array()));
		unset($this->config['assets']);
	}

	/**
	 * Assets instance wrapper
	 * 
	 * @param  mixed(string|NULL) $group Group name
	 * @return Assets
	 */
	public function assets($group = NULL)
	{
		return Assets::instance($group);
	}

	/**
	 * Executes the given action and calls the [Controller::before] and [Controller::after] methods.
	 * 
	 * Can also be used to catch exceptions from actions in a single place.
	 * 
	 * 1. Before the controller action is called, the [Controller::before] method
	 * will be called.
	 * 2. Next the controller action will be called.
	 * 3. After the controller action is called, the [Controller::after] method
	 * will be called.
	 * 
	 * @throws  HTTP_Exception_404
	 * @return  Response
	 */
	public function execute()
	{
		// Execute the "before action" method
		$this->before();
		// Call action when response content not cached
		if ( ! $this->_cached)
		{
			// Determine the action to use
			$action = 'action_'.$this->request->action();
			// If the action doesn't exist, it's a 404
			if ( ! method_exists($this, $action))
			{
				throw HTTP_Exception::factory(404,
					'The requested URL :uri was not found on this server.',
					array(':uri' => $this->request->uri())
				)->request($this->request);
			}
			// Execute the action itself
			$this->{$action}();
			// Execute the "after action" method
			$this->after();
		}
		// Return the response
		return $this->response;
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
		if ($this->auto_render)
		{
			if ( ! $this->content->file())
			{
				// Auto generate content View filename
				$this->content->file(CMS::path($this));
			}
			
			if ($this->request->is_external() OR $this->request->is_initial())
			{
				// Wrap content in layout
				$this->wrapper = SView::factory($this->wrapper);
				$this->wrapper->content = $this->content->render();
				$this->content = $this->wrapper->render();
				// $this->content = clone $this->wrapper;
				unset($this->wrapper);
			}
		}
		
		parent::after();
		
		if (Kohana::$caching AND $this->auto_cache)
		{
			// Save content in cache
			$this->cache()->set($this->_tag, $this->response->body(), Date::HOUR);
		}
	}

} // End Controller_Layout