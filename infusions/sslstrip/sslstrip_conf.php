<?php

require("sslstrip_vars.php");

if (isset($_GET['get_conf']))
{
	$configArray = explode("\n", trim(file_get_contents($module_path."sslstrip.conf")));
	
	echo "<form id='form_conf'>";
	echo "Command executed on selected capture [Variables: %%FILENAME%%]<br />";
	echo '<input type="text" id="command_File" name="commands[]" value="'.$configArray[0].'" size="115">';
	echo "</form>";
}

if (isset($_POST['set_conf']))
{
	if (isset($_POST['commands']))
	{
		$commands = stripslashes(base64_decode($_POST['commands']));
		
		$filename = $module_path."sslstrip.conf";
		
		$newdata = $commands;
		$newdata = ereg_replace(13,  "", $newdata);
		$fw = fopen($filename, 'w+');
		$fb = fwrite($fw,stripslashes($newdata));
		fclose($fw);
	}
}
?>
