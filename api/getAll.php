<?php
require_once('autoload.php');

// Switch through the months and the years
switch($_GET['year'])
{
	case '6-2013':
		break;

	default:
		http_response_code(404);
		exit;
		break;
}

$regions = uwe\atwd\region::get('all');

// Switch through the response formats
switch($_GET['response'])
{
	case 'xml':
	default:
		$dom = new DOMDocument;
		$dom->formatOutput = true;

		$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'response');
		$node->setAttribute('timesamp', time());

		$response = $dom->appendChild($node);

		$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'crimes');
		$node->setAttribute('year', $_GET['year']);
		$crimes = $response->appendChild($node);

		foreach($regions as $key => $region)
		{
			$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'region');
			$node->setAttribute('id', ucwords($region->name));
			$node->setAttribute('total', $region->getTotalCrime(true));
			$crimes->appendChild($node);
		}
}

echo $dom->saveXML();


