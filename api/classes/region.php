<?php namespace uwe\atwd;

if(!defined('BASEDIR')) exit('No direct script access allowed');

/**
 * 'uwe\atwd\region' class
 * 
 * This class holds an instance of a specific Region.
 * A Region contains multiple Areas within it, the Areas are instances of 'uwe\atwd\area' class
 *
 ** Table of Contents
 * 1. Variables
 * 2.  __construct()
 * 3.  ::get()
 * 4.  getTotalCrime()
 * 5.  _setTotalCrime()
 * 6.  getTotalFraud()
 * 7.  getTotalEngland()
 * 8.  getTotalEnglandAndWales()
 * 9.  putTotalCrime()
 * 10. postArea()
 * 11. deleteArea()
 * 12. last_modified_time()
 */
class region 
{
	public $id;
	public $name;
	private $total_crime = 0;
	private $total_fraud = 0;
	private $areas = array();

	private static $last_modified_time = 0;

	/**
	 * __construct()
	 * 
	 * Initialises the class defined by the snake_case slug we feed it.
	 *
	 * @access public
	 * @param string $slug
	 * @return void
	 */
	function __construct($slug)
	{
		// Load the region in from the XML
		$xml = simplexml_load_file(BASEDIR .'data/recorded_crime.xml');
		$xml->registerXPathNamespace('atwd', 'http://www.cems.uwe.ac.uk/assignments/10008548/atwd/');

		if(filemtime(BASEDIR .'data/recorded_crime.xml') > self::$last_modified_time)
		{
			self::$last_modified_time = filemtime(BASEDIR .'data/recorded_crime.xml');
		}

		$region = $xml->xpath("//atwd:region[@id='$slug']");
		
		// No region found?
		if(empty($region))
		{
			return;
		}

		$region = $region[0];

		$this->id = $slug;
		$this->name = (string) $region->name;

		// Loop through each of the areas
		foreach($xml->xpath("//atwd:region[@id='$slug']/atwd:area") as $area)
		{
			$this->areas[(string) $area->attributes()['id']] = new area($area);
		}

		$iterator = new \RecursiveDirectoryIterator(BASEDIR.'data/custom/areas');
        foreach (new \RecursiveIteratorIterator($iterator) as $filename => $file) 
		{
			if(in_array($this->id, explode('/', str_replace(BASEDIR, '', $filename))))
			{
				$xml = simplexml_load_file($filename);
				$area = new area($xml->region->area, $this->id);
				$this->areas[$area->id] = $area;

				if(filemtime($filename) > $this->last_modified_time)
				{
					self::$last_modified_time = filemtime($filename);
				}
			}
		}

		$this->_setTotalCrime();
	}

