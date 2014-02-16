<?php
require_once('autoload.php');

// Iterate through the custom data folders
$iterator = new RecursiveDirectoryIterator(BASEDIR.'data/custom');
foreach (new RecursiveIteratorIterator($iterator) as $filename => $file) 
{
	if(substr($file->getFileName(), -4) == '.xml')
	{
		unlink($file->getPathName());
	}
}