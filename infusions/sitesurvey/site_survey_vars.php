<?php

$module_name = "Site Survey";
$module_path = exec("pwd")."/";
$module_version = "2.6";

$interfaces = array_reverse(explode("\n", trim(shell_exec("iwconfig 2> /dev/null | grep \"wlan*\" | grep -v \"mon*\" | awk '{print $1}'"))));
$monitorInterfaces = array_reverse(explode("\n", trim(shell_exec("iwconfig 2> /dev/null | grep \"mon*\" | awk '{print $1}'"))));

$is_airodump_running = exec("ps | grep airodump-ng | grep -v -e grep | grep -v -e php") != "" ? 1 : 0;
$is_capture_running = file_exists($module_path."captures/lock") != "" ? 1 : 0;
$is_custom_running = exec("ps | grep custom.sh | grep -v -e grep | grep -v -e php") != "" ? 1 : 0;

if(!$is_airodump_running && $is_capture_running) exec("rm -rf ".$module_path."captures/lock &");

$custom_commands = explode("\n", trim(file_get_contents($module_path."site_survey.conf")));

$timeAP = 30;

$dumpPath="/tmp/mk";

$output_types = array("cap", "csv", "kismet.csv", "kismet.netxml");

function dataSize($path)
{
    $blah = exec( "/usr/bin/du -sch $path | tail -1 | awk {'print $1'}" );
    return $blah;
}

function replace_tags($tags = array(), $buffer) 
{
	foreach ($tags as $tag => $data)
	{
		$buffer = str_replace("%%".$tag."%%", $data, $buffer);
	}
	
	return $buffer;
}

?>