	/**
	 * get()
	 * 
	 * Gets the specified region, or all of them if specified
	 *
	 * @access public
	 * @static true
	 * @param string $region
	 * @return mixed
	 */
	public static function get($region = 'all')
	{
		if($region == 'all')
		{
			$xml = simplexml_load_file(BASEDIR .'data/recorded_crime.xml');
			$xml->registerXPathNamespace('atwd', 'http://www.cems.uwe.ac.uk/assignments/10008548/atwd/');

			$return = array();
			
			foreach($xml->xpath("//atwd:region") as $region)
			{
				$return[(string) $region['id']] = new region((string) $region['id']);
			}
		}
		else
		{
			$return = new region($region);
			
			if(empty($return->name))
			{
				$return = false;
			}
		}
		return $return;
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
	 * _setTotalCrime()
	 *
	 * Sets the total crime stats by looping through each of the areas
	 *
	 * @access private
	 * @return void
	 */
	private function _setTotalCrime()
	{
		// Check for overrides on the region
        if(file_exists(BASEDIR .'data/custom/regions/'. $this->id .'.xml'))
        {
            $xml = simplexml_load_file(BASEDIR .'data/custom/regions/'. $this->id .'.xml');
            $this->total_crime = $xml->region->total_recorded_crime->including_fraud - $this->total_fraud;

            if(filemtime(BASEDIR .'data/custom/regions/'. $this->id .'.xml') > $this->last_modified_time)
            {
            	self::$last_modified_time = filemtime(BASEDIR .'data/custom/regions/'. $this->id .'.xml');
            }
        }
        else
        {
        	foreach($this->areas as $key => $area)
			{
				$this->total_crime += $area->getTotalCrime(false);
				$this->total_fraud += $area->getTotalFraud();
			}
		}
	}

	/**
	 * getTotalFraud()
	 *
	 * Gets the total number of fraud offenses
	 *
	 * @access public
	 * @return int
	 */
	public function getTotalFraud()
	{
		return $this->total_fraud;
	}

	/**
	 * getAreas()
	 *
	 * @access public
	 * @param mixed $which
	 * @return array
	 */
	public function getAreas($which = NULL)
	{
		if(is_array($which))
		{
			return array_intersect_key($this->areas, $which);
		}
		elseif(is_string($which) && array_key_exists($which, $this->areas))
		{
			return $this->areas[$which];
		}
		else
		{
			return $this->areas;
		}
	}

	/**
	 * getTotalEngland()
	 *
	 * Gets the total crime figure for all of England
	 *
	 * @access public
	 * @static true
	 * @param bool $fraud - If true, returns the total including fraud
	 * @return int $total
	 */
	public static function getTotalEngland($fraud = false)
	{
		$total = 0;
		
		foreach(self::get() as $region_id => $region)
		{
			if($region_id == 'wales')
			{
				continue;
			}
			$total += $region->getTotalCrime($fraud);
		}

		return $total;
	}

	/**
	 * getTotalEnglandAndWales()
	 *
	 * Gets the total crime figure for all of England AND Wales
	 *
	 * @access public
	 * @static true
	 * @param bool $fraud - If true, returns the total including fraud
	 * @return int $total
	 */
	public static function getTotalEnglandAndWales($fraud = false)
	{
		$total = 0;
		
		foreach(self::get() as $region_id => $region)
		{
			$total += $region->getTotalCrime($fraud);
		}

		return $total;
	}

	/**
	 * CRUD OPERATIONS
	 */

	/** 
	 * putTotalCrime()
	 *
	 * Updates the total crime MINUS ANY FRAUD (this figure stays the same) using HTTP PUT
	 *
	 * @access public
	 * @param int $new_total
	 * @return void
	 */
	public function putTotalCrime($new_total)
	{
		// Create the XML to save
		if(file_exists(BASEDIR .'data/custom/regions/'. $this->id .'.xml'))
		{
			$xml = simplexml_load_file(BASEDIR .'data/custom/regions/'. $this->id .'.xml');
		}
		else
		{
			$dom = new \DOMDocument;
			$dom->formatOutput = true;
			$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/~b2-argo/atwd/', 'custom_data');
			$node->setAttribute('timestamp', time());
			$dom_custom_data = $dom->appendChild($node);

			$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/~b2-argo/atwd/', 'region');
			$node->setAttribute('id', $this->id);
			$dom_region = $dom_custom_data->appendChild($node);

			$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/~b2-argo/atwd/', 'name', $this->name);
			$dom_region->appendChild($node);

			$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/~b2-argo/atwd/', 'total_recorded_crime');
			$dom_total = $dom_region->appendChild($node);

			$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/~b2-argo/atwd/', 'including_fraud', $this->getTotalCrime(true));
			$dom_total->appendChild($node);

			$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/~b2-argo/atwd/', 'excluding_fraud', $this->getTotalCrime(false));
			$dom_total->appendChild($node);

			$xml = simplexml_import_dom($dom);
		}

		$this->total_crime = $new_total;
		$xml->region->total_recorded_crime->including_fraud = (int) $new_total;
		$xml->region->total_recorded_crime->excluding_fraud = (int) $new_total - $this->getTotalFraud();

		file_put_contents(BASEDIR .'data/custom/regions/'. $this->id .'.xml', $xml->asXML());
	}

	/**
	 * postArea()
	 * 
	 * POSTs a new area to the end of the current list of areas
	 *
	 * @access public
	 * @param uwe\atwd\area object
	 * @return bool
	 */
	public function postArea(area $area)
	{
		$total_crime = $this->total_crime + $area->getTotalCrime(false);
		
		if(array_key_exists($area->id, $this->areas))
		{
			$total_crime -= $this->areas[$area->id]->getTotalCrime(false);
		}
		
		$this->areas[$area->id] = $area;
		$this->putTotalCrime($total_crime);

	}

	/**
	 * deleteArea()
	 *
	 * Deletes an area from this object (does not remove it from the Database)
	 * Then resets the total crime for this object
	 *
	 * @access public
	 * @param string $area_name
	 * @return void
	 */
	public function deleteArea($area_name)
	{
		$this->putTotalCrime($this->total_crime - $this->areas[$area_name]->getTotalCrime(false));
		unset($this->areas[$area_name]);
	}

	/**
	 * last_modified_time()
	 *
	 * Generates the last modified time of the data
	 *
	 * @access public
	 * @return (int) unix time
	 */
	public static function last_modified_time()
	{
		return self::$last_modified_time;
	}
}

