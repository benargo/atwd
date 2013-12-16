<?php
	if(is_uploaded_file($_FILES['csv']['tmp_name']))
	{
		try 
		{
			move_uploaded_file($_FILES['csv']['tmp_name'], __DIR__.'/recorded_crime.csv');
			$csv = str_getcsv(file_get_contents($_FILES['csv']['tmp_name']));
			print_r($csv);
		}
		catch (Exception $e)
		{
			http_response_code(500);
			echo 'Caught exception: '. $e->getMessage() ."\n";
		}
	}
?>