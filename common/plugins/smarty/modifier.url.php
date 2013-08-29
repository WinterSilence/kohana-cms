<?php
/**
 * Kohana CMS modifier: generates URL's, using Routes
 * 
 * @package Smarty
 * @subpackage PluginsModifier
 */
function smarty_modifier_url($params_str, $escape_html = TRUE, $lower_case = TRUE, $protocol = NULL)
{
	return CMS::url($params_str, $escape_html, $lower_case, $protocol);
}