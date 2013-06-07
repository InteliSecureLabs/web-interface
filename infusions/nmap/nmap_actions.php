<?php

require("nmap_vars.php");

if (isset($_GET['scan']))
{
	if (isset($_GET['cmd']))
	{
		$time = time();
		$full_cmd = stripslashes($_GET['cmd']) . " -oN ".$module_path."scans/tmp 2>&1";
		
		shell_exec("echo \"#!/bin/sh\n".$full_cmd." && mv ".$module_path."scans/tmp ".$module_path."scans/scan_".$time."\" > ".$module_path."nmap.sh && chmod +x ".$module_path."nmap.sh &");
		$cmd = "echo ".$module_path."nmap.sh | at now";
	}
}

if (isset($_GET['cancel']))
{
	$cmd = "killall nmap &";
}

if (isset($_GET['load']))
{
	if (isset($_GET['file']))
	{
		echo file_get_contents($module_path."scans/".$_GET['file']);
	}
}

if (isset($_GET['delete']))
{
	if (isset($_GET['file']))
	{
		$cmd = "rm -rf ".$module_path."scans/".$_GET['file'];
	}
}

if (isset($_GET['install'])) 
{
	if (isset($_GET['where']))
	{
		$where = $_GET['where'];
		
		switch($where)
		{
			case 'usb': 
				$cmd = "opkg update && opkg install nmap --dest usb"; 
			break;
			
			case 'internal': 
				$cmd = "opkg update && opkg install nmap"; 
			break;
		}
	}
}

if($cmd != "")
{
	$output = shell_exec($cmd);

	if($output != "")
		echo trim($output);
}

?>