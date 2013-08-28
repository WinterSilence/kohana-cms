<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * XML helper
 *
 * @package   CMS/Common
 * @category  Helpers
 * @author    WinterSilence
 */ 
abstract class CMS_XML
{
	/**
	 * Convert array in xml
	 */
	public function from_array(array $data, $xml = NULL)
	{
		if (is_null($xml))
		{
			$xml = simplexml_load_string('<'.key($data).'/>');
			$data = current($data);
			$return = TRUE;
		}
		
		if (is_array($data))
		{
			foreach ($data as $name => $value)
			{
				$this->from_array($value, is_numeric($name) ? $xml : $xml->addChild($name));
			}
		}
		else
		{
			$xml->{0} = $data;
		}
		
		if ( ! empty($return))
		{
			return $xml->asXML();
		}
	}

	/**
	 * Convert xml in array
	 */
	public function as_array($xml)
	{
		$tree = NULL;
		while ($xml->read())
		{
			switch ($xml->nodeType)
			{ 
				case XMLReader::END_ELEMENT: 
					return $tree; 
				case XMLReader::ELEMENT: 
					$node = array(
						'tag'   => $xml->name, 
						'value' => $xml->isEmptyElement ? '' : self::to_array($xml)
					); 
					if ($xml->hasAttributes) 
					{
						while ($xml->moveToNextAttribute())
						{
							$node['attributes'][$xml->name] = $xml->value;
						}
					}
					$tree[] = $node; 
				break; 
				case XMLReader::TEXT:
				case XMLReader::CDATA:
					$tree .= $xml->value; 
			}
		}
		return $tree; 
	}
}