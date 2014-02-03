<?php namespace uwe\atwd;

if(!defined('BASEDIR')) exit('No direct script access allowed');

/**
 * 'uwe\atwd\area' class
 * 
 * This class holds an instance of a specific Area.
 * For example, Avon and Somerset Constabulary covers the 'Avon and Somerset' area.
 *
 * The area is loaded by passing in a SimpleXMLElement containing the XML for a single area,
 * this is pulled from the XML database file, and any custom data is loaded on top.
 *
 ** Table of Contents
 * 1. Variables
 * 2. __construct()
 * 3. getTotalCrime()
 * 4. getTotalFraud()
 * 5. iterate()
 */
class area 
{
	// Handler for Iterations 
	private static $iterations;

	// Area-specific variables
	public $id;
	public $name;
	private $total_crime;
	private $total_fraud;
	private $crime = array();

	/**
	 * __construct()
	 * 
	 * @access public
	 * @param \SimpleXMLElement $xml
	 * @param string $region;
	 * @return void
	 */
	function __construct(\SimpleXMLElement $xml)
	{
		$xml = simplexml_load_string($xml->asXML());
		$xml->registerXPathNamespace('atwd', 'http://www.cems.uwe.ac.uk/assignments/10008548/atwd/');

		$this->id = (string) $xml->attributes()['id'];
		$this->name = (string) $xml->name;
		$this->total_crime = (int) $xml->total_recorded_crime->excluding_fraud;
		//$this->total_crime = (int) $xml->xpath("//atwd:area[@id='". $this->id ."']/atwd:total_recorded_crime/atwd:including_fraud")[0];
		$this->total_fraud = (int) $xml->total_recorded_crime->including_fraud - (int) $xml->total_recorded_crime->excluding_fraud;

		// Go through all the crime
		foreach($xml->xpath("/area/victim_based|/area/other_crimes_against_society") as $crimes)
		{
			$this->crime[$crimes->getName()] = SimpleXMLIterator::iterate($crimes);
		}
	}

	/**
	 * getTotalCrime()
	 *
	 * Gets the total crime level, including or excluding fraud
	 *
	 * @access public
	 * @param bool $fraud
	 * @return int $total
	 */
	public function getTotalCrime($fraud = false)
	{
		$total = $this->total_crime;

		if($fraud)
		{
			$total += $this->total_fraud;
		}

		return $total;
	}

	/**
	 * getTotalFraud()
	 * 
	 * Gets the total number of fraud
	 *
	 * @access public
	 * @return int
	 */
	public function getTotalFraud()
	{
		return $this->total_fraud;
	}

	/**
	 * iterate()
	 *
	 * Iterate through all the crimes, returning each of the figures but stripping out the totals
	 *
	 * @access public
	 * @param key $start
	 * @return array ($key => $value)
	 */
	public function iterate($input = NULL, $fresh = true)
	{
		if($fresh)
		{
			self::$iterations = array();
		}

		if(!$input)
		{
			$input = $this->crime;
		}


		foreach($input as $key => $crime)
		{
			if(is_array($crime))
			{
				$this->iterate($crime, false);
			}
			else
			{
				self::$iterations[$key] = $crime;
			}
		}

		return self::$iterations;
	}

}