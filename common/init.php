<?php

// Path to common dir
if ( ! defined('COMMONPATH'))
{
	define('COMMONPATH', __DIR__.DS);
}

// Smarty engine autoloader
spl_autoload_register(function ($class) {
	if ($class == 'Smarty' OR $class == 'SmartyBC')
	{
		require_once COMMONPATH.'vendor'.DS.'smarty'.DS.$class.'.class'.EXT;
	}
});
