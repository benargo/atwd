<?php
require_once('autoload.php');

// Google Analytics
$ga = new GoogleAnalytics;

// Switch through the years
switch(uwe\atwd\uri::get('year'))
{
	case '6-2013':
		break;

	default:
		$error = new uwe\atwd\error(404, 'User trying to update figures that this API doesn\'t support.');
		echo $error->response();

		// Google Analytics
		$ga->event('error', 'code = 404');
		exit;
		break;
}

$region = false;
$area = false;
$areas = array();

$iterator = new RecursiveDirectoryIterator(BASEDIR.'data/custom/areas');
foreach (new RecursiveIteratorIterator($iterator) as $filename => $file) 
{
	if($file->getFileName() == uwe\atwd\uri::get('area') .'.xml')
	{
		$path = explode('/', str_replace(BASEDIR .'data/custom/areas/', '', $filename));
		$xml = simplexml_load_file($filename);
		$region = new uwe\atwd\region($path[0]);
		$area = new uwe\atwd\area($xml->region->area);
		$areas[] = $area;
		
		$region->deleteArea($area->id);
		unlink(BASEDIR .'data/custom/areas/'. $region->id .'/'. $area->id .'.xml');
	}
}

if($region && $area)
{
	// Switch through the response formats
	switch(uwe\atwd\uri::get('response'))
	{
		case 'xml':
			header('Content-type: text/xml');
			$dom = new DOMDocument;
			$dom->formatOutput = true;

			$node = $dom->createElement('response');
			$node->setAttribute('timestamp', time());
			$dom_response = $dom->appendChild($node);

			$node = $dom->createElement('crimes');
			$node->setAttribute('year', uwe\atwd\uri::get('year'));
			$dom_crimes = $dom_response->appendChild($node);

			$node = $dom->createElement('area');
			$node->setAttribute('id', $area->id);

			$total_crime = 0;
			foreach($areas as $area)
			{
				$total_crime += $area->getTotalCrime(true);
			}

			$node->setAttribute('deleted', $total_crime);
			$dom_area = $dom_crimes->appendChild($node);

			foreach($area->iterate() as $key => $value)
			{
				if($value == 0)
				{
					continue;
				}
				$node = $dom->createElement('deleted');
				$node->setAttribute('id', ucwords(str_replace('_', ' ', $key)));
				$node->setAttribute('total', $value);
				$dom_area->appendChild($node);
			}

			$node = $dom->createElement('england');
			$node->setAttribute('total', uwe\atwd\region::getTotalEngland(true));
			$dom_crimes->appendChild($node);

			$node = $dom->createElement('england_wales');
			$node->setAttribute('total', uwe\atwd\region::getTotalEnglandAndWales(true));
			$dom_crimes->appendChild($node);

			echo $dom->saveXML();
			break;

		case 'json':
			header('Content-type: application/json');
			$json['response']['timestamp'] = time();
			$json['response']['crimes']['year'] = uwe\atwd\uri::get('year');
			$json['response']['crimes']['area']['id'] = $area->name;
			$json['response']['crimes']['area']['deleted'][0] = $area->getTotalCrime(true);

			$count = 1;
			foreach($area->iterate() as $key => $value)
			{
				if($value == 0)
				{
					continue;
				}
				$json['response']['crimes']['area']['deleted'][$count]['id'] = ucwords(str_replace('_', ' ', $key));
				$json['response']['crimes']['area']['deleted'][$count]['total'] = $value;
				$count++;
			}
			
			$json['response']['crimes']['england']['total'] = uwe\atwd\region::getTotalEngland(true);
			$json['response']['crimes']['england_wales']['total'] = uwe\atwd\region::getTotalEnglandAndWales(true);

			echo json_encode($json);
			break;
	}


	// Google Analytics
	$ga->event('delete', $area->name);

}
else
{
	$error = new uwe\atwd\error(404, 'User requested to delete the area "'. uwe\atwd\uri::get('area') .'." Unfortunately that area doesn\'t exist', 16);
	echo $error->response();

	// Google Analytics
	$ga->event('error', 'code = 404');
}