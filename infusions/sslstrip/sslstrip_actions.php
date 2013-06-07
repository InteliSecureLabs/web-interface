<?php

require("sslstrip_vars.php");

if (isset($_GET['sslstrip']))
{
	if($is_sslstrip_installed)
	{
		if (isset($_GET['start']))
		{
			if (isset($_GET['verbose'])) $verbose = 1; else $verbose = 0;
		
			exec("iptables -t nat -A PREROUTING -p tcp --destination-port 80 -j REDIRECT --to-ports 10000");
			//exec("iptables -t nat -A PREROUTING -p tcp --destination-port 443 -j REDIRECT --to-ports 10000");
		
			$time = time();
		
			if($verbose)
				$full_cmd = "sslstrip -a -k -f -w ".$module_path."log/output_".$time.".log 2>&1";
			else
				$full_cmd = "sslstrip -k -f -w ".$module_path."log/output_".$time.".log 2>&1";

			shell_exec("echo \"#!/bin/sh\n".$full_cmd." &\" > ".$module_path."sslstrip.sh && chmod +x ".$module_path."sslstrip.sh &");
			$cmd = "echo ".$module_path."sslstrip.sh | at now";
		}
	
		if (isset($_GET['stop']))
		{
			$rule_http_number = exec("iptables -t nat --line-numbers -n -L | grep 80 | grep 10000 | awk {'print $1'}") != "" ? 1 : 0;
			exec("iptables -t nat -D PREROUTING ".$rule_http_number);
			$rule_https_number = exec("iptables -t nat --line-numbers -n -L | grep 443 | grep 10000 | awk {'print $1'}") != "" ? 1 : 0;
			exec("iptables -t nat -D PREROUTING ".$rule_https_number);
			
			$cmd = "kill `ps -ax | grep sslstrip | grep -v -e grep | grep -v -e php | awk {'print $1'}`";
		}
	}
	else
	{
		echo "sslstrip is not installed...";
	}
}

if (isset($_GET['load']))
{
	if (isset($_GET['file']))
	{
		if (isset($_GET['log']))
		{
			$log_date = date ("F d Y H:i:s", filemtime($module_path."log/".$_GET['file']));
			echo "sslstrip ".$_GET['file']." [".$log_date."]\n";
			echo file_get_contents($module_path."log/".$_GET['file']);
		}
		else if (isset($_GET['custom']))
		{
			$log_date = date ("F d Y H:i:s", filemtime($module_path."custom/".$_GET['file']));
			echo "Custom script ".$_GET['file']." [".$log_date."]\n";
			echo file_get_contents($module_path."custom/".$_GET['file']);
		}
	}
}

if (isset($_GET['delete']))
{
	if (isset($_GET['file']))
	{
		if (isset($_GET['log']))
			$cmd = "rm -rf ".$module_path."log/".$_GET['file']."*";
		else if (isset($_GET['custom']))
			$cmd = "rm -rf ".$module_path."custom/".$_GET['file']."*";
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
				$cmd = "opkg update && opkg install sslstrip --dest usb"; 
			break;
			
			case 'internal': 
				$cmd = "opkg update && opkg install sslstrip"; 
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
			exec("sed -i '/sslstrip\/autostart.sh/d' /etc/rc.local");
			break;
		}
	}	
}

if (isset($_GET['execute']))
{
	if (isset($_GET['cmd']))
	{	
		$time = time(); $cmd = stripslashes(base64_decode($_GET['cmd']));
		$full_cmd = "(".$cmd.") &> ".$module_path."custom/output_".$time.".log &";
		
		$filename = $module_path."custom.sh";
		
		$newdata = "#!/bin/sh\n".$full_cmd;
		$newdata = ereg_replace(13,  "", $newdata);
		$fw = fopen($filename, 'w+');
		$fb = fwrite($fw,stripslashes($newdata));
		fclose($fw);
		
		shell_exec("chmod +x ".$module_path."custom.sh &");
		$cmd = "echo ".$module_path."custom.sh | at now";
	}
}

if($cmd != "")
{
	$output = shell_exec($cmd);
	
	if($output != "")
		echo trim($output);	
}

?>