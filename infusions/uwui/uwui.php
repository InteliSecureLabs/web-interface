<?php

require("uwui_vars.php");

?>
<html>
<head>
<title>Pineapple Control Center - <?php echo $module_name." [v".$module_version."]"; ?></title>
<script type="text/javascript" src="/includes/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>

<script type="text/javascript" src="js/uwui.js"></script>
<link rel="stylesheet" type="text/css" href="css/uwui.css" />
<link rel="stylesheet" type="text/css" href="css/firmware.css" />

</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green">

<script type="text/javascript">
	$(document).ready(function(){ init(); });
</script> 

<?php if(file_exists("/pineapple/includes/navbar.php")) require('/pineapple/includes/navbar.php'); ?>

<p id="version">v<?php echo $module_version; ?></p>
<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $module_name; ?> <span id="refresh_text"></span> <span style="float:right; padding-right: 5px;"><a style="color: white; text-decoration: none;" id="toggle_link" href="javascript:mytoggle($('.sidePanelContent'),$('#toggle_link'),$('.sidePanelTitle'));">[_]</a></span></div>
<div class=sidePanelContent>
<?php

$checks_passed = true;
foreach($dependencies as $k => $v)
{
	$installed = exec("which ".$v) != "" ? 1 : 0;

	if ($installed)
	{
		echo $v." <font color=\"lime\"><strong>installed</strong></font><br />";
	}
	else
	{ 
		echo $v." <span id=\"".$v."_status\"><font color=\"red\"><strong>not installed</strong></font></span>";
		echo " | Install to <a href=\"javascript:install('".$v."','internal');\"><strong>Internal Storage</strong></a> / <a href=\"javascript:install('".$v."','usb');\"><strong>USB</strong></a><br />";
		$checks_passed = false;
	}
}

?>
</div>
</div>

<?php
if(!$checks_passed) 
{
	echo "Dependencies checks <font color=\"red\"><strong>failed</strong></font>. All above dependencies have to be installed first.";
	exit();
}
?>

<script type="text/javascript"> mytoggle($('.sidePanelContent'),$('#toggle_link'),$('.sidePanelTitle')); </script> 
<iframe id="uwui_frame" src="uwui/index.php" style="top:0; bottom:0; width:98%; height:98%"></iframe>

</body>
</html>