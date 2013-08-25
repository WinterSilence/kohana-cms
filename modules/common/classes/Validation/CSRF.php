<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Validation with CSRF rules
 *
 * @package    Kohana
 * @category   Security
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Validation_CSRF extends Validation
{
	/**
	 * Creates a new Validation instance 
	 *
	 * @param   array   $array  array to use for validation
	 * @return  Validation
	 */
	public static function factory(array $array)
	{
		return Validation::factory($array)
			->rules('csrf', array(array('not_empty'), array('Security::check')))
			->label('csrf', 'Cross-site request forgery');
	}
	
} // End Validation_CSRF
