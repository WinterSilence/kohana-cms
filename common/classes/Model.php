<?php defined('SYSPATH') OR die('No direct script access.');

class Model extends Kohana_Model
{
	/**
	 * Create a new model instance.
	 *
	 *     $model = Model::factory($name);
	 *
	 * @param   string  $name    Model name
	 * @param   mixed   $config  Configurator
	 * @return  Model
	 */
	public static function factory($name, $config = NULL)
	{
		// Add the model prefix
		$class = 'Model_'.$name;
		if ((new ReflectionClass($class))->getMethod('factory')->getDeclaringClass()->name == $class)
		{
			return $class::factory($name, $config);
		}
		return new $class($config);
	}
}