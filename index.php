<?php
// Short aliases for system constants 
define('DS',  DIRECTORY_SEPARATOR);
define('EOL', PHP_EOL);

// Wrapper for construction empty(), works with objects
function is_empty($value)
{
	return (empty($value) OR ($value instanceof Countable AND count($value) === 0));
}

// Short version for `if (is_null($var)) $var = $value;` and `if ( ! isset($var)) $var = $value;`
function set_if_null( & $var, $value)
{
	return (is_null($var) ? ($var = $value) : $var);
}

// Short version for `if (empty($var)) $var = $value;`
function set_if_empty( & $var, $value)
{
	return (empty($var) ? ($var = $value) : $var);
}

// Short version for `if (isset($value)) $var = $value;`
function set_if_isset( & $value, & $var)
{
	if ( ! isset($value))
	{
		return FALSE;
	}
	$var = $value;
	return TRUE;
}

/**
 * The directory in which your application specific resources are located.
 * The application directory must contain the bootstrap.php file.
 *
 * @link http://kohanaframework.org/guide/about.install#application
 */
$application = (isset($application) ? $application : 'frontend');
define('APP', $application);

define('APP_ALIAS', (isset($app_alias) ? $app_alias : '/'));

/**
 * The directory in which your modules are located.
 *
 * @link http://kohanaframework.org/guide/about.install#modules
 */
$modules = 'modules';

/**
 * The directory in which the Kohana resources are located. The system
 * directory must contain the classes/kohana.php file.
 *
 * @link http://kohanaframework.org/guide/about.install#system
 */
$system = 'system';

/**
 * The default extension of resource files. If you change this, all resources
 * must be renamed to use the new extension.
 *
 * @link http://kohanaframework.org/guide/about.install#ext
 */
define('EXT', '.php');

/**
 * Set the PHP error reporting level. If you set this in php.ini, you remove this.
 * @link http://www.php.net/manual/errorfunc.configuration#ini.error-reporting
 *
 * When developing your application, it is highly recommended to enable notices
 * and strict warnings. Enable them by using: E_ALL | E_STRICT
 *
 * In a production environment, it is safe to ignore notices and strict warnings.
 * Disable them by using: E_ALL ^ E_NOTICE
 *
 * When using a legacy application with PHP >= 5.3, it is recommended to disable
 * deprecated notices. Disable with: E_ALL & ~E_DEPRECATED
 */
error_reporting(E_ALL | E_STRICT);

/**
 * End of standard configuration! Changing any of the code below should only be
 * attempted by those with a working knowledge of Kohana internals.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 */

// Set the full path to the docroot
define('DOCROOT', realpath(__DIR__).DS);

// Make the application relative to the docroot, for symlink'd index.php
if ( ! is_dir($application) AND is_dir(DOCROOT.$application))
	$application = DOCROOT.$application;

// Make the modules relative to the docroot, for symlink'd index.php
if ( ! is_dir($modules) AND is_dir(DOCROOT.$modules))
	$modules = DOCROOT.$modules;

// Make the system relative to the docroot, for symlink'd index.php
if ( ! is_dir($system) AND is_dir(DOCROOT.$system))
	$system = DOCROOT.$system;

// Define the absolute paths for configured directories
define('APPPATH',    realpath($application).DS);
define('MODPATH',    realpath($modules).DS);
define('SYSPATH',    realpath($system).DS);

// Clean up the configuration vars
unset($application, $modules, $system);

/*
if (file_exists('install'.EXT))
{
	// Load the installation check
	return include 'install'.EXT;
}
*/

/**
 * Define the start time of the application, used for profiling.
 */
if ( ! defined('KOHANA_START_TIME'))
{
	define('KOHANA_START_TIME', microtime(TRUE));
}

/**
 * Define the memory usage at the start of the application, used for profiling.
 */
if ( ! defined('KOHANA_START_MEMORY'))
{
	define('KOHANA_START_MEMORY', memory_get_usage());
}

// Common bootstrap (includes the application bootstrap)
require_once DOCROOT.'bootstrap'.EXT;

/**
 * Execute the main request. A source of the URI can be passed, eg: $_SERVER['PATH_INFO'].
 * If no source is specified, the URI will be automatically detected.
 */
if (PHP_SAPI !== 'cli')
{
	echo Request::factory(TRUE, array(), FALSE)
		->execute()
		->send_headers(TRUE)
		->body();
}