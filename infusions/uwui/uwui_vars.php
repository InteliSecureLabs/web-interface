<?php

putenv('LD_LIBRARY_PATH='.getenv('LD_LIBRARY_PATH').':/usb/lib:/usb/usr/lib');   
putenv('PATH='.getenv('PATH').':/usb/usr/bin:/usb/usr/sbin');

$module_name = "UWUI";
$module_path = exec("pwd")."/";
$module_version = "1.0 / v0.51";

$dependencies = array(
		"nmap" => "nmap",  
		"tcpdump" => "tcpdump",
		"sslstrip" => "sslstrip",
		"dsniff" => "dsniff",
		"urlsnarf" => "urlsnarf"
		 );

$is_nmap_installed = exec("which nmap") != "" ? 1 : 0;
$is_tcpdump_installed = exec("which tcpdump") != "" ? 1 : 0;
$is_sslstrip_installed = exec("which sslstrip") != "" ? 1 : 0;
$is_dsniff_installed = exec("which dsniff") != "" ? 1 : 0;
$is_urlsnarf_installed = exec("which urlsnarf") != "" ? 1 : 0;

// sslstrip checks
if(!file_exists("/usr/lib/python2.7") && file_exists("/usb/usr/lib/python2.7/"))
{
	exec("ln -s /usb/usr/lib/python2.7/ /usr/lib/python2.7");
}

if(!file_exists("/usr/share/sslstrip") && file_exists("/usb/usr/share/sslstrip/"))
{
	exec("ln -s /usb/usr/share/sslstrip/ /usr/share/sslstrip");
}

if(!file_exists("/usb/usr/lib/python2.7/site-packages/zope/__init__.py"))
{
	exec("touch /usb/usr/lib/python2.7/site-packages/zope/__init__.py");
}

// nmap checks
if(!file_exists("/usr/share/nmap/") && file_exists("/usb/usr/share/nmap/"))
{
	exec("ln -s /usb/usr/share/nmap/ /usr/share/nmap");
}

?>