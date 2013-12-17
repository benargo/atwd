<?php namespace uwe\atwd;

if(!defined('BASEDIR')) exit('No direct script access allowed');

if(!function_exists('get_file'))
{
	/**
	 * get_file()
	 * 
	 * This function will get any file through the UWE proxy.
	 *
	 * It has been adapted so that if we
	 * are running on our local testing server, we do not
	 * need to use this function, as my private server
	 * does not have proxy requirements.
	 *
	 * @author Chris Wallace
	 * @author Ben Argo
	 * @param string $url
	 * @created 30 November 2009
	 * @updated 20 January 2012
	 * @source http://www.cems.uwe.ac.uk/~pchatter/php/dsa/dsa_utility.phps
	 */
	function get_file($url)
	{
        if($_CEMS_SERVER['HTTP_HOST'] == 'www.cems.uwe.ac.uk') 
        {
            $context = stream_context_create(array('http'=> array('proxy'=>'proxysg.uwe.ac.uk:8080', 'header'=>'Cache-Control: no-cache')));
     		$contents = file_get_contents($uri, false, $context);
        } 
        else 
        {
            $contents = file_get_contents($uri, false);
        }

        return $contents;
    }
}