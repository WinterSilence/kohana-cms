<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Validation with CSRF + CAPTCHA rules
 *
 * @package    Kohana
 * @category   Security
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Validation_CSRF_Captcha extends Validation
{
	/**
	 * Creates a new Validation instance 
	 *
	 * @param   array   $array  array to use for validation
	 * @return  Validation
	 */
	public static function factory(array $array)
	{
		return Validation_CSRF::factory($array)
			->rules('captcha', array(array('not_empty'), array('Captcha::check')))
			->label('captcha', 'CAPTCHA');
	}
} // End Validation_CSRF_Captcha
