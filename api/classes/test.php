<?php 

/**
 * Test Script Class
 *
 * Handles the running of my test script
 */
class Test 
{
	private $request;
	private $base_url;
	private $url;
	private $headers;
	private $response_type;
	private $response;
	private $username;

	private $probe;

	private $buffer = '';

	/**
	 * status
	 *
	 * 0 -> success
	 * 1 -> warning
	 * 2 -> error
	 */
	private $status = 0;

	/**
	 * __construct()
	 *
	 * Takes a username and creates a testing environment
	 *
	 * @access public
	 * @param string $username
	 * @param string url
	 * @return void
	 */
	function __construct($username, $url)
	{
		$this->username = $username;
		$this->base_url = 'http://www.cems.uwe.ac.uk/~'+ $username +'/atwd/';
		$this->url = $url;
		$this->response_type = strstr($this->url, '.');

		$this->request_type();
		
		$this->headers = get_headers($this->url());
		$this->response = file_get_contents($this->url());
	}

	/**
	 * request_type()
	 *
	 * Determines the request type
	 * Creates a probe for the delete requests
	 *
	 * @access private
	 * @return void
	 */
	private function request_type()
	{
		// Determine the request type
		switch($url) 
		{
			case (preg_match('/^crimes\/([\-\d]+)\/(xml|json)$/', $url) ? true : false):
				$this->request = 'get_all';
				break;

			case (preg_match('/^crimes\/([\-\d]+)\/(\w+)\/(xml|json)$/', $url) ? true : false):
				$this->request = 'get_region';
				break;

			case (preg_match('/^crimes\/([\-\d]+)\/put\/(\w+):(\d+)\/(xml|json)$/', $url) ? true : false):
				$this->request = 'put';
				break;

			case (preg_match('/^crimes\/([\-\d]+)\/post\/(\w+)\/(\w+)\/([\w\-\:]+)\/(xml|json)$/', $url) ? true : false):
				$this->request = 'post';
				break;

			case (preg_match('/^crimes\/([\-\d]+)\/delete\/(\w+)\/(xml|json)$/', $url) ? true : false):
				$this->request = 'delete';
				break;
		}

		// Create a probe for the POST requests :p
		if($this->request === 'post')
		{
			$this->probe = new Test($this->username, 'crimes/6-2013/delete/wessex/xml');
		}

		// Create a probe for the DELETE requests :p
		elseif($this->request === 'delete')
		{
			$this->probe = new Test($this->username, 'crimes/6-2013/post/south_west/wessex/hom:4-vwi:15-vwoi:25/xml');
		}

	}

	/**
	 * url()
	 *
	 * Gets the fully qualified URL
	 *
	 * @access public
	 * @return string
	 */
	public function url()
	{
		return $this->base_url . $this->url;
	}

	/**
	 * get_headers()
	 *
	 * Fetches the contents again
	 *
	 * @access public
	 * @return void
	 */
	public function get_headers()
	{
		$this->headers = get_headers($this->url()); 
	}

	/**
	 * get_contents()
	 *
	 * Fetches the contents again
	 *
	 * @access public
	 * @return void
	 */
	public function get_contents()
	{
		$this->response = file_get_contents($this->url()); 
	}

	/**
	 * probe()
	 *
	 * Launches the probe
	 *
	 * @access public
	 * @return void
	 */
	private function probe()
	{
		$this->probe->get_contents();
	}

	/**
	 * status_code()
	 *
	 * Determines the status code of the response
	 *
	 * @access private
	 * @return int
	 */
	private function status_code()
	{
		return $this->headers[0];
	}

	/**
	 * run()
	 *
	 * Runs the full test
	 *
	 * @access public
	 * @return text/html
	 */
	public function run()
	{
		// Check if we got a status code that matches
		if($this->status_code() == 200 && !empty($this->response))
		{
			$this->buffer .= '<strong class="success">Found:</strong> <span class="url"><a href="'. $this->url() .'">'. $this->url() .'</a></span> (HTTP 200 OK)<br />';

			if($this->response_type == $this->headers['Content-Type'])
			{
				$this->buffer .= '<strong class="success">Content-type validated</strong> as '. $this->response_type .'<br />';

				// Load the contents into a string
				$dom = new DOMDocument;
			
				if($dom->loadXML($this->response))
				{
					$this->buffer .= '<strong class="success">Structure validated</strong> as XML<br />';

					// Validate the response
					if($dom->schemaValidate(BASEDIR .'test/xsd/'. $this->request .'.xsd'))
					{
						$this->buffer .= '<strong class="success">Content validated</strong> against the <a href="./xsd/'. $this->request .'.xsd">XSD Schema</a><br />';
					}
					else
					{
						$this->status(1);
						$this->buffer .= '<strong class="warning">Content failed to validate</strong> against the <a href="./xsd/'. $this->request .'.xsd">XSD Schema</a></br />';
					}

				}
				else
				{
					$this->status(2);
					$this->buffer .= '<strong class="error">Structure invalid:</strong> expecting well-formed XML<br />';
				}	
			}
			else
			{	
				$this->status(1);
				$this->buffer .= '<strong class="warning">Content-type invalid:</strong> received "'. $this->headers['Content-Type'] .'", expected "'. $this->response_type .'"<br />';
			}

			return '<p class="'. $this->status() .'">'. $this->buffer .'</p>';
		}
		else
		{
			switch($this->status_code())
			{
				case 404:
					return '<p class="error"><strong class="error">Unable to find document:</strong> <span class="url"><a href="'. $this->url() .'">'. $this->url() .'</a></url> (HTTP 404 Not Found)</p>';
					break;

				case 500:
					return '<p class="error"><strong class="error">Service Error:</strong> <span class="url"><a href="'. $this->url() .'">'. $this->url() .'</a></span> (HTTP 500 Internal Server Error)</p>';
					break;

				case 501:
					return '<p class="error"><strong class="error">URL pattern not recognized:</strong> <span class="url"><a href="'. $this->url() .'">'. $this->url() .'</a></span> (HTTP 501 Not Implemented)</p>';
					break;
			}
		}
	}

	/**
	 * status()
	 *
	 * Sets the buffer status
	 *
	 * @access private
	 * @param int $level
	 * @return string
	 */
	private function status($level = 0)
	{
		if($this->status < $level)
		{
			$this->status = $level;
		}

		switch($this->status)
		{
			case 0:
			default:
				return 'success';
				break;
			case 1:
				return 'warning';
				break;
			case 2:
				return 'error';
				break;
		}
	}
}