<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
	<!-- Meta -->
	<meta charset="utf-8">
	<title>Data Upload</title>
	<meta name="description" content="UFCEWT-20-3: Advanced Topics in Web Development">
	<meta name="author" content="10008548">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS -->
	<!-- http://www.getskeleton.com/ -->
	<link rel="stylesheet" href="../media/css/base.css">
	<link rel="stylesheet" href="../media/css/skeleton.css">
	<link rel="stylesheet" href="../media/css/layout.css">

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- JavaScript -->
	<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script src="../media/js/data-upload.js"></script>

</head>
<body>

	<div class="container">
		<div class="sixteen columns">
			<h1 class="remove-bottom" style="margin-top: 40px">Data Upload</h1>
			<h5>Police Recorded Crime for England &amp; Wales</h5>
			<hr />
		</div>
		<div class="sixteen columns row">
			<p>1. Download the <a href="recorded_crime.csv">CSV File</a> to upload.</p>
			<form id="upload" action="upload" enctype="multipart/form-data" method="POST">
				<input type="hidden" name="MAX_FILE_SIZE" value="32000">
				<input type="hidden" name="year" value="6-2013">
				<p>2. Upload a CSV file which will become the recorded crime data for England and Wales</p>
				<p class="upload"><input type="file" name="csv"></p>
			</form>
			<p>View the <a href="recorded_crime.xsd">XSD schema</a><?php echo (file_exists('./recorded_crime.xml') ? ' or <a href="recorded_crime.xml">the existing data</a>' : ''); ?>.</p>
		</div>

		<!-- Footer -->
		<hr>
		<div class="sixteen columns clearfix">
			<p>Copyright &copy; 2013-14 University of the West of England, Bristol. Assignment by 10008548.</p>
		</div>
		<div class="one-third column clearfix">
			<a href="http://www.uwe.ac.uk/" target="_blank" rel="nofollow"><img src="../media/images/uwe.75px.png" alt="UWE Logo"></a>
		</div>
		<div class="one-third column">
			<a href="https://www.gov.uk/government/organisations/home-office" target="_blank" rel="nofollow"><img src="../media/images/home_office.75px.png" alt="Home Office Logo"></a>
		</div>
	</div><!-- container -->
</body>
</html>