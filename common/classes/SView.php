<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * View use Smarty
 *
 * @package   CMS/Common
 * @category  View
 * @author    WinterSilence
 */
class SView extends Kohana_View
{
	/**
	 * Template engine Smarty
	 * Smarty object
	 */
	protected static $_smarty;

	/**
	 * View file extension
	 */
	protected static $_extension = 'tpl';

	/**
	 * Returns a new View object. If you do not define the "file" parameter,
	 * you must call [View::set_filename].
	 *
	 *     $view = View::factory($file);
	 *
	 * @param   string  $file   view filename
	 * @param   array   $data   array of values
	 * @return  View
	 */
	public static function factory($file = NULL, array $data = NULL)
	{
		return new SView($file, $data);
	}

	/**
	 * Instance Smarty object
	 *
	 * @return  Smarty
	 */
	public static function smarty()
	{
		if ( ! self::$_smarty)
		{
			// Конфигурация
			if ($config = Kohana::$config->load('smarty')->as_array())
			{
				self::extension($config['extension']);
			};
			// Создание объекта
			$smarty = (isset($config['class']) ? new $config['class'] : new Smarty);
			// Конфигурирование объекта
			foreach ($config as $key => $value)
			{
				$method = Inflector::camelize('set '.$key);
				if (method_exists($smarty, $method))
				{
					$smarty->$method($value);
				}
				elseif (isset($smarty->$key))
				{
					$smarty->$key = $value;
				}
			}
			self::$_smarty = $smarty;
		}
		return self::$_smarty;
	}

	/**
	 * Captures the output that is generated when a view is included.
	 * The view data will be extracted to make local variables. This method
	 * is static to prevent object scope resolution.
	 *
	 *     $output = Smarty_View::capture($file, $data);
	 *
	 * @param   string  $kohana_view_filename   filename
	 * @param   array   $kohana_view_data       variables
	 * @return  string
	 */
	protected static function capture($view_filename, array $view_data)
	{
		return self::smarty()->clearAllAssign()
							 ->assign(Arr::merge(self::$_global_data, $view_data))
							 ->fetch($view_filename);
	}

	/**
	 * Sets the view filename.
	 *
	 *     $view->set_filename($file);
	 *
	 * @param   string  $file   view filename
	 * @return  $this
	 * @throws  View_Exception
	 */
	public function set_filename($file)
	{
		self::smarty();
		if ( ! $path = Kohana::find_file('views', $file, self::extension()))
		{
			throw new View_Exception('The requested view :file.:ext could not be found', array(
				':file' => $file,
				':ext'  => self::extension(),
			));
		}
		// Store the file path locally
		$this->_file = $path;
		return $this;
	}

	/**
	 * Gets or sets View filename.
	 *
	 * @param   string  $file   View filename
	 * @return  mixed
	 */
	public function file($file = NULL)
	{
		if ($file)
		{
			// Setter
			return $this->set_filename($file);
		}
		// Getter
		return $this->_file;
	}

	/**
	 * Gets or sets View file extension
	 *
	 * @param   string  $extension  View filename extension
	 * @return  mixed
	 */
	public static function extension($extension = NULL)
	{
		if ($extension)
		{
			// Setter
			self::$_extension = $extension;
		}
		// Getter
		return self::$_extension;
	}

	/**
	 * Renders the view object to a string. Global and local data are merged
	 * and extracted to create local variables within the view file.
	 *
	 *     $output = $view->render();
	 *
	 * [!!] Global variables with the same key name as local variables will be
	 * overwritten by the local variable.
	 *
	 * @param   string  $file   view filename
	 * @return  string
	 * @throws  View_Exception
	 * @uses    View::capture
	 */
	public function render($file = NULL)
	{
		if ($file !== NULL)
		{
			$this->set_filename($file);
		}
		
		if (empty($this->_file))
		{
			throw new View_Exception('You must set the file to use within your view before rendering');
		}
		
		// Combine local and global data and capture the output
		return self::capture($this->_file, $this->_data);
	}

} // End SView
