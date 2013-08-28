<?php 
/**
 * Kohana CMS widget plugin
 * 
 * @package Smarty
 * @subpackage PluginsFunction
 */
function smarty_function_widget(array $params = NULL)
{
	if (isset($params['name']))
	{
		if ($widget = CMS::widget($params['name'], $params))
		{
			return EOL.$widget.EOL;
		}
		else
		{
			throw new View_Exception('Widget :name is not specified', array(':name', $params['name']));
		}
	}
	else
	{
		throw new View_Exception('Widget name is not specified');
	}
}