<?php
require_once(__DIR__ .'/autoload.php');

// Iterate through the custom data folders
$iterator = new RecursiveDirectoryIterator(BASEDIR.'data/custom');
foreach (new RecursiveIteratorIterator($iterator) as $filename => $file) 
{
	if(substr($file->getFileName(), -4) == '.xml')
	{
		echo (unlink($file->getPathName()) ? 'Deleted '. $file->getPathName() : 'Unable to delete '. $file->getPathName() );
	}
}