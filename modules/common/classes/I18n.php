<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Internationalization (i18n) class. Provides language loading and translation
 * methods without dependencies on [gettext](http://php.net/gettext).
 *
 * Typically this class would never be used directly, but used via the __()
 * function, which loads the message and replaces parameters:
 *
 *     // Display a translated message
 *     echo __('Hello, world');
 *
 *     // With parameter replacement
 *     echo __('Hello, :user', array(':user' => $username));
 *
 * @package    Kohana
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class I18n extends Kohana_I18n
{
	/**
	 * Returns translation of a string. If no translation exists, the original
	 * string will be returned. No parameters are replaced.
	 *
	 *     $hello = I18n::get('Hello friends, my name is :name');
	 *
	 * @param   string  $string text to translate
	 * @param   string  $lang   target language
	 * @return  string
	 */
	public static function get($string, $lang = NULL)
	{
		if ( ! $lang)
		{
			$lang = I18n::$lang;
		}
		$table = I18n::load($lang);
		
		if (isset($table[$string]))
		{
			return $table[$string];
		}
		if (Kohana::$environment !== Kohana::PRODUCTION)
		{
			/**
			 * If the project is at the development stage, it is not all 
			 * localized strings are stored in a separate file
			 */ 
			I18n::append($string, $lang);
		}
		return $string;
	}

	/**
	 * Append not translated string in file
	 *
	 * @param string  $string text to translate
	 * @param string  $lang   target language
	 * @return void
	 * @uses Kohana::load
	 * @uses File::var_export
	 */
	public static function append($string, $lang)
	{
		$file = APPPATH.'i18n'.DS.str_replace('-', DS, $lang).'_'.EXT;
		$table = file_exists($file) ? Kohana::load($file) : array();
		$table[$string] = $string;
		File::var_export($table, $file);
	}

} // End I18n