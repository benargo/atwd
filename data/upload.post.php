<?php
	if(is_uploaded_file($_FILES['csv']['tmp_name']))
	{
		$csv = fopen($_FILES['csv']['tmp_name'], 'rt');

		// Set up the stuff we need for the XML File
		$row_count = 1;
		$dom = new DOMDocument;
		$dom->formatOutput = true;
		$crimes = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'crimes');
		$crimes->setAttribute('timestamp', time());
		$crimes = $dom->appendChild($crimes);

		$headers = array();
		$areas = array();

		while($row = fgetcsv($csv))
		{	
			switch($row_count)
			{
				// Skip these rows
				case 1:
				case 2:
				case 3:
				case 6:
					break;

				// Generate the headers
				case 4:
				case 5:
					foreach($row as $key => $column)
					{
						if($column)
						{
							$headers[$key] = preg_replace('/(\s)+/', ' ', $column);
						}
					}
					break;
				
				// Loop through all the data
				case ($row_count >= 7):
					// Skip empty rows
					if(empty($row[0]) || $row[0] == 'ENGLAND')
					{
						break;
					}

					if(preg_match('/(Region|^WALES)$/', $row[0]))
					{
						$region = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'region');
						$region->setAttribute('id', strtolower(str_replace(' ', '_', preg_replace('/( Region)/', '', $row[0]))));
						$region = $crimes->appendChild($region);

						$region_name = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'name', str_replace(' Region', '', $row[0]));
						$region_name = $region->appendChild($region_name);

						foreach($areas as $area)
						{
							$region->appendChild($area);
						}

						unset($areas);
					}
					else
					{
						$area_id = strtolower(str_replace(' ', '_', str_replace('1', '', $row[0])));
						$areas[$area_id] = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'area');
						$areas[$area_id]->setAttribute('id', $area_id);

						// Area Name
						$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'name', str_replace('1', '', $row[0]));
						$areas[$area_id]->appendChild($node);

						// Total recorded crime
						$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'total_recorded_crime');
						$total = $areas[$area_id]->appendChild($node);

							// Total recorded crime, including fraud
							$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'including_fraud', str_replace(',', '', $row[1]));
							$total->appendChild($node);

							// Total recorded crime, excluding fraud
							$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'excluding_fraud', str_replace(',', '', $row[2]));
							$total->appendChild($node);

							// Unset $total, clearing memory
							unset($total);

						// Victim-based crime
						$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'victim_based');
						$victim_based = $areas[$area_id]->appendChild($node);

							// Violence against the person
							$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'violence_against_the_person');
							$node->setAttribute('total', array_sum(array(str_replace(',', '', $row[5]), str_replace(',', '', $row[6]), str_replace(',', '', $row[7]))));
							$violence_against_the_person = $victim_based->appendChild($node);

								// Homicide
								$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'homicide', str_replace(',', '', $row[5]));
								$violence_against_the_person->appendChild($node);

								// Violence with injury
								$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'violence_with_injury', str_replace(',', '', $row[6]));
								$violence_against_the_person->appendChild($node);

								// Violence without injury
								$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'violence_without_injury', str_replace(',', '', $row[7]));
								$violence_against_the_person->appendChild($node);

								// Unset $violence_against_the_person, clearing memory
								unset($violence_against_the_person);

							// Sexual offences
							$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'sexual_offences', str_replace(',', '', $row[8]));
							$victim_based->appendChild($node);

							// Robbery
							$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'robbery', str_replace(',', '', $row[9]));
							$victim_based->appendChild($node);

							// Theft offences
							$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'theft_offences');
							$node->setAttribute('total', array_sum(array(str_replace(',', '', $row[12]), str_replace(',', '', $row[13]), str_replace(',', '', $row[14]), str_replace(',', '', $row[15]), str_replace(',', '', $row[16]), str_replace(',', '', $row[17]), str_replace(',', '', $row[18]))));
							$theft_offences = $victim_based->appendChild($node);

								// Burglary
								$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'burglary');
								$node->setAttribute('total', array_sum(array(str_replace(',', '', $row[12]), str_replace(',', '', $row[13]))));
								$burglary = $theft_offences->appendChild($node);

									// Domestic burglary
									$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'domestic', str_replace(',', '', $row[12]));
									$burglary->appendChild($node);

									// Non-domestic burglary
									$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'non_domestic', str_replace(',', '', $row[13]));
									$burglary->appendChild($node);

									// Unset $burglary, clearing memory
									unset($burglary);

								// Vehicle offences
								$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'vehicle_offences', str_replace(',', '', $row[14]));
								$theft_offences->appendChild($node);

								// Theft from the person
								$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'theft_from_the_person', str_replace(',', '', $row[15]));
								$theft_offences->appendChild($node);

								// Bicycle theft
								$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'bicycle_theft', str_replace(',', '', $row[16]));
								$theft_offences->appendChild($node);

								// Shoplifting
								$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'shoplifting', str_replace(',', '', $row[17]));
								$theft_offences->appendChild($node);

								// All other theft offences
								$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'other', str_replace(',', '', $row[18]));
								$theft_offences->appendChild($node);

								// Unset $theft_offences, clearing memory
								unset($theft_offences);

							// Criminal damage and arson
							$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'criminal_damage_and_arson', str_replace(',', '', $row[19]));
							$victim_based->appendChild($node);

							// Unset $victim_based, clearing memory
							unset($victim_based);

						// Other crimes against society
						$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'other_crimes_against_society');
						$node->setAttribute('total', array_sum(array(str_replace(',', '', $row[21]), str_replace(',', '', $row[22]), str_replace(',', '', $row[23]), str_replace(',', '', $row[24]), str_replace(',', '', $row[25]))));
						$other_crimes_against_society = $areas[$area_id]->appendChild($node);

							// Drug offences
							$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'drug_offences', str_replace(',', '', $row[21]));
							$other_crimes_against_society->appendChild($node);

							// Possession of weapons offences
							$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'posession_of_weapons_offences', str_replace(',', '', $row[22]));
							$other_crimes_against_society->appendChild($node);

							// Public order offences
							$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'public_order_offences', str_replace(',', '', $row[23]));
							$other_crimes_against_society->appendChild($node);

							// Miscellaneous crimes against society
							$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'miscellaneous', str_replace(',', '', $row[24]));
							$other_crimes_against_society->appendChild($node);

							// Fraud
							$node = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'fraud', str_replace(',', '', $row[26]));
							$other_crimes_against_society->appendChild($node);

							// Unset $other_crimes_against_society, clearing memory
							unset($other_crimes_against_society);
					}

					if(preg_match('/(British Transport Police|Action Fraud1)$/', $row[0]))
					{
						$acronym = preg_replace('~\b(\w)|.~', '$1', $row[0]);
						$region = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'region');
						$region->setAttribute('id', strtolower(str_replace(' ', '_', str_replace('1', '', $acronym))));
						$region = $crimes->appendChild($region);

						$region_name = $dom->createElementNS('http://www.cems.uwe.ac.uk/assignments/10008548/atwd/', 'name', str_replace('1', '', $row[0]));
						$region_name = $region->appendChild($region_name);

						foreach($areas as $area)
						{
							$region->appendChild($area);
						}

						unset($areas);
					}


					break;
			}
			$row_count++;
		}

		if($dom->schemaValidate(__DIR__.'/recorded_crime.xsd'))
		{
			file_put_contents(__DIR__.'/recorded_crime.xml', $dom->saveXML());
		}
		else
		{
			header('Content-type: text/xml');
			print_r($dom->saveXML());
			exit;
		}
	}
	else
	{
		http_response_code(400);
		exit;
	}
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
	<!-- Meta -->
	<meta charset="utf-8">
	<title>Data Upload</title>
	<meta name="description" content="UFCEWT-20-3: Advanced Topics in Web Development">
	<meta name="author" content="10008548">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS -->
	<!-- http://www.getskeleton.com/ -->
	<link rel="stylesheet" href="../media/css/base.css">
	<link rel="stylesheet" href="../media/css/skeleton.css">
	<link rel="stylesheet" href="../media/css/layout.css">

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- JavaScript -->
	<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script src="../media/js/data-upload.js"></script>

</head>
<body>

	<div class="container">
		<div class="sixteen columns">
			<h1 class="remove-bottom" style="margin-top: 40px">Upload Successful</h1>
			<h5>Police Recorded Crime for England &amp; Wales</h5>
			<hr />
		</div>
		<div class="sixteen columns row">
			<p>The data upload was a success. You can now download the <a href="./recorded_crime.xml">generated XML</a>.
		</div>

		<!-- Footer -->
		<hr>
		<div class="sixteen columns clearfix">
			<p>Copyright &copy; 2013-14 University of the West of England, Bristol. Assignment by 10008548.</p>
		</div>
		<div class="one-third column clearfix">
			<a href="http://www.uwe.ac.uk/" target="_blank" rel="nofollow"><img src="../media/images/uwe.75px.png" alt="UWE Logo"></a>
		</div>
		<div class="one-third column">
			<a href="https://www.gov.uk/government/organisations/home-office" target="_blank" rel="nofollow"><img src="../media/images/home_office.75px.png" alt="Home Office Logo"></a>
		</div>
	</div><!-- container -->
</body>
</html>