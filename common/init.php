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

// Path to common dir
if ( ! defined('COMMONPATH'))
{
	define('COMMONPATH', __DIR__.DS);
}