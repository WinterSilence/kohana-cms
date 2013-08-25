<?php defined('SYSPATH') OR die('No direct script access.');

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require_once SYSPATH.'classes/Kohana/Core'.EXT;

if (is_file(APPPATH.'classes/Kohana'.EXT))
{
	// Application extends the core
	require_once APPPATH.'classes/Kohana'.EXT;
}
elseif(is_file(MODPATH.'common/classes/Kohana'.EXT))
{
	// Common module extends the core
	require_once MODPATH.'common/classes/Kohana'.EXT;
}
else
{
	// Load empty core extension
	require_once SYSPATH.'classes/Kohana'.EXT;
}

/**
 * Enable the Kohana auto-loader.
 *
 * @link http://kohanaframework.org/guide/using.autoloading
 * @link http://www.php.net/manual/function.spl-autoload-register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Optionally, you can enable a compatibility auto-loader for use with
 * older modules that have not been updated for PSR-0.
 *
 * It is recommended to not enable this unless absolutely necessary.
 */
// spl_autoload_register(array('Kohana', 'auto_load_lowercase'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @link http://www.php.net/manual/function.spl-autoload-call
 * @link http://www.php.net/manual/var.configuration#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

/**
 * Load bootstrap config array
 */
$bootstrap = require_once DOCROOT.'config'.EXT;

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
if (isset($_SERVER['KOHANA_ENV']) AND $_SERVER['SERVER_ADDR'] == $bootstrap['server']['address'])
{
	Kohana::$environment = constant('Kohana::'.strtoupper($_SERVER['KOHANA_ENV']));
}
else
{
	// Hide error messages in production environment
	error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT);
}

/**
 * Set the default time zone.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/timezones
 */
date_default_timezone_set($bootstrap['timezone']);

/**
 * Set the default locale.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/function.setlocale
 */
setlocale(LC_ALL, $bootstrap['locale'].'.'.$bootstrap['charset']);

// -- Configuration and initialization -----------------------------------------

/**
 * Cookie configuration
 */
foreach ($bootstrap['cookie'] as $key => $value)
{
	Cookie::${$key} = $value;
}

/**
 * Initialize Kohana, setting the default options.
 */
Kohana::init($bootstrap['init']);

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config.
 */
Kohana::$config->attach(new Config_File);

/**
 * Require application bootstrap
 */
require_once APPPATH.'bootstrap'.EXT;

/**
 * Set the default language
 */
I18n::lang($bootstrap['language']);

/**
 * Encrypt configuration, set default algorithm
 */
Encrypt::$default = $bootstrap['encrypt'];

// Clean up the bootstrap config array
unset($bootstrap);

// Add routes add before this string !!!
if ( ! Route::cache())
{
	// Set routes cache
	Route::cache(Kohana::$caching);
}