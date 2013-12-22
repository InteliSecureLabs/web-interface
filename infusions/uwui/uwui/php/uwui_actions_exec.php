<?php
	include "uwui_vars.php";

	if ( isset($_GET["cmd"]) ) {
		echo "$_GET[cmd]\n";
		system("${sudo}$_GET[cmd] 2>&1");
	}
	if ( isset($_GET["cmd_bg"]) ) {
		echo "$_GET[cmd_bg]\n";
		shell_exec("${sudo}nohup $_GET[cmd_bg] &");
	}
	if ( isset($_GET["cmd_pine"]) ) {
		echo "$_GET[cmd_pine]\n";
		shell_exec("echo export PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usb/usr/bin:/usb/usr/sbin > ../data/cmd.sh");
		shell_exec("echo export LD_LIBRARY_PATH=/lib:/usr/lib:/usb/lib:/usb/usr/lib >> ../data/cmd.sh");
		shell_exec("echo '$_GET[cmd_pine]' >> ../data/cmd.sh");
		shell_exec("sh ../data/cmd.sh 2>&1");
	}
	if ( isset($_GET["cmd_pine_bg"]) ) {
		echo "$_GET[cmd_pine_bg]\n";
		shell_exec("echo export PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usb/usr/bin:/usb/usr/sbin > ../data/cmd.sh");
		shell_exec("echo export LD_LIBRARY_PATH=/lib:/usr/lib:/usb/lib:/usb/usr/lib >> ../data/cmd.sh");
		shell_exec("echo '$_GET[cmd_pine_bg]' >> ../data/cmd.sh");
		shell_exec("nohup sh ../data/cmd.sh &");
	}
?>
