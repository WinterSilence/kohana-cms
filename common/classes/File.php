<?php defined('SYSPATH') OR die('No direct script access.');

class File extends Kohana_File
{
	/**
	 * Create a directory specified in the path
	 * 
	 * @param   string   $path  Path to directory
	 * @param   integer  $mode  Directory permissions
	 * @return  bool
	 */
	public static function mkdir($path, $mode = 0755)
	{
		return (is_dir($path) ? TRUE : mkdir($path, $mode, TRUE));
	}
	
	/**
	 * Save varible in file
	 *
	 * @param   string  $var           Varible
	 * @param   string  $file          Path to file
	 * @param   bool    $delete_empty  Delete empty fields (for arrays)
	 * @return  bool
	 */
	public static function var_export($var, $file, $delete_empty = FALSE)
	{
		// Create path directories if no exists
		if( ! File::mkdir(dirname($file)))
		{
			return FALSE;
		}
		// Write in file
		if ( ! $h = fopen($file, 'w+'))
		{
			return FALSE;
		}
		// Block access to the file
		if (flock($h, LOCK_EX))
		{
			// Delete empty elements from varible
			if ($delete_empty AND is_array($var))
			{
				foreach ($var as $key => $value)
				{
					$var[$key] = array_filter($value);
				}
			}
			// File content 
			$content = Kohana::FILE_SECURITY.PHP_EOL.'return '.var_export($var, TRUE).';';
			// Modifiers for adjusting appearance
			$replace = array(
				"=> \n"    => '=>',
				'array ('  => 'array(',
				'  '       => '	',
				' false,'  => ' FALSE,',
				' true,'   => ' TRUE,',
				' null,'   => ' NULL,'
			);
			$content = strtr($content, $replace);
			// Write var content
			$result = fwrite($h, $content);
			flock($h, LOCK_UN);
		}
		fclose($h);
		
		return (bool) $result;
	}

} // End File