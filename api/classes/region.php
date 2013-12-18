<?php namespace uwe\atwd;

class region {

	private $id;
	public $name;
	private $total_crime;
	private $total_fraud;
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
		$region = $region[0];

		$this->id = $slug;
		$this->name = (string) $region->name;
			
		$areas = $xml->xpath("//atwd:region[@id='$slug']/atwd:area");
		foreach($areas as $area)
		{
			$this->areas[(string) $area->attributes()['id']] = new area($area);
		}

		dump($this);

		// Query the database to find this region
	//	$db = new mysqli;
	//	$query = $db->query('SELECT ');
	}
}