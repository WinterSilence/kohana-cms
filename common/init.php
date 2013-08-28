<?php defined('SYSPATH') OR die('No direct script access.');

// Smarty engine autoloader
function smarty_autoload($class)
{
	if ($class == 'Smarty' OR $class == 'SmartyBC')
	{
		require_once 'vendor'.DS.'smarty'.DS.$class.'.class'.EXT;
	}
}
spl_autoload_register('smarty_autoload');

// Common module path
if ( ! defined('COMMONPATH'))
{
	define('COMMONPATH', realpath(__DIR__).DS);
}

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */
if ( ! Route::cache())
{
	Route::set('ajax', 'ajax/<controller>(/<action>(/<data_type>))', array(
			'data_type'  => '(html|json|xml)',
		))
		->defaults(array(
			'directory'  => 'Ajax',
			'controller' => '',
			'action'     => 'index',
			'data_type'  => 'json',
		));
	
	Route::set('widget', 'widget<directory>/<controller>', array(
		'directory'  => '[\w\-]*',
		))
		->defaults(array(
			'directory'  => 'Widget',
			'controller' => '',
			'action'     => 'index',
		));
	
	Route::set('list', '<controller>/list(/<page>(-<total>(/<order>(/<direction>))))', array(
			'page'       => '[0-9]+',
			'total'      => '[0-9]+',
			'order'      => '[\w\-]+',
			'direction'  => '(asc|desc)',
		))
		->defaults(array(
			'directory'  => 'Page',
			'controller' => '',
			'action'     => 'list',
			'page'       => '1',
			'total'      => '0',
			'order'      => 'id',
			'direction'  => 'asc',
		));
	
	Route::set('default', '(<controller>(/<action>(/<id>)))', array(
			'id' => '[\w\-]+',
		))
		->defaults(array(
			'directory'  => 'Page',
			'controller' => 'Home',
			'action'     => 'index',
		));
}