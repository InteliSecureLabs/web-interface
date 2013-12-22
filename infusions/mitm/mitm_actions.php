<?php

require("mitm_vars.php");

if (isset($_GET['proxy']))
{
	if (isset($_GET['start']) && isset($_GET['script']))
	{		
		$time = time();
		
		exec("iptables -t nat -A PREROUTING -p tcp --destination-port 80 ! -d 172.16.42.1 -j REDIRECT --to-ports 8080");		
		
		exec("echo \"".$_GET['script']."\" > ".$module_path."mitm.conf");
		
		$cmd = "mitmdump -p 8080 -s ".$module_path."scripts/".$_GET['script']." > ".$module_path."log/log_".$time.".log &";
	}
	
	if (isset($_GET['stop']))
	{
		$rule_http_number = exec("iptables -t nat --line-numbers -n -L | grep 80 | grep 8080 | awk {'print $1'}") != "" ? 1 : 0;
		exec("iptables -t nat -D PREROUTING ".$rule_http_number);
		
		exec("echo \"\" > ".$module_path."mitm.conf");
		
		$cmd = "kill `ps -ax | grep mitmdump | grep -v -e grep | grep -v -e php | awk {'print $1'}`";
	}
}

if (isset($_GET['helpers']))
{
	$what = $_GET['what'];
	
	if (isset($_GET['install']))
	{
		switch($what)
		{
			case 'snowstorm': 
				exec ("cp ".$module_path."helpers/snowstorm.min.js /www/ &"); 
			break;
			
			case 'fool': 
				exec ("cp ".$module_path."helpers/jquery.min.js /www/ &"); 
			break;
		}
	}
	if (isset($_GET['uninstall']))
	{
		switch($what)
		{
			case 'snowstorm': 
				exec ("rm /www/snowstorm.min.js &"); 
			break;
			
			case 'fool': 
				exec ("rm /www/jquery.min.js &"); 
			break;
		}
	}
}

if (isset($_GET['load']))
{
	if (isset($_GET['file']) && isset($_GET['what']))
	{
		$log_date = date ("F d Y H:i:s", filemtime($module_path."".$_GET['what']."/".$_GET[file]));
		echo "mitmdump ".$_GET['file']." [".$log_date."]\n";
		echo file_get_contents($module_path."".$_GET['what']."/".$_GET['file']);
	}
}

if (isset($_GET['delete']))
{
	if (isset($_GET['file']) && isset($_GET['what']))
	{
		$cmd = "rm -rf ".$module_path."".$_GET['what']."/".$_GET['file']."*";
	}
}

if (isset($_GET['clean']))
{
	if (isset($_GET['what']))
	{
		$cmd = "rm -rf ".$module_path."".$_GET['what']."/*.txt &";
	}
}

if (isset($_GET['install_dep']))
{
	exec("echo \"<?php echo 'working'; ?>\" > ".$module_path."status.php");
	$cmd = "echo \"sh ".$module_path."install.sh\" | at now";
}

if($cmd != "")
{
	$output = shell_exec($cmd);

	if($output != "")
		echo trim($output);
}

?>