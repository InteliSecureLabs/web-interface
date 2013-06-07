<?php

require("site_survey_vars.php");

if (isset($_GET[int])) $interface = $_GET[int];
if (isset($_GET[mon])) $monitorInterface = $_GET[mon];

if (isset($_GET[history]))
{
	$log_list = array_reverse(glob($module_path."log/*"));

	if(count($log_list) == 0)
		echo "<em>No log history...</em>";
	
	for($i=0;$i<count($log_list);$i++)
	{
		if(basename($log_list[$i]) != "tmp")
		{
			$info = explode("_", basename($log_list[$i]));
			echo date('Y-m-d H-i-s', $info[1])." [";
			echo "<a href=\"javascript:load_file('".basename($log_list[$i])."');\">view</a> | ";
			echo "<a href=\"javascript:javascript:location.href='log/".basename($log_list[$i])."'\">download</a> | ";
			echo "<a href=\"javascript:delete_file('log','".basename($log_list[$i])."');\">delete</a>]<br />";
		}
	}
}

if (isset($_GET[captures]))
{
	$captures_list = array_reverse(glob($module_path."captures/*.cap"));

	if(count($captures_list) == 0)
		echo "<em>No capture history...</em>";

	for($i=0;$i<count($captures_list);$i++)
	{
		$info = explode("_", basename($captures_list[$i]));
		
		$BSSID = exec("awk -F, '/BSSID/ {i=1; next} i {print $1}' ".$module_path."captures/".basename($captures_list[$i],".cap").".csv | head -1");
		$ESSID = exec("awk -F, '/BSSID/ {i=1; next} i {print $14}' ".$module_path."captures/".basename($captures_list[$i],".cap").".csv | head -1");
		$IVS = exec("awk -F, '/BSSID/ {i=1; next} i {print $11}' ".$module_path."captures/".basename($captures_list[$i],".cap").".csv | head -1");
		
		echo date('Y-m-d H-i-s', $info[1])." [".$BSSID." - ".$ESSID."] #IVS ".$IVS." ";
		echo "| ".dataSize($module_path."captures/".basename($captures_list[$i],".cap").".*")." ";
		echo "| <a href=\"javascript:delete_file('cap','".basename($captures_list[$i],".cap")."');\">delete</a><br/>";

		for($j=0;$j<count($output_types);$j++)
		{
			$file = basename($captures_list[$i],".cap").".".$output_types[$j];
			
			$tags = array("FILENAME" => $module_path."captures/".$file);
			$custom_command = addslashes(replace_tags($tags, $custom_commands[1]));
			
			echo $output_types[$j]." ";
			echo "[<a href=\"javascript:location.href='captures/".$file."'\">load</a> - ";
			echo "<a href=\"javascript:execute_custom_script('".base64_encode($custom_command)."');\">exec</a>] ";
		}
		echo "<br /><br />";
	}
}

if (isset($_GET[deauthtarget]))
{
	if(isset($_GET[deauthtargetClient]) && $_GET[deauthtargetClient] != "")
	{
		$cmd = "aireplay-ng -0 ".$_GET[deauthtimes]." --ignore-negative-one -D -c $_GET[deauthtargetClient] -a $_GET[deauthtarget] ".$monitorInterface." &";
	}
	else
	{
		$cmd = "aireplay-ng -0 ".$_GET[deauthtimes]." --ignore-negative-one -D -a $_GET[deauthtarget] ".$monitorInterface." &"; 
	}
}

if (isset($_GET[ap]))
{
	$time = time();
	$full_cmd = "airodump-ng -c ".$_GET[channel]." --bssid ".$_GET[ap]." -w ".$module_path."captures/capture_".$time." ".$monitorInterface." &> /dev/null";

	shell_exec("echo \"#!/bin/sh\necho \"".$_GET[ap]."\" > ".$module_path."captures/lock\n".$full_cmd." &\" > ".$module_path."capture.sh && chmod +x ".$module_path."capture.sh &");
	$cmd = "echo ".$module_path."capture.sh | at now";
}

if (isset($_GET[cancel]))
{
	$cmd = "killall airodump-ng && rm -rf ".$module_path."captures/lock &";
}

if($cmd != "")
{
	$output = shell_exec($cmd);

	if($output != "")
		echo trim($output);
}

?>