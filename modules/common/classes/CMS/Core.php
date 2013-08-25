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
	 * @throws  Kohana_Exception
	 */
	public static function url($params_str, $escape_html = TRUE, $lower_case = TRUE, $protocol = NULL)
	{
		if (empty($params_str))
		{
			// Get current page URL
			$url = Request::current()->url($protocol);
		}
		else
		{
			// Try load URL from cache
			if (Kohana::$caching)
			{
				$tag = 'url:'.$params_str.':'.$escape_html.':'.$lower_case.':'.$protocol;
				
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
				throw new Kohana_Exception('Wrong url format: :url', array(':url' => $params_str));
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
		if ($params_str AND Kohana::$caching)
		{
			Kohana::cache($tag, $url, Date::DAY);
		}
		
		return $url;
	}

	/**
	 * Generate path for controllers and models
	 *
	 * @param  mixed   $class      Object or class name
	 * @param  mixed   $action     Controller action
	 * @param  string  $separator  Directory separator
	 * @return string
	 */
	public static function path($class, $action = NULL, $separator = DS)
	{
		if ($class instanceof Controller)
		{
			$name = substr(get_class($class), 11);
		}
		elseif ($class instanceof Request)
		{
			$name = ltrim($class->directory().$separator.$class->controller(), $separator);
		}
		elseif (is_object($class))
		{
			$name = get_class($class);
		}
		else
		{
			$name = (string) $class;
		}
		
		$name = str_replace('_', $separator, $name);
		
		if ( ! empty($action))
		{
			$name .= $separator.(($class instanceof Request) ? $class->action() : $action);
		}
		
		return strtolower($name);
	}

	/**
	 * Wrapper for request to widget controller
	 * 
	 * @param   string  $uri           Request uri(widget name)
	 * @param   mixed   $data          Send data
	 * @param   string  $method        Request method
	 * @param   boolean $auto_render   Execute request and return body?
	 * @return  mixed
	 * @uses    Request::factory
	 * @uses    HTML::chars
	 * @throws  Request_Exception
	 */
	public static function widget($uri, array $data = NULL, $method = Request::GET, $auto_render = TRUE)
	{
		$uri = str_replace(array(' ', '_'), '/', $uri);
		// Create request
		if ( ! $widget = Request::factory('widget/'.$uri))
		{
			throw new Request_Exception('Request to widget :uri failed', array(':uri' => $uri));
		}
		// Set data
		switch ($method)
		{
			case Request::GET:
				$widget->query($data);
				break;
			case Request::POST:
				$widget->post($data);
				break;
			default:
				throw new Request_Exception('Unknown method :method of widget :uri', 
					array(':method' => $method, ':uri' => $uri));
		}
		// Set data sending method
		$widget->method($method);
		// Return request body or object
		return ($auto_render ? $widget->execute()->body() : $widget);
	}

	/**
	 * Wrapper for work with small Views aka snippets
	 *
	 * @param   string   $uri          View uri(snippet name)
	 * @param   mixed    $data         View variables
	 * @param   boolean  $auto_render  Return render View?
	 * @return  mixed   
	 * @uses    SView::factory
	 * @throws  View_Exception
	 */
	public static function snippet($uri, array $data = NULL, $auto_render = TRUE)
	{
		$uri = str_replace(array(' ', '_'), '/', $uri);
		if ( ! $snippet = SView::factory('snippet'.DS.$uri, $data))
		{
			throw new View_Exception('Snippet :uri not found', array(':uri' => $uri));
		}
		return ($auto_render ? $snippet->render() : $snippet);
	}

	/**
	 * Multi converter data types. Optimized for AJAX data types(text, html, json, xml).
	 * 
	 * @param   mixed   $data  Convert data
	 * @param   string  $to    Convert to type
	 * @param   string  $from  Data type
	 * @return  mixed
	 * @uses    XML
	 * @throws  Kohana_Exception
	 */
	public static function convert($data, $to = 'json', $from = NULL)
	{
		// At first try use easy convert
		if (settype($data, $to))
		{
			return $data;
		}
		// Auto detect type
		if (empty($from))
		{
			$from = gettype($data);
		}
		// Conversion
		switch ($from.'_'.$to)
		{
			// Convert in text
			case 'string_text':
				return strip_tags($data);
			case 'array_text':
				return strip_tags(implode($data));
			case 'object_text':
				return (strip_tags(var_export($data, TRUE)));
			// Convert in HTML
			case 'string_html':
				return $data;
			case 'array_html':
				return implode($data);
			case 'object_html':
				return var_export($data, TRUE);
			// Convert in JSON
			case 'string_json':
			case 'array_json':
			case 'object_json':
				return json_encode($data);
			// Convert in XML
			case 'string_xml':
				return simplexml_load_string($data);
			case 'array_xml':
				return (new XML)->from_array($data);
			case 'object_xml':
				return simplexml_load_string(var_export($data, TRUE));
			// Convert in JSON
			case 'xml_array':
				return (new XML)->as_array($data);
			case 'xml_string':
			case 'xml_text':
			case 'xml_html':
				return var_export($data, TRUE);
			// Convert in array
			case 'xml_array':
				return (new XML)->as_array($data);
			// Error - unable to convert
			default:
				throw new Kohana_Exception('Convert error :from -> :to', 
					array(':from' => $from, ':to' => $to));
		}
	}

}