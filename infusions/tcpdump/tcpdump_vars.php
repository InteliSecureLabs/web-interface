<?php

putenv('LD_LIBRARY_PATH='.getenv('LD_LIBRARY_PATH').':/usb/lib:/usb/usr/lib');   
putenv('PATH='.getenv('PATH').':/usb/usr/bin:/usb/usr/sbin');

$module_name = "tcpdump";
$module_path = exec("pwd")."/";
$module_version = "2.4";

$is_tcpdump_installed = exec("which tcpdump") != "" ? 1 : 0;
$is_tcpdump_running = exec("ps | grep tcpdump | grep -v -e grep | grep -v -e php") != "" ? 1 : 0;
$is_dump_running = file_exists($module_path."dumps/tmp") != "" ? 1 : 0;

if(!$is_tcpdump_running && $is_dump_running) exec("rm -rf ".$module_path."dumps/tmp &");

$interfacesArray = explode("\n", trim(shell_exec("cat /proc/net/dev | tail -n +3 | cut -f1 -d: | sed 's/ //g'")));

$interfaces = array();
for($i=0;$i<count($interfacesArray);$i++)
{
	$interfaces[$interfacesArray[$i]] = "-i ".$interfacesArray[$i];
}

$resolve = array(
				"Don't resolve hostnames" => "-n",  
				"Don't resolve hostnames or port names" => "-nn"
				);

$options = array(
				"Don't print domain name qualification of host names" => "-N", 
				"Show the packet's contents in both hex and ASCII" => "-X",
				"Print absolute sequence numbers" => "-S",
				"Get the ethernet header as well" => "-e",
				"Show less protocol information" => "-q",
				"Monitor mode" => "-I",
				 );
				
$verbose = array(
				"Verbose" => "-v",  
				"Very verbose" => "-vv",  
				"Very very verbose" => "-vvv"
				 );

$timestamp = array(
				"Don't print a timestamp on each dump line" => "-t",  
				"Print an unformatted timestamp on each dump line" => "-tt",  
				"Print a delta (micro-second resolution) between current and previous line on each dump line" => "-ttt",  
				"Print a timestamp in default format proceeded by date on each dump line" => "-tttt",
				"Print a delta (micro-second resolution) between current and first line on each dump line" => "-ttttt"
				 );							

function dataSize($path)
{
    $blah = exec( "/usr/bin/du -sch $path | tail -1 | awk {'print $1'}" );
    return $blah;
}

?>