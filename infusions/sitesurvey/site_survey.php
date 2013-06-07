<?php

require("site_survey_vars.php");

?>
<html>
<head>
<title>Pineapple Control Center - <?php echo $module_name." [v".$module_version."]"; ?></title>
<script type="text/javascript" src="/includes/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>

<script type="text/javascript" src="js/site_survey.js"></script>
<link rel="stylesheet" type="text/css" href="css/site_survey.css" />
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

echo "WLAN interface ";
echo "<span id=\"interfaces_l\">";
echo '<select id="interfaces" name="interfaces">';
foreach($interfaces as $value) { echo '<option value="'.$value.'">'.$value.'</option>'; }
echo '</select>';
echo "</span>";
echo "&nbsp;| <a id=\"interface\" href=\"javascript:interface_toggle('start');\"><strong>Start</strong></a> - <a id=\"interface\" href=\"javascript:interface_toggle('stop');\"><strong>Stop</strong></a> [<a id=\"auto_interface\" href=\"javascript:auto_toggle();\"><strong>Auto</strong></a>] | <a id=\"monitorInterface\" href=\"javascript:monitor_toggle('start');\"><strong>Start Monitor</strong></a><br />";

echo "Monitor interface ";
echo "<span id=\"monitorInterface_l\">";
echo '<select id="monitorInterfaces" name="monitorInterfaces">';
foreach($monitorInterfaces as $value) { echo '<option value="'.$value.'">'.$value.'</option>'; }
echo '</select>';
echo "</span>";
echo "&nbsp;|  <a id=\"monitorInterface\" href=\"javascript:monitor_toggle('stop');\"><strong>Stop Monitor</strong></a><br />";
?>
</div>
</div>

[<a id="refresh" href="javascript:refresh(0);">Refresh APs</a>] [<a id="clients" href="javascript:refresh(1);">Refresh Clients</a>]<br /><br />
<div id="content"></div><br />
Auto-refresh <select id="auto_time">
	<option value="1000">1 sec</option>
	<option value="5000">5 sec</option>
	<option value="10000">10 sec</option>
	<option value="15000">15 sec</option>
	<option value="20000">20 sec</option>
	<option value="25000">25 sec</option>
	<option value="30000">30 sec</option>
</select> <select id="auto_what">
	<option value="0">APs</option>
	<option value="1">All</option>
</select> <a id="auto_refresh" href="javascript:void(0);"><font color="red">Off</font></a> 

<div id="tabs2" class="tab">
	<ul>
		<li><a id="Output_link" class="selected" href="#Output">Output</a></li>
		<li><a id="Captures_link" href="#Captures">Captures</a></li>
		<li><a id="History_link" href="#History">History</a></li>
		<li><a id="Configuration_link" href="#Conf">Configuration</a></li>
	</ul>
	
<div id="Output">
	<textarea id='output' name='output' cols='85' rows='29'></textarea>
</div>

<div id="Captures">
	[<a id="refresh" href="javascript:refresh_history();">Refresh</a>]<br />
	<div id="content_captures"></div>
</div>

<div id="History">
	[<a id="refresh" href="javascript:refresh_history();">Refresh</a>]<br />
	<div id="content_history"></div>
</div>

<div id="Conf">
	[<a id="config" href="javascript:set_config();">Save</a>]<br />
	<div id="content_conf"></div>
</div>

</div>

</body>
</html>
