<?php

require("jammer_vars.php");

?>
<html>
<head>
<title>Pineapple Control Center - <?php echo $module_name." [v".$module_version."]"; ?></title>
<script type="text/javascript" src="/includes/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>

<script type="text/javascript" src="js/jammer.js"></script>
<link rel="stylesheet" type="text/css" href="css/jammer.css" />
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

echo "WLAN interface ";
echo "<span id=\"interfaces_l\">";
echo '<select id="interfaces" name="interfaces">';
foreach($interfaces as $value) 
{ 
	if($interface_conf == $value)
		echo '<option selected value="'.$value.'">'.$value.'</option>'; 
	else
		echo '<option value="'.$value.'">'.$value.'</option>'; 
}
echo '</select>';
echo "</span>";
echo "&nbsp;| <a id=\"interface\" href=\"javascript:interface_toggle('start');\"><strong>Start</strong></a> - <a id=\"interface\" href=\"javascript:interface_toggle('stop');\"><strong>Stop</strong></a> [<a id=\"auto_interface\" href=\"javascript:auto_toggle();\"><strong>Auto</strong></a>] | <a id=\"monitorInterface\" href=\"javascript:monitor_toggle('start');\"><strong>Start Monitor</strong></a><br />";

echo "Monitor interface ";
echo "<span id=\"monitorInterface_l\">";
echo '<select id="monitorInterfaces" name="monitorInterfaces">';
foreach($monitorInterfaces as $value) 
{ 
	if($monitor_conf == $value)
		echo '<option selected value="'.$value.'">'.$value.'</option>'; 
	else
		echo '<option value="'.$value.'">'.$value.'</option>'; 
}
echo '</select>';
echo "</span>";
echo "&nbsp;|  <a id=\"monitorInterface\" href=\"javascript:monitor_toggle('stop');\"><strong>Stop Monitor</strong></a><br /><br />";

if ($is_jammer_running)
{
	echo "WiFi Jammer <span id=\"jammer_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
	echo " | <a id=\"jammer_link\" href=\"javascript:jammer_toggle('stop');\"><strong>Stop</strong></a><br />";
}
else 
{ 
	echo "WiFi Jammer <span id=\"jammer_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
	echo " | <a id=\"jammer_link\" href=\"javascript:jammer_toggle('start');\"><strong>Start</strong></a><br />"; 
}

if ($is_jammer_onboot) 
{
	echo "Autostart <span id=\"boot_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
	echo " | <a id=\"boot_link\" href=\"javascript:boot_toggle('disable');\"><strong>Disable</strong></a><br />";
}
else 
{ 
	echo "Autostart <span id=\"boot_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
	echo " | <a id=\"boot_link\" href=\"javascript:boot_toggle('enable');\"><strong>Enable</strong></a><br />"; 
}
?>
</div>
</div>

<div id="tabs" class="tab">
	<ul>
		<li><a id="Output_link" class="selected" href="#Output">Output</a></li>
		<li><a id="Whitelist_link" href="#Whitelist">Whitelist</a></li>
		<li><a id="Blacklist_link" href="#Blacklist">Blacklist</a></li>
		<li><a id="Configuration_link" href="#Conf">Configuration</a></li>
	</ul>

<div id="Output">
	[<a id="refresh" href="javascript:refresh();">Refresh</a>]<br /><br />
	<textarea id='output' name='output' cols='85' rows='29'></textarea>
</div>

<div id="Whitelist">
	<div class="my_wrapper">
		<div class="my_left_box">
		    <fieldset id="list_fieldset">
			<legend>Available AP (<a id="refresh" href="javascript:refresh_available_ap('whitelist');">Refresh</a>)</legend>
			<ul id="list_whitelist">
			</ul>
			<em>Click to add to whitelist</em>
			</fieldset>
		</div>

		<div class="my_right_box">
			<fieldset>
			<legend><strong>Whitelist</strong> (<a href="javascript:update_conf($('#whitelist').val(), 'whitelist');">Save</a>) (<a href="javascript:$('#whitelist').val(''); void(0);">Clear</a>)</legend>
		    <textarea id='whitelist' name='whitelist' cols='85' rows='29'><?php echo file_get_contents($whitelist_path); ?></textarea>
			</fieldset>
		</div>
	</div>
	<div style="clear:both; margin:0; padding:0;"></div>
	<em>Note: APs on the whitelist are not DeAuth'ed.</em>
</div>

<div id="Blacklist">
	<div class="my_wrapper">
		<div class="my_left_box">
		    <fieldset id="list_fieldset">
			<legend>Available AP (<a id="refresh" href="javascript:refresh_available_ap('blacklist');">Refresh</a>)</legend>
			<ul id="list_blacklist">
			</ul>
			<em>Click to add to blacklist</em>
			</fieldset>
		</div>

		<div class="my_right_box">
			<fieldset>
			<legend><strong>Blacklist</strong> (<a href="javascript:update_conf($('#blacklist').val(), 'blacklist');">Save</a>) (<a href="javascript:$('#blacklist').val(''); void(0);">Clear</a>)</legend>
		    <textarea id='blacklist' name='blacklist' cols='85' rows='29'><?php echo file_get_contents($blacklist_path); ?></textarea>
			</fieldset>
		</div>
	</div>
	<div style="clear:both; margin:0; padding:0;"></div>
	<em>Note: APs on the blacklist are DeAuth'ed.</em>
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
