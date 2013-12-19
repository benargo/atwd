<?php
require_once('autoload.php');

// Switch through the years
switch($_GET['year'])
{
	case '6-2013':
		break;

	default:
		$error = new uwe\atwd\error(404, 'User requested  figures that this API doesn\'t have.', __LINE__);
		echo $error->response();
		exit;
		break;
}

$region = uwe\atwd\region::get($_GET['region']);

if($region)
{
	// Switch through the response formats
	switch($_GET['response'])
	{
		case 'xml':
		default:
			header('Content-type: text/xml');
			$dom = new DOMDocument;
			$dom->formatOutput = true;

			$node = $dom->createElement('response');
			$node->setAttribute('timestamp', time());

			$dom_response = $dom->appendChild($node);

			$node = $dom->createElement('crimes');
			$node->setAttribute('year', $_GET['year']);
			$dom_crimes = $dom_response->appendChild($node);

			$node = $dom->createElement('region');
			$node->setAttribute('id', ucwords($region->name));
			$node->setAttribute('total', $region->getTotalCrime(true));
			$dom_region = $dom_crimes->appendChild($node);
			
			foreach($region->getAreas() as $area)
			{
				$node = $dom->createElement('area');
				$node->setAttribute('id', ucwords($area->name));
				$node->setAttribute('total', $area->getTotalCrime(true));
				$dom_region->appendChild($node); 
			}

			echo $dom->saveXML();
			break;

		case 'json':
			header('Content-type: application/json');
			$json['response']['timestamp'] = time();
			$json['response']['crimes']['year'] = $_GET['year'];
			$json['response']['crimes']['region']['id'] = $region->name;
			$json['response']['crimes']['region']['total'] = $region->getTotalCrime(true);
			
			$count = 0;
			foreach($region->getAreas() as $area)
			{
				$json['response']['crimes']['region']['area'][$count]['id'] = $area->name;
				$json['response']['crimes']['region']['area'][$count]['total'] = $area->getTotalCrime(true);
				$count++;
			}

			echo json_encode($json);
	}
}
else
{
	$error = new uwe\atwd\error(404, 'User requested figures for the region "'. $_GET['region'] .'." Unfortunately that region doesn\'t exist', 16);
	echo $error->response();
}