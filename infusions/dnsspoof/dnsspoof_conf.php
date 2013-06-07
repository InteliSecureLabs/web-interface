<?php

require("dnsspoof_vars.php");

if (isset($_POST[set_conf]))
{	
	if($_POST[set_conf] == "hosts")
	{
		$filename = $hosts_path;
		$newdata = $_POST['newdata'];

		$newdata = ereg_replace(13,  "", $newdata);
		$fw = fopen($filename, 'w');
		$fb = fwrite($fw,stripslashes($newdata));
		fclose($fw);
	}
}

?>