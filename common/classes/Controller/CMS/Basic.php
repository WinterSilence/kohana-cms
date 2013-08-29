<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Basic primary controller
 *
 * @package   CMS/Common
 * @category  Controller
 * @author    WinterSilence
 */ 
abstract class Controller_CMS_Basic extends Controller
{
	/**
	 * Check user auth?
	 * @var  bool
	 */
	public $check_auth = TRUE;

	/**
	 * List the roles required to access
	 * @var  array
	 */
	public $auth_roles = array();

	/**
	 * Controller configuration
	 * @var array
	 */
	public $config = array('cms');

	/**
	 * HTTP header fields
	 * @var array  
	 */
	public $headers = array();

	/**
	 * Response content template
	 * @var mixed
	 */
	public $content = NULL;

	/**
	 * Wrapper for CMS::actions method.
	 * Returned list of action names.
	 *
	 * @access  public
	 * @return  array
	 * @uses    CMS::actions
	 */
	public function get_actions()
	{
		static $actions;
		
		if ( ! $actions)
		{
			$actions = CMS::actions($this);
		}
		
		return $actions;
	}

	/**
	 * Wrapper for request post method
	 * Gets or sets HTTP POST parameters to the request.
	 *
	 * @param   mixed  $key    Key or key value pairs to set
	 * @param   string $value  Value to set to a key
	 * @return  mixed
	 */
	public function post($key = NULL, $value = NULL)
	{
		return $this->request->post($key, $value);
	}

	/**
	 * Wrapper for request query method
	 * Gets or sets HTTP query string.
	 *
	 * @param   mixed   $key    Key or key value pairs to set
	 * @param   string  $value  Value to set to a key
	 * @return  mixed
	 */
	public function query($key = NULL, $value = NULL)
	{
		return $this->request->query($key, $value);
	}

	/**
	 * Wrapper for request param method
	 * Retrieves a value from the route parameters.
	 *
	 * @param   string   $key      Key of the value
	 * @param   mixed    $default  Default value if the key is not set
	 * @return  mixed
	 */
	public function param($key = NULL, $default = NULL)
	{
		return $this->request->param($key, $default);
	}

	/**
	 * Auth instance wrapper
	 * 
	 * @return Auth
	 */
	public function auth()
	{
		return Auth::instance();
	}

	/**
	 * User object wrapper
	 * 
	 * @return Model_User ORM
	 */
	public function user()
	{
		return $this->auth()->get_user();
	}

	/**
	 * Session instance wrapper
	 * 
	 * @return Session
	 */
	public function session($group = NULL)
	{
		return Session::instance($group);
	}

	/**
	 * Cache instance wrapper
	 * 
	 * @return Cache
	 */
	public function cache($group = NULL)
	{
		return Cache::instance($group);
	}

	/**
	 * Check user authentication and authorization
	 *
	 * @return  void
	 * @throw   HTTP_Exception
	 * @uses    Auth::logged_in
	 * @uses    HTTP_Exception::factory
	 */
	public function check_auth()
	{
		if ( ! $this->user())
		{
			throw HTTP_Exception::factory(401, 'Unauthorized user');
		}
		elseif ( ! $this->auth()->logged_in($this->auth_roles))
		{
			throw HTTP_Exception::factory(403, 'Access forbidden');
		}
	}

	/**
	 * Load controller configuration from config parts
	 *
	 * @uses    CMS::tag
	 * @uses    Arr::merge
	 * @uses    Kohana::config
	 * @return  void
	 */
	public function set_config()
	{
		if (Kohana::$caching)
		{
			// Generate tag
			$tag = CMS::tag('controller_config', $this->config);
			// Try load from cache
			if ($config = $this->cache()->get($tag, FALSE))
			{
				$this->config = $config;
				return NULL;
			}
		}
		
		$config = array();
		foreach ($this->config as $name)
		{
			// Load and merge config parts
			$config = Arr::merge($config, Kohana::config($name, TRUE));
		}
		$this->config = $config;
		
		if (Kohana::$caching AND ! empty($this->config))
		{
			// Save in cache
			$this->cache()->set($tag, $this->config, Date::HOUR);
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
		/**
		 * Controller:
		 * 1. ---
		 */
		parent::before();
		
		if ($this->check_auth)
		{
			$this->check_auth();
		}
		
		if ( ! empty($this->config))
		{
			$this->set_config();
		}
	}

	/**
	 * Sets request response options
	 *
	 * @return  void
	 */
	public function set_response()
	{
		if ( ! empty($this->headers) AND $this->request->is_initial())
		{
			// Sets HTTP header fields
			$this->response->headers($this->headers);
		}
		
		if ( ! empty($this->content))
		{
			// Send content in response body
			$this->response->body($this->content);
		}
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
		$this-> set_response();
		
		/**
		 * Controller:
		 * 1. ---
		 */
		parent::after();
	}

} // End Controller_Basic