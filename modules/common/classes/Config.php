<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Extension: Wrapper for configuration arrays. Multiple configuration readers can be
 * attached to allow loading configuration from files, database, etc.
 *
 * Configuration directives cascade across config sources in the same way that 
 * files cascade across the filesystem.
 *
 * Directives from sources high in the sources list will override ones from those
 * below them.
 *
 * @package   CMS/Common
 * @category  Configuration
 * @author    WinterSilence
 */
class Config extends Kohana_Config 
{
	// Array of config groups
	// protected $_groups = array();

	/**
	 * Load a configuration group. Searches all the config sources, merging all the 
	 * directives found into a single config group.  Any changes made to the config 
	 * in this group will be mirrored across all writable sources.  
	 *
	 *     $array = $config->load($name);
	 *
	 * See [Kohana_Config_Group] for more info
	 *
	 * @param   string  $group      Configuration group name
	 * @param   string  $path       Path to config file
	 * @param   bool    $load_once  Save group in Config->_groups ?
	 * @return  Kohana_Config_Group
	 * @throws  Kohana_Exception
	 */
	/*
	public function load($group, $path = NULL, $load_once = FALSE)
	{
		if ( ! count($this->_sources))
		{
			throw new Kohana_Exception('No configuration sources attached');
		}
		
		if (empty($group))
		{
			throw new Kohana_Exception("Need to specify a config group");
		}
		
		if ( ! is_string($group))
		{
			throw new Kohana_Exception("Config group must be a string");
		}
		
		if (strpos($group, '.') !== FALSE)
		{
			// Split the config group and path
			list($group, $path) = explode('.', $group, 2);
		}
		
		if (isset($this->_groups[$group]) AND is_null($path))
		{
			if (isset($path))
			{
				return Arr::path($this->_groups[$group], $path, NULL, '.');
			}
			return $this->_groups[$group];
		}
		
		$config = array();
		
		// We search from the "lowest" source and work our way up
		$sources = array_reverse($this->_sources);
		
		foreach ($sources as $source)
		{
			if ($source instanceof Kohana_Config_Reader)
			{
				if ($source_config = $source->load((is_string($path) ? $path : $group)))
				{
					$config = Arr::merge($config, $source_config);
				}
			}
		}
		
		if ($load_once)
		{
			$group = new Config_Group($this, $group, $config);
		}
		else
		{
			if (isset($this->_groups[$group]))
			{
				$config = Arr::merge($this->_groups[$group]->as_array(), $config);
			}
			$this->_groups[$group] = new Config_Group($this, $group, $config);
		}
		
		if (isset($path))
		{
			return Arr::path($config, $path, NULL, '.');
		}
		
		return $load_once ? $group : $this->_groups[$group];
	}
	*/

} // End Config