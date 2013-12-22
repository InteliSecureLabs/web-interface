<?php

require("logcheck_vars.php");

if (isset($_GET['daemon']))
{
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
		
		switch($action)
		{
			case 'enable': $cmd = "echo \"\n".$cron_time." ".$cron_task."\" >> /etc/crontabs/root && sed -i '/^$/d' /etc/crontabs/root && /etc/init.d/cron restart"; break;
			case 'disable': $cmd = "sed -i '/logcheck_report.sh/d' /etc/crontabs/root && sed -i '/^$/d' /etc/crontabs/root && /etc/init.d/cron restart"; break;
		}
	}
}

if (isset($_GET['logcheck']))
{
	if (isset($_GET['start']))
	{
		$full_cmd = "logread -f >> ".$module_path."events";
		
		shell_exec("echo \"#!/bin/sh\n".$full_cmd." &\" > ".$module_path."logcheck.sh && chmod +x ".$module_path."logcheck.sh &");
		$cmd = "echo ".$module_path."logcheck.sh | at now";
	}
	if (isset($_GET['stop']))
	{
		$cmd = "killall logread";
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
				exec("echo ".$module_path."logcheck.sh >> /etc/rc.local");
				exec("echo exit 0 >> /etc/rc.local");
			break;
			
			case 'disable': 
				exec("sed -i '/logcheck.sh/d' /etc/rc.local");
			break;
		}
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
				$cmd = "opkg update && opkg install ssmtp --dest usb"; 
			break;
			
			case 'internal': 
				$cmd = "opkg update && opkg install ssmtp"; 
			break;
		}
	}
}

if (isset($_GET['test_email']))
{
	$body = "To: ".$To."\n";
	$body .= "From: ".$From."\n";
	$body .= "Subject: ".$Subject."\n";
	$body .= "\n\n";
	$body .= "[Test]\n";
	
	exec("echo -e '".$body."' > ".$module_path."mail_test.tmp");
	
	exec("ssmtp -t < ".$module_path."mail_test.tmp");
	exec("rm -rf ".$module_path."mail_test.tmp");
}

if($cmd != "")
{
	$output = shell_exec($cmd);
	echo trim($output);	
}

?>