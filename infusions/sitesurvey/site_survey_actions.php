<?php

require("site_survey_vars.php");

if (isset($_GET['int'])) $interface = $_GET['int'];
if (isset($_GET['mon'])) $monitorInterface = $_GET['mon'];

if (isset($_GET['monitor']))
{
	if (isset($_GET['start'])) 
		$cmd = "airmon-ng start ".$interface." &";	
	if (isset($_GET['stop']))
		$cmd = "airmon-ng stop ".$monitorInterface." &";	
}

if (isset($_GET['interface'])) 
{
	if (isset($_GET['start'])) 
		$cmd = "ifconfig ".$interface." up &";
	if (isset($_GET['stop']))
		$cmd = "ifconfig ".$interface." down &";
}

if (isset($_GET['auto']))
{
	$isUP = exec("ifconfig ".$interface." | grep UP | awk '{print $1}'");
	
	if ($isUP == "UP")
		$cmd = "ifconfig ".$interface." down && ifconfig ".$interface." up &";
	else
		$cmd = "ifconfig ".$interface." up && ifconfig ".$interface." down &";
}

if (isset($_GET['delete']))
{
	if (isset($_GET['file']))
	{
		if (isset($_GET['log']))
			$cmd = "rm -rf ".$module_path."log/".$_GET['file']."*";
		if (isset($_GET['cap']))
			$cmd = "rm -rf ".$module_path."captures/".$_GET['file']."*";
	}
}

if (isset($_GET['execute']))
{
	if (isset($_GET['cmd']))
	{	
		$time = time(); $cmd = stripslashes(base64_decode($_GET['cmd']));
		$full_cmd = "(".$cmd.") &> ".$module_path."log/output_".$time.".log";
		
		shell_exec("echo \"#!/bin/sh\n".$full_cmd." &\" > ".$module_path."custom.sh && chmod +x ".$module_path."custom.sh &");
		$cmd = "echo ".$module_path."custom.sh | at now";
	}
}

if (isset($_GET['cancel']))
{
	$cmd = "killall custom.sh &";	
}

if (isset($_GET['background_refresh']))
{
	if ($_GET['background_refresh'] == "start")
	{
		$full_cmd = "airodump-ng --write $dumpPath $monitorInterface &> /dev/null &";
		
		shell_exec("rm -rf ".$dumpPath."-01*");
		shell_exec("killall airodump-ng 2> /dev/null");

		shell_exec("echo \"#!/bin/sh\n".$full_cmd."\" > ".$module_path."refresh.sh && chmod +x ".$module_path."refresh.sh &");
		$cmd = "echo ".$module_path."refresh.sh | at now";
	}
	else if ($_GET['background_refresh'] == "stop")
	{
		$cmd = "killall airodump-ng 2> /dev/null";
		shell_exec("rm -rf ".$dumpPath."-01*");
		shell_exec("killall airodump-ng 2> /dev/null");
	}
}

if (isset($_GET['load']))
{
	if (isset($_GET['file']))
	{
		$log_date = date ("F d Y H:i:s", filemtime($module_path."log/".$_GET['file']));
		echo "Custom script ".$_GET['file']." [".$log_date."]\n";
		echo file_get_contents($module_path."log/".$_GET['file']);
	}
}

if($cmd != "")
{
	$output = shell_exec($cmd);
	
	echo trim($output);
}

?>