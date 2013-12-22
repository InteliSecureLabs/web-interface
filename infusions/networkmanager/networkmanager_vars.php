<?php

require('networkmanager_functions.php');

$module_name = "Network Manager";
$module_path = exec("pwd")."/";
$module_version = "1.9";

$wifi_interfaces = array_reverse(explode("\n", trim(shell_exec("iwconfig 2> /dev/null | grep \"wlan*\" | grep -v \"mon*\" | awk '{print $1}'"))));

$interfaces = explode("\n", trim(shell_exec("cat /proc/net/dev | tail -n +3 | cut -f1 -d: | sed 's/ //g'")));
$nbr_wifi_devices = exec("uci -P /var/state -q show wireless | grep wifi-device | wc -l");

$configArray = explode("\n", trim(file_get_contents($module_path."networkmanager.conf")));
$interface_from = $configArray[0];
$interface_to = $configArray[1];

$enable_at_boot = exec("cat /etc/rc.local | grep networkmanager/autostart.sh") != "" ? 1 : 0;

if(!is_executable($module_path."autostart.sh")) exec("chmod +x ".$module_path."autostart.sh");

$modes = array(
		"Access Point" => "ap",  
		"Client" => "sta",
		"Ad-Hoc" => "adhoc"
		 );

$security_modes = array(
				"Disabled" => "none",  
				"WEP" => "wep", 
				"WPA Personal" => "psk",  
				//"WPA Enterprise" => "wpa",  
				"WPA2 Personal" => "psk2",  
				//"WPA2 Enterprise" => "wpa2",
				"WPA/WPA2 Personal mixed mode" => "mixed-psk",
				//"WPA/WPA2 Enterprise mixed mode" => "mixed-wpa"
				 );

$wep_modes = array(
			"Shared key" => "shared",
			"Open System" => "open"
			);
			
$eap_types = array(
			"TLS" => "tls",
			"PEAP" => "ttls"
			);

$network_types = array(
			"LAN" => "lan",
			"WAN" => "wan"
			);

$ciphers = array(
		"TKIP" => "tkip",  
		"AES" => "aes",
		"TKIP / AES" => "tkip+aes"
		);

$ssid_broadcast = array(
				"Enable" => "0",
				"Disable" => "1"
				);
?>