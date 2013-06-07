<?php

require("dnsspoof_vars.php");

?>
<html>
<head>
<title>Pineapple Control Center - <?php echo $module_name." [v".$module_version."]"; ?></title>
<script type="text/javascript" src="/includes/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>

<script type="text/javascript" src="js/dnsspoof.js"></script>
<link rel="stylesheet" type="text/css" href="css/dnsspoof.css" />
<link rel="stylesheet" type="text/css" href="css/firmware.css" />

<link rel="icon" href="/favicon.ico" type="image/x-icon"> 
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green">

<script type="text/javascript" charset="utf-8">
	$(document).ready( function () { init(); <?php if ($is_dnsspoof_running) echo "refresh();"; ?> });	
</script>


<?php if(file_exists("/pineapple/includes/navbar.php")) require('/pineapple/includes/navbar.php'); ?>

<p id="version">v<?php echo $module_version; ?></p>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $module_name; ?> <span id="refresh_text"></span></div>
<div class=sidePanelContent>
<?php
	if($is_dnsspoof_installed)
	{
		echo "dnsspoof";
		echo "&nbsp;<font color=\"lime\"><strong>installed</strong></font><br />";
	}
	else
	{
		echo "dnsspoof";
		echo "&nbsp;<font color=\"red\"><strong>not installed</strong></font><br />";
	}

	if ($is_dnsspoof_running) 
	{
		echo "dnsspoof <span id=\"dnsspoof_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"dnsspoof_link\" href=\"javascript:dnsspoof_toggle('stop');\"><strong>Stop</strong></a><br />";
	}
	else
	{ 
		echo "dnsspoof <span id=\"dnsspoof_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"dnsspoof_link\" href=\"javascript:dnsspoof_toggle('start');\"><strong>Start</strong></a><br />"; 
	}

	if ($is_dnsspoof_onboot) 
	{
		echo "Autostart <span id=\"boot_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"boot_link\" href=\"javascript:boot_toggle('disable');\"><strong>Disable</strong></a><br />";
	}
	else 
	{ 
		echo "Autostart <span id=\"boot_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"boot_link\" href=\"javascript:boot_toggle('enable');\"><strong>Enable</strong></a><br />"; 
	}

	if($fake_files_installed)
	{
		echo "Fake captive portal files <span id=\"fake_status\"><font color=\"lime\"><strong>installed</strong></font></span>";
		echo " | <a id=\"fake_link\" href=\"javascript:fake_toggle('uninstall');\"><strong>Uninstall</strong></a><br />";
	}
	else
	{
		echo "Fake captive portal files <span id=\"fake_status\"><font color=\"red\"><strong>not installed</strong></font></span>";
		echo " | <a id=\"fake_link\" href=\"javascript:fake_toggle('install');\"><strong>Install</strong></a><br />";
	} 
?>
</div>
</div>

<div id="tabs" class="tab">
	<ul>
		<li><a id="Output_link" class="selected" href="#Output">Output</a></li>
		<li><a id="History_link" href="#History">History</a></li>
		<li><a id="Hosts_link" href="#Hosts">Hosts</a></li>
	</ul>

<div id="Output">
	[<a id="refresh" href="javascript:refresh();">Refresh</a>]<br /><br />
	<textarea id='output' name='output' cols='85' rows='29'></textarea>
</div>

<div id="History">
	[<a id="refresh" href="javascript:refresh_history();">Refresh</a>]<br />
	<div id="content_history"></div>
</div>

<div id="Hosts">
	[<a href="javascript:update_conf($('#hosts').val(), 'hosts');">Save</a>]<br /><br />
	<?php
		echo "<textarea id='hosts' name='hosts' cols='85' rows='29'>"; echo file_get_contents($hosts_path); echo "</textarea>";
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
