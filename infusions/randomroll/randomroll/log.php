<?php

$roll = $_GET['roll'];

$clientIP = $_SERVER["REMOTE_ADDR"];

$clientMAC = exec("cat /proc/net/arp | grep ".$clientIP." | awk '{print $4}'");

$clientName = exec("cat /tmp/dhcp.leases | grep '".$clientMAC."' | awk '{ print $4}'");

if (file_exists('/usb/infusions/randomroll/logs')) {
	exec('echo \''.date("Y-m-d H:i:s").' | '.$clientMAC.' | '.$clientIP.' | '.$clientName.' | '.$roll.' | '.$_SERVER["HTTP_REFERER"].'\' >> /usb/infusions/randomroll/logs/RandomRoll.log');
}

unset($roll, $clientIP, $clientMAC, $clientName);

?>