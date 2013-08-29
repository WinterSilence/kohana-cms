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
	 * View auto render?
	 * @var bool
	 */
	public $auto_render = TRUE;

	/**
	 * Wrapper layout for content
	 * @var mixed
	 */
	public $wrapper = 'layout/wrapper';

	/**
	 * Cache content?
	 * @var boolean   
	 */
	public $auto_cache = TRUE;

	/**
	 * Content loaded from cache?
	 * @var boolean  
	 */
	protected $_content_cached = FALSE;

	/**
	 * Controller identification tag
	 * @var string  
	 */
	protected $_tag = NULL;

	/**
	 * Path to controller files (view, config, i18n and etc.)
	 * @var string
	 */
	protected $_path = NULL;

	/**
	 * Gets Assets manager instance 
	 * 
	 * @param   string  $group  Group name
	 * @return  Assets
	 */
	public function assets($group = NULL)
	{
		return Assets::instance($group);
	}

	/**
	 * Issues a HTTP redirect.
	 *
	 * Proxies to the [HTTP::redirect] method.
	 *
	 * @static
	 * @param  string  $uri   URI to redirect to
	 * @param  int     $code  HTTP Status code to use for the redirect
	 * @return void
	 * @throws HTTP_Exception
	 */
	public static function redirect($uri = '', $code = 302)
	{
		$this->auto_render = FALSE;
		
		return parent::redirect($uri, $code);
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
		
		/**
		 * Execute the "before action" method
		 * Moved here because "virtual" actions not used in CMS
		 */
		$this->before();
		
		// Call action & after olny when response not cached
		if ( ! $this->_content_cached)
		{
			// Execute the action itself
			$this->{$action}();
			
			// Execute the "after action" method
			$this->after();
		}
		
		// Return the response
		return $this->response;
	}

	/**
	 * Load controller configuration from config parts
	 * 
	 * @return  void
	 */
	public function set_config()
	{
		// Add controller and action config parts
		if (count($this->get_actions()) > 1)
		{
			$this->config[] = dirname($this->_path);
		}
		$this->config[] = $this->_path;
		
		parent::set_config();
	}

	/**
	 * Searched controller content in cache.
	 * If content is found, send it in response 
	 * and check client browser cache.
	 *
	 * @return  void
	 */
	public function get_cached_content()
	{
		// Generate cache tag
		$this->_tag = CMS::tag('controller', $this->request->url());
		
		// Try load content from cache
		if ( ! $content = $this->cache()->get($this->_tag, FALSE))
		{
			return NULL;
		}
		
		// Send cached content in response
		$this->response->body($content);
		
		if ($this->request->is_initial())
		{
			/**
			 * Checks the browser cache to see the response needs to be returned, 
			 * execution will halt and a 304 Not Modified will be sent 
			 * if the browser cache is up to date.
			 */
			$this->check_cache($this->_tag);
		}
		
		$this->_content_cached = TRUE;
		$this->auto_render     = FALSE;
	}

	/**
	 * Automatically executed before the controller action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function set_content_view()
	{
		if ( ! $this->content)
		{
			// Set auto generated path to View file
			$this->content = $this->_path;
		}
		// Create content View
		$this->content = SView::factory($this->content);
	}

	/**
	 * Assets managment. Add CSS\JS\LESS files.
	 *
	 * @return  void
	 */
	public function set_assets()
	{
		// TODO: fix Assets module, add Config Manager module
		$assets = Arr::extract($this->config['assets'], array('css', 'js'));
		$this->assets()->set('css', $assets['css'])->set('js', $assets['js']);
		unset($this->config['assets']);
	}

	/**
	 * Automatically executed before the controller action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{
		// Set path to controller files
		$this->_path = CMS::path($this->request, (count($this->get_actions()) > 1));
		
		/**
		 * Controller:
		 * 1. ---
		 * Controller_Basic:
		 * 1. parent::before()
		 * 2. $this->check_auth();
		 * 3. $this->set_config();
		 */
		parent::before();
		
		if (Kohana::$caching AND $this->auto_cache)
		{
			$this->get_cached_content();
		}
		
		if ($this->auto_render AND ! $this->_content_cached)
		{
			$this->set_content_view();
		}
		
		if (isset ($this->config['assets']) AND ! empty($this->config['assets']))
		{
			$this->set_assets();
		}
	}

	/**
	 *  Render content and wrap in layout 
	 * 
	 * @return  string
	 */
	public function render_content()
	{
		if ( ! $this->request->is_external() AND ! $this->request->is_initial())
		{
			// Not wrap included content (widgets)
			return $this->content->render();
		}
		// Render and wrap content
		$this->wrapper = SView::factory($this->wrapper);
		$this->wrapper->content = $this->content->render();
		
		return $this->wrapper->render();
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
			$this->content = $this->render_content();
		}
		
		/**
		 * Controller:
		 * 1. ---
		 * Controller_Basic:
		 * 1. $this->set_response();
		 * 2. parent::after();
		 */
		parent::after();
		
		if (Kohana::$caching AND $this->auto_cache)
		{
			// Save content in cache
			$this->cache()->set($this->_tag, $this->response->body(), Date::HOUR);
		}
	}

} // End Controller_Layout