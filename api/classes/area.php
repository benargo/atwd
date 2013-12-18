<?php namespace uwe\atwd;

class area {

	private $id;
	public $name;
	private $total_crime;
	private $total_fraud;
	private $crime = array();


	/**
	 * __construct()
	 * 
	 * @access public
	 * @param SimpleXMLElement $xml
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

}