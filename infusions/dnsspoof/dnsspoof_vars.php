<?php

putenv('LD_LIBRARY_PATH='.getenv('LD_LIBRARY_PATH').':/usb/lib:/usb/usr/lib');   
putenv('PATH='.getenv('PATH').':/usb/usr/bin:/usb/usr/sbin');

$module_name = "dnsspoof";
$module_path = exec("pwd")."/";
$module_version = "1.6";

$is_dnsspoof_installed = exec("which dnsspoof") != "" ? 1 : 0;
$is_dnsspoof_running = exec("ps | grep dnsspoof | grep -v -e grep | grep -v -e php") != "" ? 1 : 0;
$is_dnsspoof_onboot = exec("cat /etc/rc.local | grep dnsspoof/autostart.sh") != "" ? 1 : 0;

$hosts_path = "/pineapple/config/spoofhost";

$fake_files_installed = file_exists("/www/ncsi.txt") && file_exists("/www/library/test/success.html") ? 1 : 0;

$is_executable = exec("if [ -x ".$module_path."autostart.sh ]; then echo '1'; fi") != "" ? 1 : 0;
if(!$is_executable) exec("chmod +x ".$module_path."autostart.sh");

?>