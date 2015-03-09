<?php
if($_GET['file'])
{
	highlight_file('../' . $_GET['file']);
	exit;
}
else
{
	?><!DOCTYPE html>
	<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
	<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
	<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
	<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
	<head>
		<!-- Meta -->
		<meta charset="utf-8">
		<title>Source Highlighting</title>
		<meta name="robots" content="all">
		<meta name="author" content="Ben Argo">
		<meta name="copyright" content="University of the West of England">
		<meta name="description" content="Ben Argo's webspace at UWE, Bristol">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

		<!-- CSS -->
		<!-- http://www.getskeleton.com/ -->
		<link rel="stylesheet" href="../css/base.css">
		<link rel="stylesheet" href="../css/skeleton.css">
		<link rel="stylesheet" href="../css/layout.css">

		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- JavaScript -->
		<script type="text/javascript">
		<!--// Google Analytics
		
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-23790873-4']);
			_gaq.push(['_setDomainName', 'cems.uwe.ac.uk']);
			_gaq.push(['_trackPageview']);

		 	(function() {
		    	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  	})();
		-->
		</script>

		<!-- Inspiration for page layout lifted from http://www.cems.uwe.ac.uk/~pchatter/ -->

	</head>
	<body>
		<a name="top"></a>
		<div class="container">

			<header class="sixteen columns">
				<hgroup>
					<h1 class="remove-bottom" style="margin-top: 40px">Ben Argo</h1>
					<h5>~b2-argo</h5>
				</hgroup>
			</header>

			<hr class="clearfix row" />

			<nav class="sixteen columns" id="primary">
				<ul>
					<li><a href="./" rel="home">Home</a></li>
				<!--<li><a href="year-one/">Level One</a></li>-->
					<li><a href="year-two/">Level Two</a></li>
					<li><a href="year-three/">Level Three</a></li>
					<li><a href="resources/">Resources</a></li>
				</ul>
			</nav>

			<hr class="clearfix row" />

			</header>

			<article class="sixteen columns">

				<h1>Highlighting PHP Source Files</h1>

				<p>There's a little trick which you can use for making source code accessible on UWE's webspace. 
				It involves an .htaccess file and a simple PHP script that will handle the response.</p>

				<p>First, modify your .htaccess and insert the following rule:</p>

				<p><pre><code>RewriteRule ^(.*\.php)s$ path/to/source.php?file=$1 [L]</code></pre></p>

				<p>This tells Apache to match .phps files to the source file, using $1 to match the true name of the file.</p>

				<p>Now, make a file called <code>source.php</code>, and within it add the following code:</p>

				<p><pre><code>if($_GET['file'])
{
	highlight_file(__DIR__ . $_GET['file']);
	exit;
}</code></pre></p>

				<hr />

			</article>

			<!-- Footer -->
			<div class="sixteen columns clearfix">
				<p>Copyright &copy; 2013-14 University of the West of England, Bristol, <a href="http://www.benargo.com/">Ben Argo</a>.</p>
			</div>
			<div class="one-third column clearfix row">
				<a href="http://www.uwe.ac.uk/" target="_blank" rel="nofollow"><img src="../images/uwe_logo.gif" alt="UWE Logo"></a>
			</div>
			<div class="one-third column">
				<a href="http://www.uwesu.org/" target="_blank" rel="nofollow"><img src="../images/uwesu_logo.png" alt="UWESU Logo"></a>
			</div>
		</div><!-- container -->
	</body>
	</html>
	<?php
}