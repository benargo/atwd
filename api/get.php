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

$regions = uwe\atwd\region::get($_GET['region']);

if($regions)
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
			$node->setAttribute('timesamp', time());

			$response = $dom->appendChild($node);

			$node = $dom->createElement('crimes');
			$node->setAttribute('year', $_GET['year']);
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

			echo $dom->saveXML();
			break;

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

			echo json_encode($json);
	}
}
else
{
	$error = new \uwe\atwd\error\http_404;
	dump($error);
}