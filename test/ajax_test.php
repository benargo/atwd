<?php
	error_reporting(0);

	// Shorthand :)
	$url =  $_GET['url'];
	
	// Explode the URL
	$url = preg_replace('/^http:\/\/www.cems.uwe.ac.uk\/~([\w\-]+)\/atwd\//', '', $url);
	$url_array = explode('/', $url);

	header('Content-type: application/json');
	$response = array();

	// Determine the request type
	switch($url) 
	{
		case (preg_match('/^crimes\/([\-\d]+)\/(xml|json)$/', $url) ? true : false):
			$request = 'get_all';
			break;

		case (preg_match('/^crimes\/([\-\d]+)\/(\w+)\/(xml|json)$/', $url) ? true : false):
			$request = 'get_region';
			break;

		case (preg_match('/^crimes\/([\-\d]+)\/put\/(\w+):(\d+)\/(xml|json)$/', $url) ? true : false):
			$request = 'put';
			break;

		case (preg_match('/^crimes\/([\-\d]+)\/post\/(\w+)\/(\w+)\/([\w\-\:]+)\/(xml|json)$/', $url) ? true : false):
			$request = 'post';
			break;

		case (preg_match('/^crimes\/([\-\d]+)\/delete\/(\w+)\/(xml|json)$/', $url) ? true : false):
			$request = 'delete';
			break;
	}

	// Create a probe for the delete requests :p
	if($request === 'delete')
	{
		$probe_url = preg_replace('/crimes\/([\-\d]+)\/delete\/(\w+)\/(xml|json)$/', 'crimes/6-2013/post/south_west/wessex/hom:4-vwi:15-vwoi:25/xml', $_GET['url']);
		get_headers($probe_url);
	}

	$headers = get_headers($_GET['url'], 1);

	if(strstr($headers[0], '200'))
	{
		// Probe the delete request
		if($request === 'delete')
		{
			get_headers($probe_url);
		}

		$api = file_get_contents($_GET['url']);

		$response['expected_type'] = array_pop($url_array);
		$response['request'] = $request;

		switch($response['expected_type'])
		{
			case 'xml':

				// Check the errors first of all
				$xml = simplexml_load_string($api);
				
				if($xml->error)
				{
					http_response_code($xml->error['code']);
					unset($response);
					$response = array();
					$response['error'] = 'HTTP/1.1 '. $xml->error['code'] .' '. $xml->error['desc'];
					break;
				}
				
				$response['file_loaded'] = true;
				$response['expected_type'] = 'text/xml';
				$response['response_type'] = $headers['Content-Type'];
			
				// Load the contents into a string
				$dom = new DOMDocument;
				if($dom->loadXML($api))
				{
					$response['valid'] = true;

					// Validate the response
					if($dom->schemaValidate(__DIR__.'/xsd/'. $request .'.xsd'))
					{
						$response['schema_validated'] = true;
					}
					else
					{
						$response['schema_validated'] = false;
					}
				}
				else
				{
					$response['valid'] = false;
				}

				break;

			case 'json':

				$response['file_loaded'] = true;
				$response['expected_type'] = 'application/json';
				$response['response_type'] = $headers['Content-Type'];

				// Check if the JSON is valid
				if(json_decode($api))
				{
					$response['valid'] = true;
				}
				else
				{
					$response['valid'] = false;
				}

				// Can't validate JSON, no right answer

				break;

			default:

				$response['file_loaded'] = true;
				$response['valid'] = false;
		}
	}
	else
	{
		header($headers[0]);
		$response['error'] = $headers[0];
	}

	echo json_encode($response);

?>