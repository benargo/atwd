<?php

/**
 * Autoloader file for the RESTful Service.
 *
 * It includes a number of items, such as constants,
 * helpful functions, and the classes for 
 * object-oriented interaction.
 *
 * @author 10008548
 * @copyright 2013 University of the West of England
 * @version 1.0
 *
 * Table of Contents
 * -----------------
 * 1. Setup
 * 2. Helpful Functions
 * 3. Classes
 * 4. Enable requests from off campus
 */

// 1. the API first of all
require_once('../../../global/cems_config.php');
define('BASEDIR', $_CEMS_SERVER['DOCUMENT_ROOT'] .'public_html/atwd/');

// 2. Helpful Functions
$iterator = new RecursiveDirectoryIterator(BASEDIR.'api/functions');
foreach (new RecursiveIteratorIterator($iterator) as $filename => $file) 
{
	if(substr($file->getFileName(), -4) == '.php')
	{
		require_once($file->getPathName());
	}
}

// 3. Classes
$iterator = new RecursiveDirectoryIterator(BASEDIR.'api/classes');
foreach (new RecursiveIteratorIterator($iterator) as $filename => $file) 
{
	if(substr($file->getFileName(), -4) == '.php')
	{
		require_once($file->getPathName());
	}
}

// 4. Enable requests from off campus - like a true API should
header("Access-Control-Allow-Origin: *");