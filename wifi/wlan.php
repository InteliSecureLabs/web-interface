<?php

if(isset($_GET[start])){
	exec("wifi");
	header("Status: 302 Found");
	header("Location: /index.php");
}

if(isset($_GET[stop])){
	exec("killall hostapd && ifconfig wlan0 down");
	header("Status: 302 Found");
	header("Location: /index.php");
}
