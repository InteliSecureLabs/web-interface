<?php

require("urlsnarf_vars.php");

if (isset($_GET['urlsnarf']))
{
	if (isset($_GET['start']))
	{		
		
		if (isset($_GET['int'])) $interface_run = $_GET['int']; else $interface_run = 'br-lan';
		
		exec("echo \"".$interface_run."\" > ".$module_path."urlsnarf.run");
		
		$time = time();
		$full_cmd = "urlsnarf -i ".$interface_run." > ".$module_path."log/output_".$time.".log";

		shell_exec("echo \"#!/bin/sh\n".$full_cmd."\" > ".$module_path."urlsnarf.sh && chmod +x ".$module_path."urlsnarf.sh &");
		$cmd = "echo ".$module_path."urlsnarf.sh | at now";
	}
	if (isset($_GET['stop']))
	{
		$cmd = "kill `ps -ax | grep urlsnarf | grep -v -e grep | grep -v -e php | awk {'print $1'}`";
		
		exec("echo \"\" > ".$module_path."urlsnarf.run");
	}
}

if (isset($_GET['load']))
{
	if (isset($_GET['file']))
	{
		if (isset($_GET['log']))
		{
			$log_date = date ("F d Y H:i:s", filemtime($module_path."log/".$_GET['file']));
			echo "urlsnarf ".$_GET['file']." [".$log_date."]\n";
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
				exec("sed -i '/urlsnarf\/autostart.sh/d' /etc/rc.local");
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