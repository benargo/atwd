<?php
require_once('autoload.php');

// Switch through the years
switch(uwe\atwd\uri::get('year'))
{
	case '6-2013':
		break;

	default:
		$error = new uwe\atwd\error(404, 'User requested  figures that this API doesn\'t have.', __LINE__);
		echo $error->response();
		exit;
		break;
}

$regions = uwe\atwd\region::get('all');

// Switch through the response formats
switch(uwe\atwd\uri::get('response'))
{
	// Build the XML response object
	case 'xml':
	default:
		header('Content-type: text/xml');
		$dom = new DOMDocument;
		$dom->formatOutput = true;

		$node = $dom->createElement('response');
		$node->setAttribute('timestamp', time());

		$response = $dom->appendChild($node);

		$node = $dom->createElement('crimes');
		$node->setAttribute('year', uwe\atwd\uri::get('year'));
		$crimes = $response->appendChild($node);

		foreach($regions as $key => $region)
		{
			if($key != 'wales')
			{
				if(!isset($england))
				{
					$england = 0;
				}
				$england += $region->getTotalCrime(true);
			}

			if($key == 'btp' || $key == 'af')
			{
				$node = $dom->createElement('national');
			}
			else
			{
				$node = $dom->createElement('region');
			}
			$node->setAttribute('id', ucwords($region->name));
			$node->setAttribute('total', $region->getTotalCrime(true));
			$crimes->appendChild($node);
		}

		$node = $dom->createElement('england');
		$node->setAttribute('total', $england);
		$crimes->appendChild($node);

		$node = $dom->createElement('wales');
		$node->setAttribute('total', $regions['wales']->getTotalCrime(true));
		$crimes->appendChild($node);

		// Echo out the XML
		echo $dom->saveXML();
		break;

	// Build the JSON response object
	case 'json':
		header('Content-type: application/json');
		$json['response']['timestamp'] = time();
		$json['response']['crimes']['year'] = $_GET['year'];

		// Loop through the regions;
		foreach($regions as $key => $region)
		{
			if(!isset($count))
			{
				$count = 0;
			}

			if($key != 'wales')
			{
				if(!isset($england))
				{
					$england = 0;
				}
				$england += $region->getTotalCrime(true);
			}

			if($key == 'btp' || $key == 'af')
			{
				$json['response']['crimes']['national'][$count]['id'] = $region->name;
				$json['response']['crimes']['national'][$count]['total'] = $region->getTotalCrime(true);
			}
			else
			{
				$json['response']['crimes']['region'][$count]['id'] = $region->name;
				$json['response']['crimes']['region'][$count]['total'] = $region->getTotalCrime(true);
			}

			$count++;
		}

		$json['response']['crimes']['england']['total'] = $england;
		$json['response']['crimes']['wales']['total'] = $regions['wales']->getTotalCrime(true);

		// Echo out the JSON
		echo json_encode($json);
		break;
}

