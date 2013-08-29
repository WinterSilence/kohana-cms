<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * CMS helper
 *
 * @package   CMS/Common
 * @category  Helpers
 * @author    WinterSilence
 */ 
abstract class CMS_Core
{
	// Public directory with images
	const URL_IMG = '/assets/img/';

	/**
	 * Create identification tag(id) from the methods attributes.
	 * Basically used for get or set cache.
	 * 
	 * @static
	 * @return  string
	 */
	public static function tag()
	{
		return I18n::lang().'_'.sha1(serialize(func_get_args()));
	}

	/**
	 * Return list of controller actions, `actions_` part is cut off.
	 * 
	 * @static
	 * @param  mixed  $class  The class name or an object instance
	 * @return array
	 */
	public static function actions($class)
	{
		$actions = array();
		foreach ( (array) get_class_methods($class) as $method)
		{
			if (substr($method, 0, 7) === 'action_')
			{
				$actions[] = substr($method, 7);
			}
		}
		return $actions;
	}

	/**
	 * Create URL based on route. This is a shortcut for: Route::url. URL cached.
	 * 
	 *     CMS::url('route:default'); // Home page
	 *     CMS::url(''); // Current URL
	 *     CMS::url('route:current,action:delete,id:2'); // Based on current route
	 *     CMS::url('controller:article,action:list,page:2'); // Based on default route
	 *     CMS::url('route:articles,controller:article,action:list,page:2');
	 * 
	 * @param   string   $params       String with route params
	 * @param   boolean  $escape_html  Convert url special characters to HTML entities?
	 * @param   boolean  $lower_case   Convert url to lower case?
	 * @param   mixed    $protocol     Protocol string or boolean, adds protocol and domain
	 * @return  string
	 * @uses    Request, Route, HTML, Text, UTF8
	 * @uses    CMS::tag
	 * @throws  CMS_Exception
	 */
	public static function url($params_str, $escape_html = TRUE, $lower_case = TRUE, $protocol = NULL)
	{
		if (empty($params_str))
		{
			// Get current page URL
			$url = Request::initial()->url($protocol);
		}
		else
		{
			if (Kohana::$caching)
			{
				// Cache tag(id)
				$tag = CMS::tag('url', $params_str, $escape_html, $lower_case, $protocol);
				// Try load URL from cache
				if ($url = Kohana::cache($tag))
				{
					return (string) $url;
				}
			}
			
			// Modify URL string in JSON and convert in array
			$url = str_replace(array(',', ':'), array('","', '":"'), $params_str);
			if ($params = json_decode('{"'.$url.'"}', TRUE, 2))
			{
				$route = isset($params['route']) ? $params['route'] : 'default';
				unset($params['route']);
				
				if ($route == 'current')
				{
					$request = Request::current();
					
					$params = array_merge($request->param(), $params);
					$params += array(
						'directory'  => $request->directory(),
						'controller' => $request->controller(),
						'action'     => $request->action(),
					);
					
					$route = Route::name($request->route());
				}
				
				$url = Route::url($route, $params, $protocol);
			}
			else
			{
				// Use links in format: 'option:value,option2:value2'
				throw new CMS_Exception('Wrong url format: :url', array(':url' => $params_str));
			}
		}
		
		// Use URL modificators
		$url  = ($escape_html ? HTML::chars($url) : $url);
		$url  = ($lower_case ? UTF8::strtolower($url) : $url);
		$url  = str_replace('\\', '/', $url).'/';
		$url  = Text::reduce_slashes($url);
		/*
			TODO: 
			check this APP_ALIAS.$url
			add URL::query($params, FALSE); 
			if ($params = $route->matches($uri)) // Parse the parameters
		*/
		
		// Save result URL in cache
		if ( ! empty($params_str) AND Kohana::$caching)
		{
			Kohana::cache($tag, $url, Date::DAY);
		}
		
		return $url;
	}

	/**
	 * Generate path for controllers and models
	 *
	 * @param  mixed   $class   Object or class name
	 * @param  mixed   $action  Controller action
	 * @param  string  $sep     Use as word separator
	 * @return string
	 */
	public static function path($class, $action = FALSE, $sep = DS)
	{
		if ($class instanceof Controller)
		{
			$name = substr(get_class($class), 11);
		}
		elseif ($class instanceof Request)
		{
			$name = ltrim($class->directory().$sep.$class->controller(), $sep);
		}
		elseif (is_object($class))
		{
			$name = get_class($class);
		}
		else
		{
			$name = (string) $class;
		}
		
		$name = str_replace('_', $sep, $name);
		
		if ($action)
		{
			$name .= $sep.($class instanceof Request ? $class->action() : $action);
		}
		
		return strtolower($name);
	}

