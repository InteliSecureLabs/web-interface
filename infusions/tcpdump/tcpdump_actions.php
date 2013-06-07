<?php

require("tcpdump_vars.php");

if (isset($_GET['scan']))
{
	if (isset($_GET['cmd']))
	{
		$time = time();
		$full_cmd = stripslashes($_GET['cmd']) . " -w ".$module_path."dumps/dump_".$time.".pcap 2> ".$module_path."dumps/capture.log";
		
		shell_exec("echo \"#!/bin/sh\n".$full_cmd." &\" > ".$module_path."tcpdump.sh && chmod +x ".$module_path."tcpdump.sh &");
		$cmd = "echo ".$module_path."tcpdump.sh | at now";
	}
}

if (isset($_GET['cancel']))
{
	$cmd = "killall tcpdump &";
}

if (isset($_GET['load']))
{
	if (isset($_GET['file']))
	{
		echo file_get_contents($module_path."dumps/".$_GET['file']);
	}
}

if (isset($_GET['delete']))
{
	if (isset($_GET['file']))
	{
		$cmd = "rm -rf ".$module_path."dumps/".$_GET['file'];
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
				$cmd = "opkg update && opkg install tcpdump --dest usb"; 
			break;
			
			case 'internal': 
				$cmd = "opkg update && opkg install tcpdump"; 
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