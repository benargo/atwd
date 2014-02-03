<?php if(!defined('BASEDIR')) exit('No direct script access allowed');

if(!function_exists('dump'))
{
	/**
	 * dump()
	 *
	 * This function is a sweet little helper that dumps out the data I feed it. 
	 * Very useful for debugging.
	 *
	 * @access public
	 * @param mixed $data
	 * @param bool $exit (default = true)
	 * @return dumped contents
	 */
	function dump($data,$exit=true) 
	{
		print "<pre>";
		print_r($data);
		print "</pre>";
		if($exit) 
		{
			exit;
		}
	}
}

