<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
	<!-- Meta -->
	<meta charset="utf-8">
	<title>Documentation</title>
	<meta name="description" content="UFCEWT-20-3: Advanced Topics in Web Development">
	<meta name="author" content="10008548">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="generator" content="Mou"><!-- http://mouapp.com/ -->

	<!-- CSS -->
	<!-- http://www.getskeleton.com/ -->
	<link rel="stylesheet" href="../media/css/base.css">
	<link rel="stylesheet" href="../media/css/skeleton.css">
	<link rel="stylesheet" href="../media/css/layout.css">
	<style type="text/css">
	input { display: inline-block !important; }
	</style>

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- JavaScript -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

</head>
<body>

	<div class="container">

		<a name="top">
		<header class="sixteen columns">
			<h1 class="remove-bottom" style="margin-top: 40px">API Test Script</h1>
			<h5>Advanced Topics in Web Development</h5>
			<hr />
		</header>

		<article class="sixteen columns">

			<p>This script tests a users API for the Advanced Topics in Web Development module to ensure everything works correctly.</p>


			<form id="test-form" action="./" method="get">
				<p><label for="username">To start, fill in your username below:</label></p>
				<p><input type="text" name="username" placeholder="b2-argo" /> <input type="submit" value="Go" /></p>
			</form>

			<script type="text/javascript"><!--
			$('#test-form').submit(function(event)
			{
				event.preventDefault();
				$.ajax({
					url: './test.php?username='+ $('input[name="username"]').val(),
					method: 'get'
				}).done(function(data){
					console.log(data);
				});
			});
			--></script>

		</article>

		<!-- Footer -->
		<div class="sixteen columns clearfix">
			<p>Copyright &copy; 2013-14 University of the West of England, Bristol. Script by <a href="http://www.benargo.com/">Ben Argo</a>.</p>
		</div>
		<div class="one-third column clearfix row">
			<a href="http://www.uwe.ac.uk/" target="_blank" rel="nofollow"><img src="../media/images/uwe.75px.png" alt="UWE Logo"></a>
		</div>
		<div class="one-third column">
			<a href="https://www.gov.uk/government/organisations/home-office" target="_blank" rel="nofollow"><img src="../media/images/home_office.75px.png" alt="Home Office Logo"></a>
		</div>
	</div><!-- container -->
</body>
</html>