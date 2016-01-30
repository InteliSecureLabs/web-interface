<?php

putenv('LD_LIBRARY_PATH='.getenv('LD_LIBRARY_PATH').':/usb/lib:/usb/usr/lib');   
putenv('PATH='.getenv('PATH').':/usb/usr/bin:/usb/usr/sbin');

	if (is_dir("/etc/config")) {
		$system="pineapple";
		$sudo=""; 
	} else {
		$system="intel";
		$sudo="sudo ";
	}
	$demo=false;
	$debug=false;
?>
