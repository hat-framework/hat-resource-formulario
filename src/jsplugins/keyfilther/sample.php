
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru" dir="ltr"> 
	<head>
		<title>jQuery KeyFilter Demo</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="keywords" content="jQuery, Keys, Validation" /> 
		<link rel="stylesheet" type="text/css" href="http://www.blueprintcss.org/blueprint/screen.css" media="screen, projection" />
		<link rel="stylesheet" type="text/css" href="http://www.blueprintcss.org/blueprint/print.css" media="print" />
		<!--[if IE]><link rel="stylesheet" href="http://www.blueprintcss.org/blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
		<style type="text/css">
			label { display: block; }
		</style>
	</head>
	<body>
		<div class="container">
			<h1>jQuery Keyfilter</h1>
			<h2>Demo page</h2>
			<noscript>
				<div class="error">Warning! This demo page correctly works with enabled JavaScript only.</div>
			</noscript>
			<fieldset>
				<legend>Controls that filtered by its class</legend>
				<label><input type="text" class="mask-pint" /> - mask-pint</label>
				<label><input type="text" class="mask-int" /> - mask-int</label>
				<label><input type="text" class="mask-pnum" /> - mask-pnum</label>
				<label><input type="text" class="mask-num" /> - mask-num</label>
				<label><input type="text" class="mask-hex" /> - mask-hex</label>
				<label><input type="text" class="mask-email" /> - mask-email</label>
				<label><input type="text" class="mask-alpha" /> - mask-alpha</label>
				<label><input type="text" class="mask-alphanum" /> - mask-alphanum</label>
			</fieldset>
			<fieldset>
				<legend>Controls that filtered by JavaScript code</legend>
				<label><input type="text" id="by-re" /> - By regular expression</label>
				<label><input type="text" id="by-f" /> - By function</label>
			</fieldset>
		</div>
		<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.1.min.js"></script>
                <script type="text/javascript" src="keyfilther.js"></script>
		<script type="text/javascript">
(function($)
{
	$('#by-re').keyfilter(/[\d\-]/);
	$('#by-f').keyfilter(function(c) { return c != 'A'; });
})(jQuery);
		</script>

	</body>
</html>