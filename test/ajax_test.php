<?php
	//require_once('../api/autoload.php');

	// Shorthand :)
	$url =  $_GET['url'];

	header('Content-type: application/json');
	$response = array();

	$headers = get_headers($url, 1);

	if(strstr($headers[0], '200'))
	{
		$api = file_get_contents($url);

		// Explode the URL
		$url = preg_replace('/^http:\/\/www.cems.uwe.ac.uk\/~([\w\-]+)\/atwd\//', '', $url);

		// Determine the request type
		switch($url) 
		{
			case (preg_match('/^crimes\/([\-\d]+)\/(xml|json)$/', $url)):
				$request = 'get_all';
				break;

			case (preg_match('/^crimes\/([\-\d]+)\/(\w+)\/(xml|json)$/', $url)):
				$request = 'get_region';
				break;

			case (preg_match('/^crimes\/([\-\d]+)\/put\/(\w+):(\d+)\/(xml|json)$/', $url)):
				$request = 'put';
				break;

			case (preg_match('/^crimes\/([\-\d]+)\/post\/(\w+)\/(\w+)\/([\w\-\:]+)\/(xml|json)$/', $url)):
				$request = 'post';
				break;

			case (preg_match('/^crimes\/([\-\d]+)\/delete\/(\w+)\/(xml|json)$/', $url)):
				$request = 'delete';
				break;
		}
		
		$response['response_type'] = $headers['Content-Type'];

		switch($headers['Content-Type'])
		{
			case 'text/xml':
			default:

				// Load the contents into a string
				$dom = new DOMDocument;
				$dom->loadXML($api);

				$response['file_loaded'] = true;

				// Validate the response
				if($dom->schemaValidate(__DIR__.'/xsd/'. $request .'.xsd'))
				{
					$response['schema_validated'] = true;
				}
				else
				{
					$response['schema_validated'] = false;
				}

				break;

			case 'application/json':

				$response['file_loaded'] = true;

				// Can't validate JSON, no right answer

				break;
		}
	}
	else
	{
		$response['error'] = $headers[0];
	}

	echo json_encode($response);

?>