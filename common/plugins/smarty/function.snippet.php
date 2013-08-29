<?php
/**
 * Kohana CMS snippet plugin
 * 
 * @package Smarty
 * @subpackage PluginsFunction
 */
function smarty_function_snippet(array $params = NULL)
{
	if ( ! isset($params['name']))
	{
		throw new CMS_Exception('Snippet name is not specified');
	}
	
	$name = $params['name'];
	unset($params['name']);
	
	return CMS::snippet($name, $params);
}
