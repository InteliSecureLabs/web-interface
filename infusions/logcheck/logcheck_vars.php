<?php

putenv('LD_LIBRARY_PATH='.getenv('LD_LIBRARY_PATH').':/usb/lib:/usb/usr/lib');   
putenv('PATH='.getenv('PATH').':/usb/usr/bin:/usb/usr/sbin');

if(!file_exists("/etc/ssmtp/") && file_exists("/usb/etc/ssmtp/"))
{
	exec("ln -s /usb/etc/ssmtp/ /etc/ssmtp");
}

$module_name = "Logcheck";
$module_path = exec("pwd")."/";
$module_version = "2.3";

$match_path = $module_path."rules/match";
$ignore_path = $module_path."rules/ignore";
$smtp_path = "/etc/ssmtp/ssmtp.conf";
$custom_path = $module_path."custom.sh";

$is_ssmtp_installed = exec("which ssmtp") != "" ? 1 : 0;
$is_daemon_installed = exec("cat /etc/crontabs/root | grep logcheck") != "" ? 1 : 0;
$daemon_update = exec("logread | grep logcheck_report.sh | tail -1 | awk '{print $1\" \"$2\" \"$3;}'");

$is_logcheck_running = exec("ps auxww | grep logread | grep -v -e grep | grep -v -e php") != "" ? 1 : 0;
$is_logcheck_onboot = exec("cat /etc/rc.local | grep logcheck") != "" ? 1 : 0;

$logcheck_conf = parse_ini_file($module_path."logcheck.conf");
$To = $logcheck_conf['to'];
$From = $logcheck_conf['from'];
$Subject = $logcheck_conf['subject'];

$cron_time = "*/30 * * * *";
$cron_task = $module_path."logcheck_report.sh";

?>