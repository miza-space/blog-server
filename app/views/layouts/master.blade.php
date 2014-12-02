<?php
$assets = PageConf::makePageAssets();
?>
<!doctype html>
<!--[if lt IE 7 ]> <html class="ie ie6 ie-lt10 ie-lt9 ie-lt8 ie-lt7 no-js" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 ie-lt10 ie-lt9 ie-lt8 no-js" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 ie-lt10 ie-lt9 no-js" lang="en"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 ie-lt10 no-js" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="en"><!--<![endif]-->
<!-- the "no-js" class is for Modernizr. --> 

<head>
	<meta charset="utf-8">
	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<!-- Important stuff for SEO, don't neglect. (And don't dupicate values across your site!) -->
	<title></title>
	<meta name="author" content="" />
	<meta name="description" content="" />
	
	<!-- Don't forget to set your site up: http://google.com/webmasters -->
	<meta name="google-site-verification" content="" />
	
	<!-- Who owns the content of this site? -->
	<meta name="Copyright" content="" />
	
	<!--  Mobile Viewport
	http://j.mp/mobileviewport & http://davidbcalhoun.com/2010/viewport-metatag
	device-width : Occupy full width of the screen in its current orientation
	initial-scale = 1.0 retains dimensions instead of zooming out if page height > device height
	maximum-scale = 1.0 retains dimensions instead of zooming in if page width < device width (wrong for most sites)
	-->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta property="wb:webmaster" content="923fb6ba3fa4ef34" />
	
	<!-- Use Iconifyer to generate all the favicons and touch icons you need: http://iconifier.net -->
	<link rel="shortcut icon" href="favicon.ico" />
	
	<!-- concatenate and minify for production -->
	{{ $assets['css'] }}
	<style type="text/css">
	{{ PageConf::makeCustomerPageStyle() }}
	</style>
	<!-- This is an un-minified, complete version of Modernizr. 
		 Before you move to production, you should generate a custom build that only has the detects you need. -->
	<script src="/js/modernizr-2.7.1.dev.js"></script>
	<script>
	// var _hmt = _hmt || [];(function() {var hm = document.createElement("script");hm.src = "//hm.baidu.com/hm.js?102aff6356f6873f3b0e54df68200e0f";var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(hm, s);})();
	</script>
</head>

<body>
<div class="page-container">
	@yield('content')
	<footer class="page-ft">
		<p>Copyright © 2014, Powered by Minzhang Wei, Mail: me@zhangge.me</p>
		<p>备案号：粤ICP备14032816号</p>
	</footer>
</div>
<div class="page-bg">
</div>
<div class="fade-bg"></div>
@include('layouts._sidebar')
{{ $assets['js'] }}
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=34968471" charset="UTF-8"></script>
</body>
</html>