<?php
/**
 * Kohana CMS modifier: translate text
 * 
 * @package Smarty
 * @subpackage PluginsModifier
 */
function smarty_modifier_i18n($string, $lang = NULL)
{
	return I18n::get($string, $lang);
}