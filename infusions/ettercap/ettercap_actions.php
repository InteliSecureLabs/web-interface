<?php

require("ettercap_vars.php");

if (isset($_GET['launch']))
{
	if (isset($_GET['cmd']))
	{
		$time = time();
		$full_cmd = stripslashes($_GET['cmd']) . " -w ".$module_path."log/log_".$time.".pcap > ".$module_path."log/log_".$time.".log";
		
		shell_exec("echo \"#!/bin/sh\n".$full_cmd." &\" > ".$module_path."ettercap.sh && chmod +x ".$module_path."ettercap.sh &");
		$cmd = "echo ".$module_path."ettercap.sh | at now";
	}
}

if (isset($_GET['cancel']))
{
	$cmd = "killall -9 ettercap &";
}

if (isset($_GET['load']))
{
	if (isset($_GET['file']))
	{
		$log_date = date ("F d Y H:i:s", filemtime($module_path."log/".$_GET[file]));
		echo "ettercap ".$_GET['file']." [".$log_date."]\n";
		echo file_get_contents($module_path."log/".$_GET['file']);
	}
}

if (isset($_GET['delete']))
{
	if (isset($_GET['file']))
	{
		$cmd = "rm -rf ".$module_path."log/".$_GET['file']."*";
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
				$cmd = "opkg update && opkg install ettercap --dest usb"; 
			break;
			
			case 'internal': 
				$cmd = "opkg update && opkg install ettercap"; 
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