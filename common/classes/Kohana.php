<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Extension: Contains the most low-level helpers methods in Kohana:
 *
 * - Environment initialization
 * - Locating files within the cascading filesystem
 * - Auto-loading and transparent extension of classes
 * - Variable and path debugging
 *
 * @package   CMS/Common
 * @category  Base
 * @author    WinterSilence
 */
class Kohana extends Kohana_Core
{
	/**
	 * Kohana config wrapper
	 *
	 * @param  string  $name      Configuration name or name & group
	 * @param  bool    $as_array  Return as array?
	 * @return mixed(array|Config)
	 */
	public static function config($name, $as_array = FALSE)
	{
		$config = Kohana::$config->load($name);
		if ($as_array)
			return is_object($config) ? $config->as_array() : (array) $config;
		else
			return $config;
	}

} // Kohana