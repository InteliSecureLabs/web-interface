<?php

putenv('LD_LIBRARY_PATH='.getenv('LD_LIBRARY_PATH').':/usb/lib:/usb/usr/lib');   
putenv('PATH='.getenv('PATH').':/usb/usr/bin:/usb/usr/sbin');

$module_name = "urlsnarf";
$module_path = exec("pwd")."/";
$module_version = "2.8";

$is_urlsnarf_installed = exec("which urlsnarf") != "" ? 1 : 0;
$is_urlsnarf_running = exec("ps | grep urlsnarf | grep -v -e grep | grep -v -e php") != "" ? 1 : 0;
$is_urlsnarf_onboot = exec("cat /etc/rc.local | grep urlsnarf/autostart.sh") != "" ? 1 : 0;

$interfaces = explode("\n", trim(shell_exec("cat /proc/net/dev | tail -n +3 | cut -f1 -d: | sed 's/ //g'")));
$current_interface = trim(file_get_contents($module_path."urlsnarf.run"));

$custom_commands = explode("\n", trim(file_get_contents($module_path."urlsnarf.conf")));

$is_executable = exec("if [ -x ".$module_path."autostart.sh ]; then echo '1'; fi") != "" ? 1 : 0;
if(!$is_executable) exec("chmod +x ".$module_path."autostart.sh");

function replace_tags($tags = array(), $buffer) 
{
	foreach ($tags as $tag => $data)
	{
		$buffer = str_replace("%%".$tag."%%", $data, $buffer);
	}
	
	return $buffer;
}

?>