<?php

require("dnsspoof_vars.php");

if (isset($_GET['dnsspoof']))
{
	if (isset($_GET['start']))
	{		
		$time = time();
		$full_cmd = "dnsspoof -i br-lan -f ".$hosts_path." > /dev/null 2> ".$module_path."log/output_".$time.".log";

		shell_exec("echo \"#!/bin/sh\n".$full_cmd."\" > ".$module_path."dnsspoof.sh && chmod +x ".$module_path."dnsspoof.sh &");
		$cmd = "echo ".$module_path."dnsspoof.sh | at now";
	}
	if (isset($_GET['stop']))
	{
		$cmd = "kill `ps -ax | grep dnsspoof | grep -v -e grep | grep -v -e php | awk {'print $1'}`";
	}
}

if (isset($_GET['load']))
{
	if (isset($_GET['file']))
	{
		$log_date = date ("F d Y H:i:s", filemtime($module_path."log/".$_GET['file']));
		echo "dnsspoof ".$_GET['file']." [".$log_date."]\n";

		$cmd = "cat ".$module_path."log/".$_GET['file'];
	}
}

if (isset($_GET['delete']))
{
	if (isset($_GET['file']))
	{
		if (isset($_GET['log']))
			$cmd = "rm -rf ".$module_path."log/".$_GET['file']."*";
	}
}

if (isset($_GET['fake']))
{
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
		
		switch($action)
		{
			case 'install':
				$cmd = "cp ".$module_path."fake/ncsi.txt /www/ && mkdir -p /www/library/test/ && cp ".$module_path."fake/success.html /www/library/test/"; 
			break;
			
			case 'uninstall':
				$cmd = "rm -rf /www/ncsi.txt && rm -rf /www/library "; 
			break;
		}
	}
}

if (isset($_GET['boot']))
{
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
		
		switch($action)
		{
			case 'enable':
				exec("sed -i '/exit 0/d' /etc/rc.local"); 
				exec("echo ".$module_path."autostart.sh >> /etc/rc.local");
				exec("echo exit 0 >> /etc/rc.local");
			break;
			
			case 'disable': 
				exec("sed -i '/dnsspoof\/autostart.sh/d' /etc/rc.local");
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