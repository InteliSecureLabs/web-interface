<?php

require("jammer_vars.php");

if (isset($_GET['int'])) $interface = $_GET['int'];
if (isset($_GET['mon'])) $monitorInterface = $_GET['mon'];

if (isset($_GET['jammer']))
{
	if (isset($_GET['start']))
	{
		$filename = $settings_path;

		$newdata = "packet=".$packet_conf."\n"."sleep=".$sleep_conf."\n"."interface=".$interface."\n"."monitor=".$monitorInterface;
		$newdata = ereg_replace(13,  "", $newdata);
		$fw = fopen($filename, 'w');
		$fb = fwrite($fw,stripslashes($newdata));
		fclose($fw);
		
		$cmd = "echo ".$module_path."start_jammer.sh | at now";
	}
	if (isset($_GET['stop']))
	{
		$cmd = "echo ".$module_path."stop_jammer.sh | at now";
	}
}

if (isset($_GET['monitor']))
{
	if (isset($_GET['start'])) 
		$cmd = "airmon-ng start ".$interface." &";	
	if (isset($_GET['stop']))
		$cmd = "airmon-ng stop ".$monitorInterface." &";
	if (isset($_GET['name']))
		$cmd = "iwconfig 2> /dev/null | grep \"Mode:Monitor\" | awk '{print $1}' | head -1";	
}

if (isset($_GET['interface']))
{
	if (isset($_GET['start'])) 
		$cmd = "ifconfig ".$interface." up &";
	if (isset($_GET['stop']))
		$cmd = "ifconfig ".$interface." down &";
	if (isset($_GET['name']))
		$cmd = "iwconfig 2> /dev/null | awk '{print $1}' | head -1";
}

if (isset($_GET['auto']))
{
	$isUP = exec("ifconfig ".$interface." | grep UP | awk '{print $1}'");
	
	if ($isUP == "UP")
		$cmd = "ifconfig ".$interface." down && ifconfig ".$interface." up &";
	else
		$cmd = "ifconfig ".$interface." up && ifconfig ".$interface." down &";
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
				exec("echo ".$module_path."start_jammer.sh >> /etc/rc.local");
				exec("echo exit 0 >> /etc/rc.local");
			break;
			
			case 'disable': 
				exec("sed -i '/start_jammer.sh/d' /etc/rc.local");
			break;
		}
	}	
}

if($cmd != "")
{
	$output = shell_exec($cmd);
	echo trim($output);	
}

?>