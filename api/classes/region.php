<?php namespace uwe\atwd;

if(!defined('BASEDIR')) exit('No direct script access allowed');

class region {

	private $id;
	public $name;
	private $total_crime = 0;
	private $total_fraud = 0;
	private $areas = array();

	/**
	 * __construct()
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

		$region = $xml->xpath("//atwd:region[@id='$slug']");
		if(empty($region))
		{
			return;		
		}

		$region = $region[0];

		$this->id = $slug;
		$this->name = (string) $region->name;
			
		foreach($xml->xpath("//atwd:region[@id='$slug']/atwd:area") as $area)
		{
			$this->areas[(string) $area->attributes()['id']] = new area($area);
		}

		$iterator = new \RecursiveDirectoryIterator(BASEDIR.'data/custom/areas');
        foreach (new \RecursiveIteratorIterator($iterator) as $filename => $file) 
		{
			$xml = simplexml_load_file($file);
			dump($xml);
		}

		$this->_setTotalCrime();

		// Check for overrides on the region
		if(file_exists(BASEDIR .'data/custom/regions/'. $this->id .'.xml'))
		{
			$xml = simplexml_load_file(BASEDIR .'data/custom/regions/'. $this->id .'.xml');
			$xml->registerXPathNamespace('atwd', 'http://www.cems.uwe.ac.uk/assignments/10008548/atwd/');

			dump($xml);
		}
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
		foreach($this->areas as $key => $area)
		{
			$this->total_crime += $area->getTotalCrime(false);
			$this->total_fraud += $area->getTotalFraud();
		}
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
}