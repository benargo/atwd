<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
	<!-- Meta -->
	<meta charset="utf-8">
	<title>API Test Script</title>
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
	p.success:before { content: "\2713 "; color: #51e853; font-weight: bold; }
	</style>

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- JavaScript -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

</head>
<body>

	<div class="container">

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

			<section id="response" style="display: none;">
				<h2>Result</h2>
			</section>

			<script type="text/javascript"><!--
			$('#test-form').submit(function(event)
			{
				event.preventDefault();
				var username = $('input[name="username"]').val().toLowerCase();
				var base = 'http://www.cems.uwe.ac.uk/~'+ username +'/atwd/crimes';
				var response = $('#response');
				var test_urls = [
					'/6-2013/xml', 
					'/6-2013/json',
					'/6-2013/east_of_england/xml',
					'/6-2013/east_of_england/json',
					'/6-2013/east_midlands/xml',
					'/6-2013/east_midlands/json',
					'/6-2013/london/xml',
					'/6-2013/london/json',
					'/6-2013/north_east/xml',
					'/6-2013/north_east/json',
					'/6-2013/north_west/xml',
					'/6-2013/north_west/json',
					'/6-2013/south_east/xml',
					'/6-2013/south_east/json',
					'/6-2013/south_west/xml',
					'/6-2013/south_west/json',
					'/6-2013/west_midlands/xml',
					'/6-2013/west_midlands/json',
					'/6-2013/yorkshire_and_humber/xml',
					'/6-2013/yorkshire_and_humber/json',
					'/6-2013/wales/xml',
					'/6-2013/wales/json',
					'/6-2013/british_transport_police/xml',
					'/6-2013/british_transport_police/json',
					'/6-2013/action_fraud/xml',
					'/6-2013/action_fraud/json',
				];

				function test_url(test_url)
				{
					var response_type = test_url.split('/');
					response_type = response_type[response_type.length-1];

					$.ajax(
					{
						async: false,
						url: '/~b2-argo/atwd/test/ajax_test.php?url='+ base + test_url,
						method: 'get',
						statusCode:
						{
							404: function() 
							{
								response.append('<p class="error 404">'+ base + test_url +' (HTTP 404 Not Found)</p>');
							},
							500: function() 
							{
								response.append('<p class="error 500">'+ base + test_url +' (HTTP 500 Internal Server Error)</p>');
							},
							501: function() 
							{
								response.append('<p class="error 501">'+ base + test_url +' (Error 501 URL pattern not recognized)</p>');
							}
						},
						success: function(data, status, xhr) 
						{
							switch(xhr.getResponseHeader("content-type"))
							{

							}
							response.append('<p class="success">'+ base + test_url +' ()</p>');
							
						}
					});
				}


				test_urls.forEach(function(url)
				{
					test_url(url);
				});

				response.show();

				// 2.1.1 GET All (XML)

				
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