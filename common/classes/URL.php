<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Extension URL helper class.
 *
 * @package    CMS/Common
 * @category   Helpers
 * @author     WinterSilence
 */
class URL extends Kohana_URL
{
	/**
	 * Fetches an absolute site URL based on a URI segment.
	 *
	 *     echo URL::site('foo/bar');
	 *
	 * @param   string  $uri        Site URI to convert
	 * @param   mixed   $protocol   Protocol string or [Request] class to use protocol from
	 * @param   boolean $index      Include the index_page in the URL
	 * @return  string
	 * @uses    URL::base
	 * @uses    UTF8::is_ascii
	 */
	public static function site($uri = '', $protocol = NULL, $index = TRUE)
	{
		// Chop off possible scheme, host, port, user and pass parts
		//$path = preg_replace('~^[-a-z0-9+.]++://[^/]++/?~', '', rtrim(ltrim($uri, '/')));
		$path = preg_replace('~^[-a-z0-9+.]++://[^/]++/?~', '', trim($uri, '/'));
		
		if ( ! UTF8::is_ascii($path))
		{
			// Encode all non-ASCII characters, as per RFC 1738
			$path = preg_replace_callback('~([^/]+)~', 'URL::_rawurlencode_callback', $path);
		}
		
		// Concat the URL
		return URL::base($protocol, $index).$path;
	}

	/**
	 * Generate slug string: transliterate cyrillic and delete special symbols
	 * @see http://en.wikipedia.org/wiki/Clean_URL#Slug
	 *
	 *    echo URL::slug('Бла бла'); // 'Bla_bla'
	 *
	 * @param   string  $string  Convert string
	 * @return  string
	 */
	public static function slug($string) 
	{
		$cyr = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 
		'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ж', 'з', 'и', 'й', 'к', 
		'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', ' ');
		$lat = array('A', 'B', 'V', 'G', 'D', 'E', 'J', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 
		'F', 'H', 'TS', 'CH', 'SH', 'SCH', '', 'YI', '', 'E', 'YU', 'YA', 'a', 'b', 'v', 'g', 'd', 'e', 'j', 'z', 'i', 'y', 'k', 
		'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sch', 'y', 'yi', '', 'e', 'yu', 'ya', '_');
		// Transliterate cyrillic
		$string = str_replace($cyr, $lat, $string);
		// Delete special symbols
		return preg_replace('#[^a-z0-9\-_]#i', '', $string);
	}

}