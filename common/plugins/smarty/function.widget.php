<?php 
/**
 * Kohana CMS widget plugin
 * 
 * @package Smarty
 * @subpackage PluginsFunction
 */
function smarty_function_widget(array $params = NULL)
{
	if ( ! isset($params['name']))
	{
		throw new CMS_Exception('Widget name is not specified');
	}
	
	$name = $params['name'];
	unset($params['name']);
	
	return CMS::widget($name, $params);
}