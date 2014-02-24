<?php
require_once(__DIR__ .'/autoload.php');

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
			$deleted_files[] = preg_replace('/.*data\/custom\//', '', $file->getPathName());
		}
		else
		{
			$failed_files[] = preg_replace('/.*data\/custom\//', '', $file->getPathName());
			$success = false;
		}
	}
}

?><section id="response" class="<?php echo ($success === true ? 'success' : 'warning'); ?>">
	<?php foreach($deleted_files as $file): ?>
	<p><span class="success">Deleted:</span> <?php echo $file; ?></p>
	<?php endforeach; ?>
	<?php foreach($failed_files as $file): ?>
	<p><span class="warning">Failed to delete:</span> <?php echo $file; ?></p>
	<?php endforeach; ?>

	<?php if(empty($deleted_files) && empty($failed_files)): ?>
	<p>There was no custom data to delete. Figures remain unchanged as they were in the CSV file.</p>
	<?php endif; ?>
</section>