<?php namespace uwe\atwd;

if(!defined('BASEDIR')) exit('No direct script access allowed');

/**
 * 'uwe\atwd\SimpleXMLIterator' class
 *
 * This class simply iterates over a SimpleXMLElement
 */
class SimpleXMLIterator 
{
	private static $xml = array();

	/**
	 * iterate()
	 *
	 * @access public
	 * @static true
	 * @param \SimpleXMLElement $xml
	 * @return nested array
	 */
	public static function iterate(\SimpleXMLElement $xml, $fresh = true)
	{
		if($fresh)
		{
			self::$xml = array();
		}

		foreach($xml->children() as $name => $node)
		{
			if($node->children())
			{
				self::$xml[$node->getName()] = self::iterate($node, false);
			}
			else
			{
				self::$xml[$node->getName()] = (string) $node;
			}
		}

		return self::$xml;
	}
}