	/**
	 * Wrapper for request to widget controller
	 * 
	 * @param   string  $name    Request uri(widget name)
	 * @param   mixed   $data    Send data
	 * @param   string  $method  Request method
	 * @return  mixed
	 * @uses    Request::factory
	 * @uses    HTML::chars
	 * @throws  CMS_Exception
	 */
	public static function widget($name, array $data = NULL, $method = Request::GET)
	{
		// Convert name in valid uri format
		$name = str_replace(array(' ', '_', '\\'), '/', $name);
		
		// Create request object
		if ( ! $widget = Request::factory('widget/'.$name))
		{
			throw new CMS_Exception('Widget :name: request failed', array(':name' => $name));
		}
		
		// Set request data
		switch ($method)
		{
			case Request::GET:
				$widget->query($data);
				break;
			
			case Request::POST:
				$widget->post($data);
				break;
			
			default:
				throw new CMS_Exception('Widget :name: invalid method :method', array(
					':method' => $method, 
					':name'   => $name,
				));
		}
		
		// Return rendered request content
		return $widget->method($method)->execute()->body();
	}

	/**
	 * Wrapper for work with easy Views aka snippets
	 *
	 * @param   string  $name  Path to snippet View file
	 * @param   mixed   $data  View variables
	 * @return  string
	 * @uses    SView::factory
	 * @uses    Kohana::find_file
	 * @uses    Assets::instance
	 * @throws  CMS_Exception
	 */
	public static function snippet($name, array $data = NULL)
	{
		// Convert name in path
		$name = 'snippet'.DS.str_replace(array(' ', '_', '\\'), DS, trim($name, '/\\'));
		
		// TODO: fix path's
		// Add assets JS\CSS files
		if (class_exists('Assets'))
		{
			if (Kohana::find_file('media', 'css'.DS.$name, 'css'))
			{
				Assets::instance()->set('css', $name.'.css');
			}
			if (Kohana::find_file('media', 'js'.DS.$name, 'js'))
			{
				Assets::instance()->set('js', $name.'.js');
			}
		}
		if (Kohana::$caching)
		{
			// Cache tag(id) for find
			$tag = CMS::tag('snippet', $name, I18n::lang(), $data);
			
			// Try load from cache
			if ($snippet = Kohana::cache($tag))
			{
				return $snippet;
			}
		}
		// Create and render
		if ( ! $snippet = SView::factory($name, $data)->render())
		{
			throw new CMS_Exception('Snippet :name: error at create', array(':name' => $name));
		}
		elseif (Kohana::$caching)
		{
			// Save content in cache
			Kohana::cache($tag, $snippet, Date::DAY);
		}
		return $snippet;
	}

	/**
	 * Multi converter data types. Optimized for AJAX data types(text, html, json, xml).
	 * 
	 * @param   mixed   $data  Convert data
	 * @param   string  $to    Convert to type
	 * @param   string  $from  Data type
	 * @return  mixed
	 * @uses    XML
	 * @throws  CMS_Exception
	 */
	public static function convert($data, $to = 'json', $from = NULL)
	{
		// At first try use easy convert
		if (settype($data, $to))
		{
			return $data;
		}
		
		// Auto detect type
		$from = (is_null($from) ? gettype($data) : $from);
		
		// Converting data
		switch ($from.'_'.$to)
		{
			// Convert .. in HTML/string
			case 'array_html':
			case 'array_string':
				return implode(PHP_EOL, $data);
			case 'object_html':
				return var_export($data, TRUE);
			// Convert .. in JSON
			case 'string_json':
			case 'array_json':
			case 'object_json':
				return json_encode($data);
			// Convert .. in XML
			case 'string_xml':
				return simplexml_load_string($data);
			case 'array_xml':
				return (new XML)->from_array($data);
			case 'object_xml':
				return simplexml_load_string(var_export($data, TRUE));
			// Convert XML in ..
			case 'xml_array':
				return (new XML)->as_array($data);
			case 'xml_string':
			case 'xml_html':
				return var_export($data, TRUE);
			// Error: unable to convert
			default:
				throw new CMS_Exception('Convert error: from :from to :to', array(
					':from' => $from, 
					':to'   => $to,
				));
		}
	}

}