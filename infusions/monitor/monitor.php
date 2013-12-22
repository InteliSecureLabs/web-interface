<?php

require("monitor_vars.php");

?>
<html>
<head>
<title>Pineapple Control Center - <?php echo $module_name." [v".$module_version."]"; ?></title>
<script type="text/javascript" src="/includes/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>

<script type="text/javascript" src="js/monitor.js"></script>
<link rel="stylesheet" type="text/css" href="css/monitor.css" />
<link rel="stylesheet" type="text/css" href="css/firmware.css" />

<link rel="icon" href="/favicon.ico" type="image/x-icon"> 
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green">

<script type="text/javascript">
	$(document).ready(function(){ init(); });
</script>

<?php if(file_exists("/pineapple/includes/navbar.php")) require('/pineapple/includes/navbar.php'); ?>

<p id="version">v<?php echo $module_version; ?></p>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $module_name; ?> <span id="refresh_text"></span></div>
<div class=sidePanelContent>
<?php
if($is_vnstat_installed)
{
	echo "vnStat";
	echo "&nbsp;<span id=\"vnstat_status\"><font color=\"lime\"><strong>installed</strong></font></span><br />";
}
else
{
	echo "vnStat";
	echo "&nbsp;<span id=\"vnstat_status\"><font color=\"red\"><strong>not installed</strong></font></span><br />";
}
?>
<?php
if($is_vnstati_installed)
{
	echo "vnStati";
	echo "&nbsp;<span id=\"vnstati_status\"><font color=\"lime\"><strong>installed</strong></font></span><br />";
}
else
{
	echo "vnStati";
	echo "&nbsp;<span id=\"vnstati_status\"><font color=\"red\"><strong>not installed</strong></font></span><br />";
}
?>
<?php
if($is_vnstat_installed && $is_vnstati_installed)
{
	if($is_vnstat_daemon_installed)
	{
		echo "cron <span id=\"vnstatdi_status\"><font color=\"lime\"><strong>installed</strong></font></span>";
		echo " | <a id=\"vnstatdi_link\" href=\"javascript:daemon_toggle('disable');\"><strong>Uninstall</strong></a>";
		echo " | <a href=\"/index.php?jobs\"><b>Edit</b></a><br />";
	}
	else
	{
		echo "cron <span id=\"vnstatdi_status\"><font color=\"red\"><strong>not installed</strong></font></span>";
		echo " | <a id=\"vnstatdi_link\" href=\"javascript:daemon_toggle('enable');\"><strong>Install</strong></a>";
		echo " | <a href=\"/index.php?jobs\"><b>Edit</b></a><br />";
	}
	
	if($daemon_update != "")
		echo "Last cron update: <font color=\"lime\"><strong>".$daemon_update."</strong></font><br />";
	else
		echo "Last cron update: <font color=\"red\"><strong>N/A</strong></font><br />";
			
	if($is_db_usb)
	{
		echo "DB <span id=\"db_status\"><font color=\"lime\"><strong>persistent</strong></font></span>";
		echo " | <a id=\"db_link\" href=\"javascript:usb_toggle('disable');\"><strong>Uninstall from USB</strong></a><br />";
	}
	else
	{
		echo "DB <span id=\"db_status\"><font color=\"red\"><strong>not persistent</strong></font></span>";
		echo " | <a id=\"db_link\" href=\"javascript:usb_toggle('enable');\"><strong>Install on USB</strong></a><br />";
	}
}
?>
</div>
</div>

[<a id="refresh" href="javascript:refresh();"><strong>Refresh</strong></a>] [<a id="refresh" href="javascript:force();"><strong>Force</strong></a>] [<a id="reset" href="javascript:reset();"><strong>Reset</strong></a>]<br />
<div id="content"></div>
<br />
Auto-refresh <select id="auto_time">
	<option value="1000">1 sec</option>
	<option value="5000">5 sec</option>
	<option value="10000">10 sec</option>
	<option value="15000">15 sec</option>
	<option value="20000">20 sec</option>
	<option value="25000">25 sec</option>
	<option value="30000">30 sec</option>
	<option value="60000">60 sec</option>
</select> <a id="auto_refresh" href="javascript:void(0);"><font color="red">Off</font></a> <span id="auto_text"></span>
<br /><br />
<strong>Output</strong>
<br /><div id="output">-</div>

</body>
</html>
