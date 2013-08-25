<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * JS and CSS/LESS asset management
 * Compile and serve js, css, and less files
 *
 * @package    Kohana/Assets
 * @author     Ben Midget    https://github.com/bmidget
 * @author     WinterSilence https://github.com/WinterSilence
 * @copyright  (c) 2011-2013 Kohana Team
 * @license    http://kohanaframework.org/license
 */
abstract class Assets_Core
{
	// @var string Default asset name
	public static $default = 'default';

	// @var array Instances assets 
	protected static $_instances = array();

	// @var string Current asset
	protected $_name = NULL;

	// @var array Asset directories
	protected $_dirs = array(
		// Where to save compiled css and js files
		'compile' => array(
			'css' => 'assets/css/compile/',
			'js'  => 'assets/js/compile/',
		),
		// Where to get source css and js files
		'source' => array(
			'css' => 'assets/css/',
			'js'  => 'assets/js/',
		),
		// LESS default directories to import from
		'less' => array(),
	);

	/**
	 * Arrays containing all the added paths for js and css/less
	 * that will be compiled together
	 *
	 * @var mixed
	 * @access protected
	 */
	protected $_paths = array(
		'css' => array(),
		'js'  => array(),
	);

	// Protection from creating clone
	private function __clone(){}

   // Protection from creating unserialize
	private function __wakeup(){}

	// Protection from creating new Assets
	protected function __construct($name)
	{
		$this->_name = $name;
		
		if ($config = Kohana::$config->load('assets')->get($name))
		{
			$this->_dirs = Arr::merge($this->_dirs, $config);
		}
		else
		{
			throw new Kohana_Exception('Configuration assets.:name not found', array(':name' => $name));
		}
	}

	// Asset group instance
	public static function instance($group = NULL)
	{
		if (is_null($group))
		{
			// Use the default group name
			$group = self::$default;
		}
		
		if ( ! isset(self::$_instances[$group]))
		{
			self::$_instances[$group] = new Assets($group);
		}
		
		return self::$_instances[$group];
	}

	/**
	 * Get tag for ether 'css' or 'js'
	 *
	 * @param string $type
	 * @param string $group
	 * @return string
	 */
	public static function get($type, $group = NULL)
	{
		$assets = Assets::instance($group);
		
		if ($hash = $assets->_make_hash($type))
		{
			$compile_path = $assets->_get_compile_path($type, $hash);
			
			if (file_exists($compile_path))
			{
				return $assets->_get_tag($type, $hash);
			}
			
			$contents = '';
			foreach ($assets->_paths[$type] as $path)
			{
				$contents .= file_get_contents($assets->_get_file_location($type, $path));
			}
			
			$assets->_remove_files($type, $hash)
				   ->_write_file($compile_path, $assets->{'_compile_'.$type}($contents));
			
			return $assets->_get_tag($type, $hash);
		}
	}

	/**
	 * Gets or sets import directories for LESS compiling
	 *
	 * @access public
	 * @param mixed $path
	 * @return Assets
	 */
	public function less_dirs($path = NULL)
	{
		if ($path)
		{
			$this->_dirs['less'] = array_merge($this->_dirs['less'], array($path));
			return $this;
		}
		return $this->_dirs['less'];
	}

	/**
	 * Get the asset object name
	 *
	 * @access public
	 * @param mixed $name
	 * @return Assets
	 */
	public function name()
	{
		return $this->_name;
	}

	/**
	 * Add path or an array of paths for compiling
	 *
	 * @access protected
	 * @param string $type
	 * @param mixed  $key
	 * @param mixed  $path
	 * @return Assets
	 */
	public function set($type, $key, $path = NULL)
	{
		if (is_array($key))
		{
			$this->_paths[$type] = Arr::merge($this->_paths[$type], $key);
		}
		else
		{
			$this->_paths[$type][$key] = $path;
		}
		return $this;
	}

	/**
	 * Remove a specific css or js path or array of paths from added paths
	 *
	 * @access public
	 * @param mixed $type
	 * @param mixed $key
	 * @return Assets
	 */
	public function remove($type, $key = NULL)
	{
		if (is_array($type))
		{
			$this->_paths = array_diff_assoc($this->_paths, $type);
		}
		else
		{
			unset($this->_paths[$type][$key]);
		}
		return $this;
	}
	
