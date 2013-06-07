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

if(isset($_GET[cpu]))
{
	echo '<fieldset>';

	echo '<legend>CPU Monitoring</legend>';

	echo '<iframe src="svg/graph_cpu.svg" width="555" height="275" frameborder="0" type="image/svg+xml">';
	echo "</iframe>";
	
	echo '</fieldset>';
}
else
{
	echo '<fieldset>';

	echo '<legend>Bandwidth Monitoring</legend>';

	echo '<div id="tabs" class="tab"><ul>';
	for($i=0;$i<count($interfaces);$i++)
	{
		if($i == 0) $class = "selected"; else $class = "";
		$tmp_int = str_replace(".", "_", $interfaces[$i]);
	
		echo '<li><a class="'.$class.'" href="#'.$tmp_int.'">'.$interfaces[$i].'</a></li>';
	}
	echo '</ul>';

	for($i=0;$i<count($interfaces);$i++)
	{
		$tmp_int = str_replace(".", "_", $interfaces[$i]);
		echo '<div id="'.$tmp_int.'">';
	
		echo '<iframe src="svg/graph_if.svg?'.$interfaces[$i].'" width="555" height="275" frameborder="0" type="image/svg+xml">';
		echo "</iframe>";
	
		echo '</div>';
	}

	echo '</div>';
	echo '</fieldset>';
}

?>

</body>
</html>