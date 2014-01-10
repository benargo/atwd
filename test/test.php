<?php
		
	if(empty($_GET['username']) || !preg_match('/^[a-z0-9\-]$/', $_GET['username']))
	{
		http_response_code(500);
		$response = array('error' => 'Invalid username');
	}
	elseif(!is_dir('/nas/students/'. substr($_GET['username'], 0, 1) .'/'. strtolower($_GET['username'])))
	{
		http_response_code(404);
		$response = array('error' => 'Unknown username');
	}
	else
	{
		
	}


?>