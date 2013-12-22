<?php

require("mitm_vars.php");

if (isset($_GET['which']))
	$which = $_GET['which'];

?>
<html>
<head>
<title>Pineapple Control Center - <?php echo $module_name." [v".$module_version."]"; ?></title>
<script type="text/javascript" src="/includes/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>

<script type="text/javascript" src="js/mitm.js"></script>
<link rel="stylesheet" type="text/css" href="css/mitm.css" />
<link rel="stylesheet" type="text/css" href="css/firmware.css" />

<link rel="icon" href="/favicon.ico" type="image/x-icon"> 
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green">
	
<script type="text/javascript">
	$(document).ready(function(){ 
		refresh_config('<?php echo $which; ?>');
	});
</script>

<div id="tabs" class="tab">
	<ul>
		<li><a id="Configuration_link" href="#Conf">Configuration</a></li>
	</ul>

<div id="Conf" style='width: 99%'>
	[<a id="config" href="javascript:set_config('<?php echo $which; ?>');">Save</a>] <span id="refresh_text"></span><br />
	<div id="content_<?php echo $which; ?>"></div>
</div>

</div>

</body>
</html>