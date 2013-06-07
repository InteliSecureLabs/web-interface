<?php

require("status_vars.php");

echo '<div id="contentsInfo">';
echo '<div class="col2l">';

echo '<fieldset>';

$current_time = exec("date");

echo '<legend>System</legend>';

echo '<div class="setting">';
echo '<div class="label">Current Time</div>';
echo '<span id="current_time">'.$current_time.'</span>&nbsp;';
echo '</div>';

$up_time = exec("uptime | awk -F, '{sub(\".*up \",x,$1);print $1}'");

echo '<div class="setting">';
echo '<div class="label">Uptime</div>';
echo '<span id="up_time">'.$up_time.'</span>&nbsp;';
echo '</div>';

$hostname = exec("uci get system.@system[0].hostname");

echo '<div class="setting">';
echo '<div class="label">Hostname</div>';
echo '<span id="hostname">'.$hostname.'</span>&nbsp;';
echo '</div>';

echo '</fieldset>';
echo '<br />';
echo '<fieldset>';

echo '<legend>CPU</legend>';

$cpu = exec("uname -m");

echo '<div class="setting">';
echo '<div class="label">CPU Model</div>';
echo '<span id="cpu">'.$cpu.'</span>&nbsp;';
echo '</div>';

$cpu_load_five = exec("uptime | awk '{ print $6}'  | sed 's/,//'");
$cpu_load_ten = exec("uptime | awk '{ print $7}'  | sed 's/,//'");
$cpu_load_fiften = exec("uptime | awk '{ print $8}'  | sed 's/,//'");
$cpu_load = ($cpu_load_five + $cpu_load_ten + $cpu_load_fiften) / 3;

$cpu_load_ptg = round(($cpu_load / 1) * 100) > 100 ? 100 : round(($cpu_load / 1) * 100);
$cpu_load_all = exec("uptime | awk -F 'average:' '{ print $2}'");

echo '<div class="setting">';
echo '<div class="label">Load Average</div>';
echo '<span id="cpu_load"><div class="meter"><div class="bar" style="width: '.$cpu_load_ptg.'%;"></div><div class="text">'.$cpu_load_ptg.'%</div></div>'.$cpu_load_all.'</span>&nbsp;';
echo '</div>';

echo '</fieldset>';
echo '<br />';
echo '<fieldset>';

echo '<legend>Memory</legend>';

$mem_total = exec("free | grep \"Mem:\" | awk '{ print $2 }'");
$mem_used = exec("free | grep \"Mem:\" | awk '{ print $3 }'");
$mem_free = exec("free | grep \"Mem:\" | awk '{ print $4 }'");

$mem_free_ptg = round(($mem_free / $mem_total) * 100);
$mem_used_ptg = 100 - $mem_free_ptg;

echo '<div class="setting">';
echo '<div class="label">Total Available</div>';
echo '<span id="mem_total">'.kbytes_to_string($mem_total).'</span>&nbsp;';
echo '</div>';

echo '<div class="setting">';
echo '<div class="label">Free</div>';
echo '<span id="mem_free"><div class="meter"><div class="bar" style="width: '.$mem_free_ptg.'%;"></div><div class="text">'.$mem_free_ptg.'%</div></div>'.kbytes_to_string($mem_free).'</span>&nbsp;';
echo '</div>';

echo '<div class="setting">';
echo '<div class="label">Used</div>';
echo '<span id="mem_used"><div class="meter"><div class="bar" style="width: '.$mem_used_ptg.'%;"></div><div class="text">'.$mem_used_ptg.'%</div></div>'.kbytes_to_string($mem_used).'</span>&nbsp;';
echo '</div>';

echo '</fieldset>';
echo '<br />';
echo '<fieldset>';

echo '<legend>Swap</legend>';

$swap_total = exec("free | grep \"Swap:\" | awk '{ print $2 }'");
$swap_used = exec("free | grep \"Swap:\" | awk '{ print $3 }'");
$swap_free = exec("free | grep \"Swap:\" | awk '{ print $4 }'");

if($swap_total != 0) $swap_free_ptg = round(($swap_free / $swap_total) * 100); else $swap_free_ptg = 0;
$swap_used_ptg = 100 - $swap_free_ptg;

echo '<div class="setting">';
echo '<div class="label">Total Available</div>';
echo '<span id="mem_total">'.kbytes_to_string($swap_total).'</span>&nbsp;';
echo '</div>';

