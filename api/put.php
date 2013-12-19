<?php
require_once('autoload.php');

// Switch through the years
switch(uwe\atwd\uri::get('year'))
{
	case '6-2013':
		break;

	default:
		$error = new uwe\atwd\error(501, 'User trying to update figures that this API doesn\'t support.');
		echo $error->response();
		exit;
		break;
}

// British Transport Police's ID is 'btp'
if(uwe\atwd\uri::get('region') == 'british_transport_police')
{
	uwe\atwd\uri::set('region', 'btp');
}

$region = uwe\atwd\region::get(uwe\atwd\uri::get('region'));

if($region)
{
	$dom = new DOMDocument;
	$dom->formatOutput = true;

	// Create the XML to save
	if(file_exists(BASEDIR .'data/custom/regions/'. uwe\atwd\uri::get('region') .'.xml'))
	{
		$import = dom_import_simplexml(simplexml_load_file(BASEDIR .'data/custom/regions/'. uwe\atwd\uri::get('region') .'.xml'));
		$dom_custom_data = $dom->appendChild($import)
	}
	else
	{
		$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/~b2-argo/atwd/', 'custom_data');
		$node->setAttribute('timestamp', time());
		$dom_custom_data = $dom->appendChild($node);

		$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/~b2-argo/atwd/', 'region');
		$node->setAttribute('id', uwe\atwd\uri::get('region'));
		$dom_region = $dom_custom_data->appendChild($node);

		
	}

	// Switch through the response formats
	switch(uwe\atwd\uri::get('response'))
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
			$node->setAttribute('year', uwe\atwd\uri::get('year'));
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
			$json['response']['crimes']['year'] = uwe\atwd\uri::get('year');
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
	$error = new uwe\atwd\error(404, 'User requested figures for the region "'. uwe\atwd\uri::get('region') .'." Unfortunately that region doesn\'t exist', 16);
	echo $error->response();
}