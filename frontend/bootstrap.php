<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(
	'common'	=> DOCROOT.'common',	// Common applications files
	'cache'		=> MODPATH.'cache',		// Caching with multiple backends
	'auth'		=> MODPATH.'auth',		// Basic authentication
	'database'	=> MODPATH.'database',	// Database access
	'orm'		=> MODPATH.'orm',		// Object Relationship Mapping
	//'mptt'		=> MODPATH.'mptt',		// ORM nested sets tree
	'image'		=> MODPATH.'image',		// Image manipulation
	'message'	=> MODPATH.'message',	// Sending flash messages
	'captcha'	=> MODPATH.'captcha',	// Anti Spam Picure
	'email'		=> MODPATH.'email',		// Sends e-mail (based on SwiftMail)
	'assets'	=> MODPATH.'assets',	// Assets manager for CSS\LESS\JS files
));

//Session::$default = 'native'; // 'database'

/**
 * Attach a database reader to config.
 */
//Kohana::$config->attach(ORM::factory('Config'), TRUE); 
//Kohana::$config->attach(new DB_Config, TRUE); 

/**
 * Set Upload helper settings
 */
//Upload::$default_directory = MEDIAPATH.'upload'.DS;

 /**
 * Set cache settings
 * @link http://en.wikipedia.org/wiki/List_of_PHP_accelerators
 */
/*
if (function_exists('apc_store') AND ini_get('apc.enabled'))
{
	Cache::$default = 'apc';
}
*/
 
 /**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */
 