	/**
	 * Compile into a single string using PHPLess and CssMin
	 *
	 * @access protected
	 * @param mixed $contents
	 * @return string
	 */
	protected function _compile_css($contents)
	{
		$lessc = new lessc;
		if ( ! empty($this->_dirs['less']))
		{
			$lessc->importDir = $this->_dirs['less'];
		}
		$contents = $lessc->parse($contents);
		
		return CssMin::minify($contents);
	}

	/**
	 * Compile JS into a single string using JSMin
	 *
	 * @access protected
	 * @param  mixed $contents
	 * @return string
	 */
	protected function _compile_js($contents)
	{
		return JSMin::minify($contents);
	}

	/**
	 * Get the path to compile to
	 *
	 * @access protected
	 * @param  string $type
	 * @param  string $hash
	 * @return string
	 */
	protected function _get_compile_path($type, $hash)
	{
		return $this->_dirs['compile'][$type].$hash.'.'.$type;
	}

	/**
	 * Retrieve the file location for a pre-compiled js/css/less asset
	 *
	 * @access protected
	 * @param  mixed $type
	 * @param  mixed $path
	 * @return string
	 */
	protected function _get_file_location($type, $path)
	{
		if (preg_match('/^(\/|http|https)|([A-Z]:\\\\)/iU', $path))
		{
			return $path;
		}
		elseif ($this->_dirs['source'][$type] instanceof Closure)
		{
			return $this->_dirs['source'][$type]($path);
		}
		return $this->_dirs['source'][$type].$path;
	}

	/**
	 * Retrieve a namespace from the compiled file
	 * This namespace is the prefix before the '_' in the compiled file name:
	 * ex: 627a74de1fc5a2e750e85a2995a5ac6a332e5018_70e79492f9153932dea60473.js
	 *
	 * @access protected
	 * @param mixed $type
	 * @return string
	 */
	protected function _get_file_namespace($type)
	{
		return md5($this->_name.implode($this->_paths[$type]));
	}

	/**
	 * Build and return the style or script HTML tag
	 *
	 * @access protected
	 * @param mixed $type
	 * @param mixed $hash
	 * @return string
	 */
	protected function _get_tag($type, $hash)
	{
		if ($type == 'css')
		{
			return HTML::style($this->_get_compile_path($type, $hash));
		}
		elseif ($type == 'js')
		{
			return HTML::script($this->_get_compile_path($type, $hash));
		}
	}

	/**
	 * Make a hash that is the represents a namespace for 
	 * all the assets included in the compiled file
	 * and a last edited date of those files.
	 * The format for this hash is {namespace}_{edited}.ext
	 * ex: 627a74de1fc5a2e750e85a2995a5ac6a332e5018_70e79492f915393.js
	 *
	 * @access protected
	 * @param mixed $type
	 * @return string
	 */
	protected function _make_hash($type)
	{
		$str = '';
		foreach ($this->_paths[$type] as $path)
		{
			$str .= filemtime($this->_get_file_location($type, $path));
		}
		if ( ! empty($str))
		{
			return $this->_get_file_namespace($type).'_'.md5($str);
		}
	}

	/**
	 * Remove files from he compiled directory for the same set of assets 
	 * that are outdated do to edits
	 *
	 * @access protected
	 * @param mixed $type
	 * @param mixed $hash
	 * @return void
	 */
	protected function _remove_files($type, $hash)
	{
		$base = $this->_dirs['compile'][$type];
		$files = glob($base.$this->_get_file_namespace($type).'_*.'.$type);
		foreach ($files as $file)
		{
			@unlink($file);
		}
		return $this;
	}

	/**
	 * Write the compiled string to the compiled directory
	 *
	 * @access protected
	 * @param mixed $path
	 * @param mixed $contents
	 * @return void
	 */
	protected function _write_file($path, $contents)
	{
		file_put_contents($path, $contents, LOCK_EX);
		return $this;
	}

} // End Assets
