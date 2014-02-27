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
	#response p { border: solid 5px; border-radius: 3px; -webkit-border-radius: 3px; padding: 5px; }
	#response p.error { border-color: #ff0200; }
	#response p.warning { border-color: #ff9900; }
	#response p.success { border-color: #00b224; }
	strong.error { color: #ff0200; }
	strong.warning { color: #ff9900; }
	strong.success { color: #00b224; }
	</style>

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- JavaScript -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script type="text/javascript">
	<!--
		$(function()
		{
			$('#test-form').submit(function(event)
			{
				event.preventDefault();
				if(typeof event.originalEvent === 'undefined')
				{
					event.preventDefault();
				}
				var username = $('input[name="username"]').val().toLowerCase();
				var base = 'http://www.cems.uwe.ac.uk/~'+ username +'/atwd/crimes';
				var response = $('#response');
				// GET All
				var get_all_urls = [
						
						'/6-2013/xml', 
						'/6-2013/json'
				];

				// GET Specific Region
				var get_region_urls = [
	
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
						'/6-2013/action_fraud/json'
				];

				// PUT
				var put_urls = [	

					'/6-2013/put/east_of_england:51970/xml',
					'/6-2013/put/east_of_england:51970/json',
					'/6-2013/put/east_midlands:51970/xml',
					'/6-2013/put/east_midlands:51970/json',
					'/6-2013/put/london:51970/xml',
					'/6-2013/put/london:51970/json',
					'/6-2013/put/north_east:51970/xml',
					'/6-2013/put/north_east:51970/json',
					'/6-2013/put/north_west:51970/xml',
					'/6-2013/put/north_west:51970/json',
					'/6-2013/put/south_east:51970/xml',
					'/6-2013/put/south_east:51970/json',
					'/6-2013/put/south_west:51970/xml',
					'/6-2013/put/south_west:51970/json',
					'/6-2013/put/west_midlands:51970/xml',
					'/6-2013/put/west_midlands:51970/json',
					'/6-2013/put/yorkshire_and_humber:51970/xml',
					'/6-2013/put/yorkshire_and_humber:51970/json',
					'/6-2013/put/wales:51970/xml',
					'/6-2013/put/wales:51970/json',
					'/6-2013/put/british_transport_police:51970/xml',
					'/6-2013/put/british_transport_police:51970/json',
					'/6-2013/put/action_fraud:51970/xml',
					'/6-2013/put/action_fraud:51970/json'
				];

				// POST
				var post_urls = [

					'/6-2013/post/east_of_england/wessex/hom:4-vwi:15-vwoi:25/xml',
					'/6-2013/post/east_of_england/wessex/hom:4-vwi:15-vwoi:25/json',
					'/6-2013/post/east_midlands/wessex/hom:4-vwi:15-vwoi:25/xml',
					'/6-2013/post/east_midlands/wessex/hom:4-vwi:15-vwoi:25/json',
					'/6-2013/post/london/wessex/hom:4-vwi:15-vwoi:25/xml',
					'/6-2013/post/london/wessex/hom:4-vwi:15-vwoi:25/json',
					'/6-2013/post/north_east/wessex/hom:4-vwi:15-vwoi:25/xml',
					'/6-2013/post/north_east/wessex/hom:4-vwi:15-vwoi:25/json',
					'/6-2013/post/north_west/wessex/hom:4-vwi:15-vwoi:25/xml',
					'/6-2013/post/north_west/wessex/hom:4-vwi:15-vwoi:25/json',
					'/6-2013/post/south_east/wessex/hom:4-vwi:15-vwoi:25/xml',
					'/6-2013/post/south_east/wessex/hom:4-vwi:15-vwoi:25/json',
					'/6-2013/post/south_west/wessex/hom:4-vwi:15-vwoi:25/xml',
					'/6-2013/post/south_west/wessex/hom:4-vwi:15-vwoi:25/json',
					'/6-2013/post/west_midlands/wessex/hom:4-vwi:15-vwoi:25/xml',
					'/6-2013/post/west_midlands/wessex/hom:4-vwi:15-vwoi:25/json',
					'/6-2013/post/yorkshire_and_humber/wessex/hom:4-vwi:15-vwoi:25/xml',
					'/6-2013/post/yorkshire_and_humber/wessex/hom:4-vwi:15-vwoi:25/json',
					'/6-2013/post/wales/wessex/hom:4-vwi:15-vwoi:25/xml',
					'/6-2013/post/wales/wessex/hom:4-vwi:15-vwoi:25/json',
					'/6-2013/post/british_transport_police/wessex/hom:4-vwi:15-vwoi:25/xml',
					'/6-2013/post/british_transport_police/wessex/hom:4-vwi:15-vwoi:25/json',
					'/6-2013/post/action_fraud/wessex/hom:4-vwi:15-vwoi:25/xml',
					'/6-2013/post/action_fraud/wessex/hom:4-vwi:15-vwoi:25/json'
				];

				// DELETE
				var delete_urls = [

					'/6-2013/delete/wessex/xml',
					'/6-2013/delete/wessex/json'
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
								response.append('<p class="error"><strong class="error">Unable to find document:</strong> <span class="url"><a href="'+ base + test_url +'">'+ base + test_url +'</a></url> (HTTP 404 Not Found)</p>');
							},
							500: function() 
							{
								response.append('<p class="error"><strong class="error">Service Error:</strong> <span class="url"><a href="'+ base + test_url +'">'+ base + test_url +'</a></span> (HTTP 500 Internal Server Error)</p>');
							},
							501: function() 
							{
								response.append('<p class="error"><strong class="error">URL pattern not recognized:</strong> <span class="url"><a href="'+ base + test_url +'">'+ base + test_url +'</a></span> (HTTP 501 Not Implemented)</p>');
							}
						},
						success: function(data) 
						{
							var status = 'success';
							var string = '<p><strong class="success">Found:</strong> <span class="url"><a href="'+ base + test_url +'">'+ base + test_url +'</a></span> (HTTP 200 OK)<br />';
							if(data.expected_type == data.response_type)
							{
								string += '<strong class="success">Content-type validated</strong> as '+ data.response_type +'<br />';
							}
							else
							{
								status = 'warning';
								string += '<strong class="warning">Content-type invalid:</strong> received "'+ data.response_type +'", expected "'+ data.expected_type +'"<br />';
							}

							if(data.valid)
							{
								string += '<strong class="success">Structure validated</strong> as '+ data.expected_type +'<br />';
							}
							else
							{
								status = 'error';
								string += '<strong class="error">Structure invalid:</strong> expecting well-formed '+ data.expected_type +'<br />';
							}

							if(data.response_type == 'text/xml')
							{
								if(data.schema_validated)
								{
									string += '<strong class="success">Content validated</strong> against the <a href="./xsd/'+ data.request +'.xsd">XSD Schema</a><br />';
								}
								else
								{
									if(status != 'error')
									{
										status = 'warning';
									}
									string += '<strong class="warning">Content failed to validate</strong> against the <a href="./xsd/'+ data.request +'.xsd">XSD Schema</a></br />';
								}
							}
							string += '</p>';
							response.append(string);
							$('#response p').last().addClass(status);
						}
					});
				}

				$('#response p, #response h2').remove();			

				response.append('<h2>GET All</h2>');

				get_all_urls.forEach(function(url)
				{
					test_url(url);
				});

				response.append('<h2>GET Region</h2>');

				get_region_urls.forEach(function(url)
				{
					test_url(url);
				});

				response.append('<h2>PUT</h2>');

				put_urls.forEach(function(url)
				{
					test_url(url);
				});

				response.append('<h2>POST</h2>');

				post_urls.forEach(function(url)
				{
					test_url(url);
				});

				response.append('<h2>DELETE</h2>');

				delete_urls.forEach(function(url)
				{
					test_url(url);
				});

				// Log the results
				$.ajax(
				{
					type: 'POST',
					data: {
						username: username
					},
					url: '/~b2-argo/atwd/test/ajax_log.php'
				});

				$('#response').append('<p style="text-align: center; border: none;"><a href="#top">Scroll to Top</a></p>');
				
			});
			
			<?php if($_GET['username']): ?>
			$('#test-form').submit();
			<?php endif; ?>

		});
	-->
	</script>
	<script type="text/javascript">
	<!--// Google Analytics
	
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-23790873-4']);
		_gaq.push(['_setDomainName', 'www.cems.uwe.ac.uk']);
		_gaq.push(['_trackPageview']);

	 	(function() {
	    	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  	})();
	-->
	</script>

</head>
<body>
	<a name="top"></a>
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
				<p><input type="text" name="username" placeholder="e.g. b2-argo" <?php if(isset($_GET['username'])) echo 'value="'. $_GET['username'] .'"'; ?> /> <input type="submit" value="Go" /></p>
			</form>

			<section id="response">
				
			</section>	

			<hr />

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