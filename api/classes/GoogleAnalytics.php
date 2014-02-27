<?php

/**
 * GoogleAnalytics class
 * 
 * This class handles the tracking of Google Analytics.
 *
 * For every API request, an event must be logged with Google to enable tracking. This includes catching of errors.
 *
 ** Table of Contents
 * 1. Variables
 * 2. __construct()
 */

// Include the Curl class (just in case)
require_once(__DIR__ .'/ooCurl.php');

class GoogleAnalytics
{
	const GA_URL = 'http://www.google-analytics.com/collect';

	/**
	 * Variables
	 */
	private $curl;
	private $fields;
	private $response;

	/**
	 * __construct()
	 *
	 * Initialises the class
	 *
	 * @access public
	 * @return void
	 */
	function __construct()
	{
		$this->curl = new Curl(self::GA_URL);
		$this->curl->proxy = 'proxysg.uwe.ac.uk:8080';

		$cid = 'fa056146-561d-41cd-8fcf-5dac78cb7f60';

		if(isset($_COOKIE["_ga"]))
		{
			$cid = $_COOKIE["_ga"];
		}

		$this->fields = array(
			'v' 	=> 1,
			'tid'	=> 'UA-23790873-4',
			'cid'	=> $cid,
			't'		=> 'event',
			'ec'	=> 'api-request',
			'dh'	=> 'www.cems.uwe.ac.uk',
			'dp' 	=> $_SERVER['REDIRECT_URL'],
		);
	}

	/**
	 * __set()
	 *
	 * A sneaky way of handling GA POST parameters
	 *
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	function __set($key, $value)
	{
		$this->fields[$key] = $value;
	}

	/**
	 * event()
	 *
	 * Creates a new event
	 *
	 * @access public
	 * @param $ea -> Event Action
	 * @param $el -> Event Label
	 * @param $ev -> Event Value
	 * @return void
	 */
	public function event($ea, $el = '')
	{
		$this->fields['ea'] = $ea;
		$this->fields['el'] = $el;

		$this->post();
	}

	/**
	 * post()
	 *
	 * Posts the event to Google
	 *
	 * @access public
	 * @return void
	 */
	public function post()
	{
		// Reinitialises the cURL
		$this->curl->init(self::GA_URL);

		$this->curl->post = count($this->fields);
		$this->curl->postfields = $this->fields_string();

		$this->response = $this->curl->exec();

		$this->curl->close();
	}

	/**
	 * fields_string()
	 *
	 * Takes all the fields, and combines them into a POSTable string
	 *
	 * Credit to {@link http://davidwalsh.name/curl-post} for a hand with this :)
	 *
	 * @access private
	 * @return string
	 */
	private function fields_string()
	{
		$string = '';

		foreach($this->fields as $key => $value)
		{
			$string .= $key .'='. $value .'&';
		}

		return rtrim($string, '&');
	}
}

