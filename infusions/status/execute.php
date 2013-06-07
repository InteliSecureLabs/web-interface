<?php

require("status_vars.php");

?>
<html>
<head>
<title>Pineapple Control Center - <?php echo $module_name." [v".$module_version."]"; ?></title>
<script type="text/javascript" src="/includes/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>

<script type="text/javascript" src="js/status.js"></script>
<link rel="stylesheet" type="text/css" href="css/status.css" />
<link rel="stylesheet" type="text/css" href="css/firmware.css" />

<link rel="icon" href="/favicon.ico" type="image/x-icon"> 
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green">
	
<script type="text/javascript">
	$(document).ready(function(){ 
		$("#tabs ul").idTabs();
	});
</script>
	
<?php

if(isset($_GET[cmd]))
{
	$cmd = $_GET[cmd];
	
	echo '<fieldset>';

	echo '<legend>Execute: '.$cmd.'</legend>';

	exec ($cmd, $output);
	foreach($output as $outputline) {
	echo ("$outputline<br/>");}
	
	echo '</fieldset>';
}

?>

</body>
</html>