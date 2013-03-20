<?php

$usbPresent = (exec("mount | grep \"on /usb\" -c") >= 1)?true:false;

if($usbPresent){
	echo "present";
}else echo "usbNotPresent";

?>