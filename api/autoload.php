<?php

/***************************************************
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
* 2. Configuration Files
* 3. Helpful Functions
* 4. Classes
***************************************************/

// 1. the API first of all
require_once('../../../global/cems_config.php');
define('BASEDIR', $_CEMS_SERVER['DOCUMENT_ROOT'] .'public_html/atwd/');

// 3. Helpful Functions
foreach(scandir('./functions/') as $file)
{
	if(is_dir($file))
	{

	}
	if(substr($file, -4) == '.php')
	{
		require_once('./functions/'. $file);	
	}
}

// 4. Classes
$iterator = new RecursiveDirectoryIterator(BASEDIR.'api/classes');
foreach (new RecursiveIteratorIterator($iterator) as $filename => $file) 
{
	if(substr($file->getFileName(), -4) == '.php')
	{
		require_once($file->getPathName());
	}
}