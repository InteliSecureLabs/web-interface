<?php

require("uwui_vars.php");

if (isset($_POST['install'])) 
{
	if (isset($_POST['where']) && isset($_POST['what']))
	{
		$where = $_POST['where'];
		$what = $_POST['what'];
		
		switch($where)
		{
			case 'usb': 
				$cmd = "opkg update && opkg install ".$what." --dest usb"; 
			break;
			
			case 'internal': 
				$cmd = "opkg update && opkg install ".$what.""; 
			break;
		}
	}
}

if($cmd != "")
{
	$output = shell_exec($cmd);
	
	if($output != "")
		echo trim($output);
}

?>