echo '<div class="setting">';
echo '<div class="label">Free</div>';
echo '<span id="mem_free"><div class="meter"><div class="bar" style="width: '.$swap_free_ptg.'%;"></div><div class="text">'.$swap_free_ptg.'%</div></div>'.kbytes_to_string($swap_free).'</span>&nbsp;';
echo '</div>';

echo '<div class="setting">';
echo '<div class="label">Used</div>';
echo '<span id="mem_used"><div class="meter"><div class="bar" style="width: '.$swap_used_ptg.'%;"></div><div class="text">'.$swap_used_ptg.'%</div></div>'.kbytes_to_string($swap_used).'</span>&nbsp;';
echo '</div>';

echo '</fieldset>';
echo '<br />';
echo '<fieldset>';

echo '<legend>Storage</legend>';

$df = explode("\n", trim(shell_exec("df | grep -v \"Filesystem\"")));

for($i=0;$i<count($df);$i++)
{
	$df_name = exec("df | grep -v \"Filesystem\" | grep \"".$df[$i]."\" | awk '{ print $1}'");
	$df_mount = exec("df | grep -v \"Filesystem\" | grep \"".$df[$i]."\" | awk '{ print $6}'");
	$df_total = exec("df | grep -v \"Filesystem\" | grep \"".$df[$i]."\" | awk '{ print $2}'");
	$df_used = exec("df | grep -v \"Filesystem\" | grep \"".$df[$i]."\" | awk '{ print $3}'");
	$df_used_ptg = exec("df | grep -v \"Filesystem\" | grep \"".$df[$i]."\" | awk '{ print $5}'");
	
	echo '<div class="setting">';
	echo '<div class="label">'.$df_name.' ['.$df_mount.']</div>';
	echo '<span id="df_used"><div class="meter"><div class="bar" style="width: '.$df_used_ptg.';"></div><div class="text">'.$df_used_ptg.'</div></div>'.kbytes_to_string($df_used).'/'.kbytes_to_string($df_total).'</span>&nbsp;';
	echo '</div>';
}

echo '</fieldset>';
echo '<br />';
echo '<fieldset>';

echo '<legend>DHCP Clients</legend>';

$dhcp_clients = explode("\n", trim(shell_exec("cat /tmp/dhcp.leases")));

$count=0;
for($i=0;$i<count($dhcp_clients);$i++)
{
	if($dhcp_clients[$i] != "")
	{
		$count++; $dhcp_client = explode(" ", $dhcp_clients[$i]);
		$mac_address = $dhcp_client[1];
		$ip_address = $dhcp_client[2];
		$hostname = $dhcp_client[3];
				
		echo '<div class="setting">';
		echo '<div class="label">'.$hostname.'</div>';
		echo '<span id="dhcp_clients"><div class="settings_right" onclick="getOUIFromMAC(\''.$mac_address.'\')" title="OUI Search" style="cursor:pointer; text-decoration:underline;">'.$mac_address.'</div><span onclick="execute(\'ping -c4 '.$ip_address.'\')" title="Ping" style="cursor:pointer; text-decoration:underline;">'.$ip_address.'</span></span>&nbsp;';
		echo '</div>';
	}
}

if($count == 0) echo "<em>-</em>";

echo '</fieldset>';
echo '<br />';
echo '<fieldset>';

echo '<legend>WiFi Clients</legend>';

$wifi_clients = explode("\n", trim(shell_exec("iw dev wlan0 station dump | grep \"Station\"")));

$count=0;
for($i=0;$i<count($wifi_clients);$i++)
{
	if($wifi_clients[$i] != "")
	{
		$count++; $wifi_client = explode(" ", $wifi_clients[$i]);
		$mac_address = $dhcp_client[1];
		$ip_address = exec("cat /tmp/dhcp.leases | grep \"".$mac_address."\" | awk '{ print $3}'");
		$hostname = exec("cat /tmp/dhcp.leases | grep \"".$mac_address."\" | awk '{ print $4}'");
	
		echo '<div class="setting">';
		echo '<div class="label">'.$hostname.'</div>';
		echo '<span id="wifi_clients"><div class="settings_right" onclick="getOUIFromMAC(\''.$mac_address.'\')" title="OUI Search" style="cursor:pointer; text-decoration:underline;">'.$mac_address.'</div><span onclick="execute(\'ping -c4 '.$ip_address.'\')" title="Ping" style="cursor:pointer; text-decoration:underline;">'.$ip_address.'</span></span>&nbsp;';
		echo '</div>';
	}
}

