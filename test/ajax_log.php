<?php
	error_reporting(E_ALL);

	// Shorthand :)
	$username =  $_POST['username'];

	// Calculate the date, used for the log file name
	$file_name = __DIR__ .'/log/'. date('Y_m_d') .'_test_script.log';

	$message = date('r') .": Test script ran by \"$username\"\r\n";

	if(file_exists($file_name))
	{
		$log_file = fopen($file_name, 'a+');
		fwrite($log_file, $message);
		fclose($log_file);
	}
	else
	{
		file_put_contents($file_name, $message);
	}

	echo json_encode(array('log' => $message));