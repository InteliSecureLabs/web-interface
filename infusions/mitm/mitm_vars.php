<?php

putenv('LD_LIBRARY_PATH='.getenv('LD_LIBRARY_PATH').':/usb/lib:/usb/usr/lib');   
putenv('PATH='.getenv('PATH').':/usb/usr/bin:/usb/usr/sbin');

$module_name = "MITM";
$module_path = exec("pwd")."/";
$module_version = "1.3";

$is_snowstorm_installed = file_exists("/www/snowstorm.min.js") ? 1 : 0;
$is_fool_installed = file_exists("/www/jquery.min.js") ? 1 : 0;

$usb_mnt = exec("mount | grep \"on /usb\"") != "" ? 1 : 0;
$on_usb = strpos($module_path, "usb") !== false ? 1 : 0;

$installed = file_exists($module_path."installed") ? 1 : 0;

$is_python_installed = exec("which python") != "" ? 1 : 0;
$is_mitm_installed = exec("which mitmdump") != "" ? 1 : 0;
$is_mitm_running = exec("ps auxww | grep mitmdump | grep -v -e grep | grep -v -e php") != "" ? 1 : 0;

$mitm_conf = trim(file_get_contents($module_path."mitm.conf"));

if(!$is_mitm_running && $mitm_conf != "") exec("echo \"\" > ".$module_path."mitm.conf");

$is_executable = exec("if [ -x ".$module_path."install.sh ]; then echo '1'; fi") != "" ? 1 : 0;
if(!$is_executable) exec("chmod +x ".$module_path."install.sh");

if(!file_exists("/usr/lib/python2.7") && file_exists("/usb/usr/lib/python2.7/"))
{
	exec("ln -s /usb/usr/lib/python2.7/ /usr/lib/python2.7");
}

function dataSize($path)
{
    $blah = exec( "/usr/bin/du -sch $path | tail -1 | awk {'print $1'}" );
    return $blah;
}

?>
