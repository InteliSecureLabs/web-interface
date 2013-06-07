<?php

require("site_survey_vars.php");

if (isset($_GET[get_conf]))
{
	$configArray = explode("\n", trim(file_get_contents($module_path."site_survey.conf")));
	
	echo "<form id='form_conf'>";
	echo "<input type='hidden' name='set_conf'/>";
	echo "Command executed on selected AP [Variables: %%SSID%%, %%BSSID%%, %%CHANNEL%%]<br />";
	echo '<input type="text" id="command_AP" name="commands[]" value="'.$configArray[0].'" size="115"><br /><br />';
	echo "Command executed on selected capture [Variables: %%FILENAME%%]<br />";
	echo '<input type="text" id="command_File" name="commands[]" value="'.$configArray[1].'" size="115">';
	echo "</form>";
}

if (isset($_POST[set_conf]))
{
	if (isset($_POST[commands]))
	{
		$configArray = $_POST[commands];
		
		$commands = "";
		foreach($configArray as $conf)
		{
			$commands .= stripslashes($conf)."\n";
		}
		
		exec("echo \"".$commands."\" > ".$module_path."site_survey.conf");
	}
}
?>