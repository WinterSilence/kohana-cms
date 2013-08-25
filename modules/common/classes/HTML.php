<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * HTML helper class. Provides generic methods for generating various HTML
 * tags and making output HTML safe.
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Kohana Team
 * @copyright  (c) 2007-2013 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class HTML extends Kohana_HTML
{
	/**
	 * Create a meta tag.
	 *
	 * @param  string              $name
	 * @param  mixed(string|array) $attributes  
	 * @return string
	 * @uses   HTML::attributes
	 */
	public static function meta($name, $attributes)
	{
		if ($name == 'title')
		{
			return '<title>'.implode(' - ', (array) $attributes).'</title>';
		}
		if ( ! is_array($attributes))
		{
			$attributes = array('name' => $name, 'content' => $attributes);
		}
		return '<meta'.HTML::attributes($attributes).' />';
	}

	/**
	 * Magic method: short aliases for anchor and file_anchor
	 *
	 * @param   string  $name       Method name
	 * @param   array   $arguments  Sended arguments
	 * @return  mixed
	 */
	public static function __callStatic($name, $arguments)
	{
		switch ($name)
		{
			case 'a':
				return call_user_func_array(array('HTML', 'anchor'), $arguments);
			case 'file_a':
			case 'a_file':
				return call_user_func_array(array('HTML', 'file_anchor'), $arguments);
		}
		throw new Kohana_Exception('Call undefined static method :name', array(':name' => $name));
	}
	
}