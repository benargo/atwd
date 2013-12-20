<?php namespace uwe\atwd;

if(!defined('BASEDIR')) exit('No direct script access allowed');

class response {

	private $iterations = array()	;

	/**
	 * __construct()
	 *
	 * @access public
	 * @return void
	 */
	function __construct()
	{

	}

	/**
	 * asXML()
	 *
	 * Returns the response object coded as XML
	 *
	 * @access public
	 * @return SimpleXMLElement $xml
	 */
	public function asXML()
	{
		$dom = new DOMDocument;
		$dom->formatOutput = true;

		foreach($this->iterate($this) as $key => $value)
		{
			$node = $dom->createElement()
		}
	}

	/**
	 * iterate()
	 *
	 * Iterate through a this object
	 * 
	 * @access private
	 * @param object $obj
	 * @param bool $fresh
	 * @return array $array
	 */
	private function iterate($obj, $fresh = true)
	{
		if($fresh)
		{
			$this->iterations = array();
		}

		foreach($obj as $key => $value)
		{
			if(is_object($value))
			{
				$this->iterations[$key] = $this->iterate($value, false);
			}
			else
			{
				$this->iterations[$key] = $value;
			}
		}

		return $this->iterations;
	}
}