if($count == 0) echo "<em>-</em>";

echo '</fieldset>';

echo '</div>';

echo '<div class="col2r">';

echo '<fieldset>';

echo '<legend>WAN Status</legend>';

$wan = @file_get_contents("http://cloud.wifipineapple.com/ip.php"); $wan = $wan != "" ? $wan : "-";
$gateway = exec("netstat -r | grep 'default' | awk '{ print $2}'"); $gateway = $gateway != "" ? $gateway : "-";
$dns = explode("\n", trim(shell_exec("cat /tmp/resolv.conf.auto | grep nameserver | awk '{ print $2}'")));

echo '<div class="setting">';
echo '<div class="label">IP Address</div>';
echo '<span id="wan">'.$wan.'</span>&nbsp;';
echo '</div>';

echo '<div class="setting">';
echo '<div class="label">Gateway</div>';
echo '<span id="gateway">'.$gateway.'</span>&nbsp;';
echo '</div>';

for($i=0;$i<count($dns);$i++)
{
	$tmp_dns = $dns[$i] != "" ? $dns[$i] : "-";
	echo '<div class="setting">';
	echo '<div class="label">DNS '.($i+1).'</div>';
	echo '<span id="dns">'.$tmp_dns.'</span>&nbsp;';
	echo '</div>';
}

echo '</fieldset>';
echo '<br />';

for($i=0;$i<count($interfaces);$i++)
{	
	echo '<fieldset>';
	echo '<legend>'.$interfaces[$i].' Status</legend>';
	
$mac_address = exec("ifconfig ".$interfaces[$i]." | grep 'HWaddr' | awk '{ print $5}'"); $mac_address = $mac_address != "" ? $mac_address : "-";
$ip_address = exec("ifconfig ".$interfaces[$i]." | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'"); $ip_address = $ip_address != "" ? $ip_address : "-";
$subnet_mask = exec("ifconfig ".$interfaces[$i]." | grep 'inet addr:' | cut -d: -f4 | awk '{ print $1}'"); $subnet_mask = $subnet_mask != "" ? $subnet_mask : "-";
$gateway = exec("netstat -r | grep 'default' | grep ".$interfaces[$i]." | awk '{ print $2}'"); $gateway = $gateway != "" ? $gateway : "-";

$is_wifi = shell_exec("iwconfig ".$interfaces[$i]) != "" ? 1 : 0;

if($is_wifi)
{
	$mode = exec("iwconfig ".$interfaces[$i]." | grep 'Mode:' | cut -d: -f2 | awk '{ print $1}'");
	$tx_power = exec("iwconfig ".$interfaces[$i]." | grep 'Tx-Power=' | cut -d= -f2");
}

echo '<div class="setting">';
echo '<div class="label">MAC Address</div>';
echo '<span id="mac_address" onclick="getOUIFromMAC(\''.$mac_address.'\')" title="OUI Search" style="cursor:pointer; text-decoration:underline;">'.$mac_address.'</span>&nbsp;';
echo '</div>';

echo '<div class="setting">';
echo '<div class="label">IP Address</div>';
echo '<span id="ip_address">'.$ip_address.'</span>&nbsp;';
echo '</div>';

echo '<div class="setting">';
echo '<div class="label">Subnet Mask</div>';
echo '<span id="subnet_mask">'.$subnet_mask.'</span>&nbsp;';
echo '</div>';

echo '<div class="setting">';
echo '<div class="label">Gateway</div>';
echo '<span id="gateway">'.$gateway.'</span>&nbsp;';
echo '</div>';

if($is_wifi)
{
	echo '<br />';
	echo '<div class="setting">';
	echo '<div class="label">Mode</div>';
	echo '<span id="mode">'.$mode.'</span>&nbsp;';
	echo '</div>';
	
	echo '<div class="setting">';
	echo '<div class="label">TX Power</div>';
	echo '<span id="tx_power">'.$tx_power.'</span>&nbsp;';
	echo '</div>';
}

echo '</fieldset>';
echo '<br />';

}

echo '</div>';

echo '</div>';

echo '<br clear="all">';

?>