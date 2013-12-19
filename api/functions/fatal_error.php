<?php if(!defined('BASEDIR')) exit('No direct script access allowed');

if(!function_exists('fatal_handle'))
{
	/**
	 * fatal_handle()
	 *
	 * This function will handle all fatal errors produced by my code and catch them
	 *
	 * @return text/xml
	 */
	function fatal_handle() 
	{
		$last_error = error_get_last();
		if($last_error !== NULL)
		{
			$error = new uwe\atwd\error('500');
			foreach($last_error as $key => $value)
			{
				switch($key)
				{
					case 'message':
						if(!$error->getMessageAsText())
						{
							$error->setMessage($value);
						}
						break;
					case 'type':
						$error->setType($value);
						break;
					default:
						$error->$key = $value;
				}
			}

			echo $error->respond();
		}
	}

	// Set error reporting to E_ALL
	error_reporting(E_ALL);

	// Register the above function to run on fatal errors
	register_shutdown_function('fatal_handle');
}