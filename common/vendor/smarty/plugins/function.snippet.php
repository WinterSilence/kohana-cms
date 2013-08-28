<?php
/**
 * Kohana CMS snippet plugin
 * 
 * @package Smarty
 * @subpackage PluginsFunction
 */
function smarty_function_snippet(array $params = NULL)
{
	if (isset($params['name']))
	{
		if ($snippet = CMS::snippet($params['name'], $params))
		{
			return EOL.$snippet.EOL;
		}
		else
		{
			throw new View_Exception('Snippet :name is not specified', array(':name', $params['name']));
		}
	}
	else
	{
		throw new View_Exception('Snippet name is not specified');
	}
}
