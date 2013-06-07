<?php

putenv('LD_LIBRARY_PATH='.getenv('LD_LIBRARY_PATH').':/usb/lib:/usb/usr/lib');   
putenv('PATH='.getenv('PATH').':/usb/usr/bin:/usb/usr/sbin');

$module_name = "Nmap";
$module_path = exec("pwd")."/";
$module_version = "2.4";

$is_nmap_installed = exec("which nmap") != "" ? 1 : 0;
$is_nmap_running = exec("ps | grep nmap | grep -v -e grep | grep -v -e php | grep -v -e flush-mtd-unmap") != "" ? 1 : 0;
$is_scan_running = file_exists($module_path."scans/tmp") != "" ? 1 : 0;

if(!$is_nmap_running && $is_scan_running) exec("rm -rf ".$module_path."scans/tmp &");

if(!file_exists("/usr/share/nmap/") && file_exists("/usb/usr/share/nmap/"))
{
	exec("ln -s /usb/usr/share/nmap/ /usr/share/nmap");
}

$profiles = array(
				"Intense scan" => "-T4 -A -v",  
				"Intense scan plus UDP" => "-sS -sU -T4 -A -v",  
				"Intense scan, all TCP ports" => "-p 1-65535 -T4 -A -v",  
				"Intense scan, no ping" => "-T4 -A -v -Pn",  
				"Ping scan" => "-sn",  
				"Quick scan" => "-T4 -F",  
				"Quick scan plus" => "-sV -T4 -O -F --version-light",  
				"Quick traceroute" => "-sn --traceroute",  
				"Regular scan" => " ", 
				"Slow comprehensive scan" => "-sS -sU -T4 -A -v -PE -PP -PS80,443 -PA3389 -PU40125 -PY -g 53" 
				 );

$timmings = array(
				"Paranoid" => "-T0",  
				"Sneaky" => "-T1",  
				"Polite" => "-T2",  
				"Normal" => "-T3",  
				"Aggresive" => "-T4",  
				"Insane" => "-T5"
				 );
				
$tcp_scans = array(
				"ACK scan" => "-sA",  
				"FIN scan" => "-sF",  
				"Maimon scan" => "-sM",  
				"Null scan" => "-sN",  
				"TCP SYN scan" => "-sS",  
				"TCP connect scan" => "-sT",  
				"Window scan" => "-sW",  
				"Xmas Tree scan" => "-sX"
				 );
				
$non_tcp_scans = array(
				"UDP scan" => "-sU",  
				"IP protocol scan" => "-sO",  
				"List scan" => "-sL",  
				"No port scan" => "-sn",  
				"SCTP INIT port scan" => "-sY",  
				"SCTP cookie-echo port scan" => "-sZ"
				 );

$scan_options = array(
				"Enable all advanced/aggressive options" => "-A",  
				"OS detection" => "-O",  
				"Version detection" => "-sV",  
				"Disable reverse DNS resolution" => "-n",  
				"IPv6 support" => "-6"
				 );	
			
$target_options = array(
				"Fast scan" => "-F"
				 );

$ping_options = array(
				"No ping before scanning" => "-Pn",  
				"ICMP ping" => "-PE",  
				"ICMP timestamp request" => "-PP",  
				"ICMP netmask request" => "-PM"
				 );					

$other_options = array(
				"Fragment IP packets" => "-f",  
				"Packet trace" => "--packet-trace",  
				"Disable randomizing scanned ports" => "-r",  
				"Trace routes to targets" => "--traceroute"
				 );
				
?>