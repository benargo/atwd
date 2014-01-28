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

// Action Fraud's ID in the database is 'af'
if(uwe\atwd\uri::get('region') == 'action_fraud')
{
	uwe\atwd\uri::set('region', 'af');
}

$region = uwe\atwd\region::get(uwe\atwd\uri::get('region'));

/**
 * URL Parameters
 * @param hom = Homicide
 * @param vwi = Violence with injury
 * @param vwoi = Violence without injury
 */
$param_keys = array('hom' => 'Homicide',
 					'vwi' => 'Violence with injury',
 					'vwoi' => 'Violence without injury');
$params = explode('-', uwe\atwd\uri::get('params'));
foreach($params as $key => $param)
{
	$param = explode(':', $param);
	$params[$param[0]] = (int) $param[1];
	unset($params[$key]);
}

if($region)
{
	// Create the XML to save
	$xml = simplexml_load_file(BASEDIR .'data/custom_areas.template.xml');
	$xml->region['id'] = $region->id;
	$xml->region->name = $region->name;
	$xml->region->area['id'] = uwe\atwd\uri::get('area');
	$xml->region->area->name = ucwords(str_replace('_', '', uwe\atwd\uri::get('area')));
	$xml->region->area->total_recorded_crime->including_fraud = array_sum($params);
	$xml->region->area->total_recorded_crime->excluding_fraud = array_sum($params);
	$xml->region->area->victim_based->violence_against_the_person['total'] = ($params['hom'] + $params['vwi'] + $params['vwoi']);
	$xml->region->area->victim_based->violence_against_the_person->homicide = $params['hom'];
	$xml->region->area->victim_based->violence_against_the_person->violence_with_injury = $params['vwi'];
	$xml->region->area->victim_based->violence_against_the_person->violence_without_injury = $params['vwoi'];

	$area = new uwe\atwd\area($xml->region->area);
	$region->postArea($area);
	
	if(!is_dir(BASEDIR .'data/custom/areas/'. $region->id))
	{
		mkdir(BASEDIR .'data/custom/areas/'. $region->id, 0777, true);
	}
	file_put_contents(BASEDIR .'data/custom/areas/'. $region->id .'/'. uwe\atwd\uri::get('area') .'.xml', $xml->asXML());


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

			$node = $dom->createElement('area');
			$node->setAttribute('id', $area->id);
			$node->setAttribute('total', $area->getTotalCrime(true));
			$dom_area = $dom_region->appendChild($node);

			foreach($params as $key => $param)
			{
				$node = $dom->createElement('recorded');
				$node->setAttribute('id', $param_keys[$key]);
				$node->setAttribute('total', $param);
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
			$json['response']['crimes']['region']['id'] = $region->name;
			$json['response']['crimes']['region']['total'] = $region->getTotalCrime(true);
			$json['response']['crimes']['region']['area']['id'] = $area->id;
			$json['response']['crimes']['region']['area']['total'] = $area->getTotalCrime(true);

			$count = 0;
			foreach($params as $key => $param)
			{
				$json['response']['crimes']['region']['area']['recorded'][$count]['id'] = $param_keys[$key];
				$json['response']['crimes']['region']['area']['recorded'][$count]['total'] = $param;
				$count++;
			}

			$json['response']['crimes']['england']['total'] = uwe\atwd\region::getTotalEngland(true);
			$json['response']['crimes']['england_wales']['total'] = uwe\atwd\region::getTotalEnglandAndWales(true);

			echo json_encode($json);
	}
}
else
{
	$error = new uwe\atwd\error(404, 'User requested to update figures for the region "'. uwe\atwd\uri::get('region') .'." Unfortunately that region doesn\'t exist', 16);
	echo $error->response();
}