<?php

require("sslstrip_vars.php");

?>
<html>
<head>
<title>Pineapple Control Center - <?php echo $module_name." [v".$module_version."]"; ?></title>
<script type="text/javascript" src="/includes/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>
<script type="text/javascript" src="js/jquery.base64.min.js"></script>

<script type="text/javascript" src="js/sslstrip.js"></script>
<link rel="stylesheet" type="text/css" href="css/sslstrip.css" />
<link rel="stylesheet" type="text/css" href="css/firmware.css" />

<link rel="icon" href="/favicon.ico" type="image/x-icon"> 
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green">

<script type="text/javascript" charset="utf-8">
	$(document).ready( function () { init(); <?php if ($is_sslstrip_running) echo "refresh();"; ?> });
</script>

<?php if(file_exists("../includes/navbar.php")) require('/opt/pwnpad/web-interface/includes/navbar.php'); ?>

<p id="version">v<?php echo $module_version; ?></p>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $module_name; ?> <span id="refresh_text"></span></div>
<div class=sidePanelContent>
<?php
if($is_sslstrip_installed)
{
	echo "sslstrip";
	echo "&nbsp;<font color=\"lime\"><strong>installed</strong></font><br />";

	if ($is_sslstrip_running)
	{
		echo "sslstrip <span id=\"sslstrip_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"sslstrip_link\" href=\"javascript:sslstrip_toggle('stop');\"><strong>Stop</strong></a> ";
		if($is_verbose)
			echo '<input type="checkbox" checked="checked" disabled="disabled" id="verbose" name="verbose" value="verbose" /> Verbose<br /><br />';
		else
			echo '<input type="checkbox" disabled="disabled" id="verbose" name="verbose" value="verbose" /> Verbose<br /><br />';
	}
	else
	{ 
		echo "sslstrip <span id=\"sslstrip_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"sslstrip_link\" href=\"javascript:sslstrip_toggle('start');\"><strong>Start</strong></a> "; 
		if($is_verbose)
			echo '<input type="checkbox" checked="checked" id="verbose" name="verbose" value="verbose" /> Verbose<br /><br />';
		else
			echo '<input type="checkbox" id="verbose" name="verbose" value="verbose" /> Verbose<br /><br />';
	}

	if ($is_sslstrip_onboot) 
	{
		echo "Autostart <span id=\"boot_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"boot_link\" href=\"javascript:boot_toggle('disable');\"><strong>Disable</strong></a><br />";
	}
	else 
	{ 
		echo "Autostart <span id=\"boot_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"boot_link\" href=\"javascript:boot_toggle('enable');\"><strong>Enable</strong></a><br />"; 
	}
}
else
{
	echo "sslstrip";
	echo "&nbsp;<font color=\"red\"><strong>not installed</strong></font><br /><br />";
	
	echo "Install to <a id=\"install_int\" href=\"javascript:install('internal');\">Internal Storage</a> or <a id=\"install_usb\" href=\"javascript:install('usb');\">USB Storage</a>";
		
	exit();	
}
?>
</div>
</div>

<div id="tabs" class="tab">
	<ul>
		<li><a id="Output_link" class="selected" href="#Output">Output</a></li>
		<li><a id="History_link" href="#History">History</a></li>
		<li><a id="Custom_link" href="#Custom">Custom</a></li>
		<li><a id="Configuration_link" href="#Conf">Configuration</a></li>
	</ul>

<div id="Output">
	[<a id="refresh" href="javascript:refresh();">Refresh</a>] Filter <input type="text" id="filter" name="filter" value="" size="90"> <em>Piped commands used to filter output (e.g. grep, awk)</em><br /><br />
	<textarea id='output' name='output' cols='85' rows='29'></textarea>
</div>

<div id="History">
	[<a id="refresh" href="javascript:refresh_history();">Refresh</a>]<br />
	<div id="content_history"></div>
</div>

<div id="Custom">
	[<a id="refresh" href="javascript:refresh_custom();">Refresh</a>]<br />
	<div id="content_custom"></div>
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
