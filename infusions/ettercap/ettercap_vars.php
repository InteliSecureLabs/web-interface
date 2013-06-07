<?php

putenv('LD_LIBRARY_PATH='.getenv('LD_LIBRARY_PATH').':/usb/lib:/usb/usr/lib');   
putenv('PATH='.getenv('PATH').':/usb/usr/bin:/usb/usr/sbin');

$module_name = "Ettercap";
$module_path = exec("pwd")."/";
$module_version = "1.5";

$is_ettercap_installed = exec("which ettercap") != "" ? 1 : 0;
$is_ettercap_running = exec("ps | grep ettercap | grep -v -e grep | grep -v -e php") != "" ? 1 : 0;
$is_log_running = file_exists($module_path."log/tmp") != "" ? 1 : 0;

if(!$is_ettercap_running && $is_log_running) exec("rm -rf ".$module_path."log/tmp &");

$interfacesArray = explode("\n", trim(shell_exec("cat /proc/net/dev | tail -n +3 | cut -f1 -d: | sed 's/ //g'")));

$interfaces = array();
for($i=0;$i<count($interfacesArray);$i++)
{
	$interfaces[$interfacesArray[$i]] = "-i ".$interfacesArray[$i];
}

$mitm_options = array(
				"arp" => "-M arp",
				"icmp" => "-M icmp",
				"dhcp" => "-M dhcp",
				"port" => "-M port"
				);

$proto_options = array(
				"tcp" => "-t tcp",
				"udp" => "-t udp",
				"all" => "-t all"
				);

$sniffing_and_attack_options = array(
				"Don't sniff, only perform the mitm attack" => "-o",
				"Do not put the iface in promisc mode" => "-p",
				"Do not forward packets" => "-u",
				"Use reversed TARGET matching" => "-R"
				);

$ui_type = array(
				"Do not display packet contents" => "-q",
				"Use console interface" => "-T"
				 );
				
$visualization_options = array(
				"Resolves ip addresses into hostnames" => "-d",  
				"Print extended header for every packet" => "-E",  
				"Do not display user and password" => "-Q"
				 );
				
$visualization_format = array(
				"hex" => "-V hex",  
				"ascii" => "-V ascii",  
				"text" => "-V text",
				"ebcdic" => "-V ebcdic",  
				"html" => "-V html",  
				"utf8" => "-V utf8"
				 );

$general_options = array(
				"Do not perform the initial ARP scan" => "-z"
				 );							

function dataSize($path)
{
    $blah = exec( "/usr/bin/du -sch $path | tail -1 | awk {'print $1'}" );
    return $blah;
}

?>