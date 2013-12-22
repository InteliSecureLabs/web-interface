<?php

putenv('LD_LIBRARY_PATH='.getenv('LD_LIBRARY_PATH').':/usb/lib:/usb/usr/lib');   
putenv('PATH='.getenv('PATH').':/usb/usr/bin:/usb/usr/sbin');

$module_name = "WiFi Jammer";
$module_path = exec("pwd")."/";
$module_version = "3.1";

$interfaces = array_reverse(explode("\n", trim(shell_exec("iwconfig 2> /dev/null | grep \"wlan*\" | grep -v \"mon*\" | awk '{print $1}'"))));
$monitorInterfaces = array_reverse(explode("\n", trim(shell_exec("iwconfig 2> /dev/null | grep \"mon*\" | awk '{print $1}'"))));

$whitelist_path = $module_path."rules/whitelist.lst";
$blacklist_path = $module_path."rules/blacklist.lst";
$settings_path = $module_path."jammer.conf";

$is_jammer_running = exec("ps auxww | grep jammer.sh | grep -v -e grep | grep -v -e php") != "" ? 1 : 0;
$is_jammer_onboot = exec("cat /etc/rc.local | grep jammer.sh") != "" ? 1 : 0;

$jammer_conf = parse_ini_file($settings_path);
$packet_conf = $jammer_conf['packet'];
$sleep_conf = $jammer_conf['sleep'];
$interface_conf = $jammer_conf['interface'];
$monitor_conf = $jammer_conf['monitor'];

?>