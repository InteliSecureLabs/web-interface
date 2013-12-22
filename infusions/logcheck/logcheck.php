<?php

require("logcheck_vars.php");

?>
<html>
<head>
<title>Pineapple Control Center - <?php echo $module_name." [v".$module_version."]"; ?></title>
<script type="text/javascript" src="/includes/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>

<script type="text/javascript" src="js/logcheck.js"></script>
<link rel="stylesheet" type="text/css" href="css/logcheck.css" />
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
if($is_ssmtp_installed)
{
	echo "ssmtp";
	echo "&nbsp;<font color=\"lime\"><strong>installed</strong></font><br />";
	
	if ($is_logcheck_running) 
	{
		echo "Logcheck <span id=\"logcheck_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"logcheck_link\" href=\"javascript:logcheck_toggle('stop');\"><strong>Stop</strong></a><br />";
	}
	else 
	{ 
		echo "Logcheck <span id=\"logcheck_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"logcheck_link\" href=\"javascript:logcheck_toggle('start');\"><strong>Start</strong></a><br />"; 
	}

	if ($is_logcheck_onboot) 
	{
		echo "Autostart <span id=\"boot_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"boot_link\" href=\"javascript:boot_toggle('disable');\"><strong>Disable</strong></a><br />";
	}
	else 
	{ 
		echo "Autostart <span id=\"boot_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"boot_link\" href=\"javascript:boot_toggle('enable');\"><strong>Enable</strong></a><br />"; 
	}

	if($is_daemon_installed)
	{
		echo "cron <span id=\"cron_status\"><font color=\"lime\"><strong>installed</strong></font></span>";
		echo " | <a id=\"cron_link\" href=\"javascript:daemon_toggle('disable');\"><strong>Uninstall</strong></a>";
		echo " | <a href=\"/index.php?jobs\"><b>Edit</b></a><br />";
	}
	else
	{
		echo "cron <span id=\"cron_status\"><font color=\"red\"><strong>not installed</strong></font></span>";
		echo " | <a id=\"cron_link\" href=\"javascript:daemon_toggle('enable');\"><strong>Install</strong></a>";
		echo " | <a href=\"/index.php?jobs\"><b>Edit</b></a><br />";
	}
	if($daemon_update != "")
		echo "Last cron update: <font color=\"lime\"><strong>".$daemon_update."</strong></font><br />";
	else
		echo "Last cron update: <font color=\"red\"><strong>N/A</strong></font><br />";
}
else
{
	echo "ssmtp";
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
		<li><a id="Rules_link" href="#Rules">Rules</a></li>
		<li><a id="Rules_link" href="#Custom">Custom</a></li>
		<li><a id="Email_link" href="#Email">Email</a></li>
	</ul>

<div id="Output">
	[<a id="refresh" href="javascript:refresh();">Refresh</a>]<br /><br />
	<textarea id='output' name='output' cols='85' rows='29'></textarea>
</div>
	
<div id="Rules">
	<strong>Matching Rules</strong> [<a href="javascript:update_conf($('#match').val(), 'match');">Save</a>]<br /><br />
	<textarea id='match' name='match' cols='85' rows='29'><?php echo file_get_contents($match_path); ?></textarea><br /><br />
	<strong>Ignore Rules</strong> [<a href="javascript:update_conf($('#ignore').val(), 'ignore');">Save</a>]<br /><br />
	<textarea id='ignore' name='ignore' cols='85' rows='29'><?php echo file_get_contents($ignore_path); ?></textarea>
</div>

<div id="Custom">
	<strong>Custom Script</strong> [<a href="javascript:update_conf($('#custom').val(), 'custom');">Save</a>]<br /><br />
	<?php
		echo "<textarea id='custom' name='custom' cols='85' rows='29'>"; if(file_exists($custom_path)) echo file_get_contents($custom_path); echo "</textarea>";
	?>
</div>

<div id="Email">
	<strong>Email Settings</strong> [<a href="javascript:update_settings('email_conf');">Save</a>] [<a href="javascript:test_email();">Test</a>]<br /><br />
	<table class="grid">
	<form id='email_conf'><input type='hidden' name='set_conf' value='email'/>
	<tr><td>To:</td> <td><input type="text" id="to" name="to" value="<?php echo $To; ?>" size="50"></td></tr>
	<tr><td>From:</td> <td><input type="text" id="from" name="from" value="<?php echo $From; ?>" size="50"></td></tr>
	<tr><td>Subject:</td> <td><input type="text" id="subject" name="subject" value="<?php echo $Subject; ?>" size="50"></td></tr>
	</form>
	</table>
	<br />
	<?php
	if($is_ssmtp_installed)
	{
		echo "<strong>SMTP Configuration</strong> (<a href=\"javascript:update_conf($('#smtp').val(), 'smtp');\">Save</a>)<br /><br />";
		echo "<textarea id='smtp' name='smtp' cols='85' rows='29'>"; if(file_exists($smtp_path)) echo file_get_contents($smtp_path); echo "</textarea>";
	}
	else
	{
		echo "<strong>SMTP Configuration</strong><br /><br />";
		echo "<em>ssmtp not installed...</em>";
	}
	?>
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
