<?php

require("jammer_vars.php");
require("iwlist_parser.php");

if (isset($_GET[int])) $interface = $_GET[int];
if (isset($_GET[mon])) $monitorInterface = $_GET[mon];

if(isset($_GET['available_ap']))
{
	$iwlistparse = new iwlist_parser();
	$p = $iwlistparse->parseScanDev($interface);
	
	if(count($p[$interface]) == 0) echo "<em>No AP...</em>";
	
	for($i=1;$i<=count($p[$interface]);$i++)
	{
		echo '<li name="'.$p[$interface][$i]["ESSID"].'" address="'.$p[$interface][$i]["Address"].'">'.$p[$interface][$i]["ESSID"].' - '.$p[$interface][$i]["Address"].'</li>';
	}
}

if(isset($_GET['log']))
{
	if($is_jammer_running)
	{
		if(file_exists($module_path."log")) echo file_get_contents($module_path."log");
	}
	else
	{
		echo "WiFi Jammer is not running...";
	}
}
?>