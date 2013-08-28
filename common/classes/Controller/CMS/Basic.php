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
	 * @var  boolean
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
	 * Content template
	 * @var mixed(string|View)
	 */
	public $content = NULL;

	/**
	 * View auto render?
	 * @var boolean 
	 */
	public $auto_render = TRUE;

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
	 */
	protected function _check_auth()
	{
		if ($this->check_auth)
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
	}

	/**
	 * Load controller configuration
	 *
	 * @return  void
	 */
	protected function _load_config()
	{
		if (Kohana::$caching)
		{
			// Cache id
			$tag = 'controller_config:'.implode(':', $this->config);
			
			if ($cfg = $this->cache()->get($tag, FALSE))
			{
				// Load from cache
				$this->config = (array) $cfg;
				
				return NULL;
			}
		}
		// Load and merge config parts
		$cfg = array();
		foreach ($this->config as $config)
		{
			$cfg = Arr::merge($cfg, Kohana::config($config, TRUE));
		}
		$this->config = $cfg;
		
		// Save in cache
		if (Kohana::$caching)
		{
			$this->cache()->set($tag, $this->config, Date::DAY);
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
		
		// Check user access to action
		$this->_check_auth();
		
		// Load controller configuration
		$this->_load_config();
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
		// Set HTTP header fields
		if ( ! empty($this->headers) AND $this->request->is_initial())
		{
			$this->response->headers($this->headers);
		}
		
		// Render and set response content
		if ($this->auto_render)
		{
			// $this->response->body($this->content->render());
			$this->response->body($this->content);
		}
		
		parent::after();
	}

	/**
	 * Issues a HTTP redirect.
	 *
	 * Proxies to the [HTTP::redirect] method.
	 *
	 * @param  string  $uri   URI to redirect to
	 * @param  int     $code  HTTP Status code to use for the redirect
	 * @throws HTTP_Exception
	 */
	public function redirect($uri = '', $code = 302)
	{
		$this->auto_render = FALSE;
		
		return HTTP::redirect( (string) $uri, $code);
	}

} // End Controller_Basic