<?php namespace uwe\atwd;

class region {

	protected $id;
	public $name;
	private $slug;
	private $total_crime;
	private $total_fraud;
	private $areas;

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
		$region = $xml->xpath("/crimes/region[@id='$slug']");

		dump($region[0]);

		// Query the database to find this region
	//	$db = new mysqli;
	//	$query = $db->query('SELECT ');
	}
}