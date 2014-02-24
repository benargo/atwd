<?php
require_once(__DIR__ .'/autoload.php');
header('Content-type: application/json');

// An empty array to contain all the files
$deleted_files = array();
$failed_files = array();
$success = true;

// Iterate through the custom data folders
$iterator = new RecursiveDirectoryIterator(BASEDIR.'data/custom');
foreach (new RecursiveIteratorIterator($iterator) as $filename => $file) 
{
	if(substr($file->getFileName(), -4) == '.xml')
	{
		if(unlink($file->getPathName()))
		{
			$deleted_files[] = preg_replace('.*data/custom/', '', $file->getPathName());
		}
		else
		{
			$failed_files[] = preg_replace('.*data/custom/', '', $file->getPathName());
		}
	}
}

$json = array();
$json['deleted'] = $deleted_files;
$json['failed'] = $failed_files;
$json['success'] = $success;

echo json_encode($json);