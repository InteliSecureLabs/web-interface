<?php

require("networkmanager_vars.php");
require("iwlist_parser.php");

if(isset($_GET['interface']))
{
	echo '<table class="interfaces">';
	
	echo '<tr><td colspan="4"><strong>Physical Interfaces</strong></td></tr>';
	for($i=0;$i<$nbr_wifi_devices;$i++)
	{
		$mac_address = exec("uci get wireless.radio".$i.".macaddr");
		$disabled = exec("uci get wireless.radio".$i.".disabled");
		$interface = exec("ifconfig | grep -i ".$mac_address." | awk '{print $1}'"); $interface = $interface != "" ? $interface : "-";
		$ip_address = exec("ifconfig ".$interface." | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'"); $ip_address = $ip_address != "" ? $ip_address : "";
		
		echo '<tr>';
		
		echo '<td>radio'.$i.'</td>';
		echo '<td>'.$interface.'</td>';
		echo '<td>';
		if(!$disabled)
		{	
			echo '<font color="lime"><strong>enabled</strong></font>'; 	
			if($interface != "-")
				echo ' <a id="macchanger" href="javascript:macchanger(\''.$interface.'\',\''.$i.'\');">[Random Mac]</a>'; 
		}
		else
		{
			echo '<font color="red"><strong>disabled</strong></font>';
		}
		
		echo '</td>';
		
		echo '</tr>';
	}
	
	echo '<tr><td>&nbsp;</td></tr>';
	
	echo '<tr><td colspan="4"><strong>Logical Interfaces</strong></td></tr>';
	
    for ($i=0;$i<count($wifi_interfaces);$i++)
    {
		$disabled = exec("ifconfig | grep ".$wifi_interfaces[$i]." | awk '{ print $1}'") == "" ? 1 : 0;
		$mode = exec("uci get wireless.@wifi-iface[".$i."].mode");
		
		echo '<tr>';
		
		echo '<td>'.$wifi_interfaces[$i].'</td>';
		
		echo '<td>';
		if(!$disabled)
			echo '<font color="lime"><strong>enabled</strong></font>';
		else
			echo '<font color="red"><strong>disabled</strong></font>';
		echo '</td>';
		
		echo '<td>';
		if(!$disabled)
			echo '<a id="disable" href="javascript:interface_toggle(\''.$wifi_interfaces[$i].'\',\'stop\');">[Disable]</a>';
		else
			echo '<a id="enable" href="javascript:interface_toggle(\''.$wifi_interfaces[$i].'\',\'start\');">[Enable]</a>';
		echo '</td>';
		
		if($mode == "sta" && !$disabled && $interface != "-")
		{
			if($ip_address != "")
				echo '<td>'.$ip_address.' <a id="save" href="javascript:release(\''.$wifi_interfaces[$i].'\');">[DHCP Release]</a></td>';
			else
				echo '<td><a id="save" href="javascript:connect(\''.$wifi_interfaces[$i].'\');">[DHCP Request]</a></td>';
		}
		else
		{
			echo '<td></td>';
		}
		
		echo '</tr>';
	}
}

if(isset($_GET['available_ap']))
{
	if (isset($_GET[int])) $interface = $_GET[int];
	
	// List APs
	$iwlistparse = new iwlist_parser();
	$p = $iwlistparse->parseScanDev($interface);

	if(!empty($p))
	{
		echo '<table id="survey-grid" class="grid" cellspacing="0">';
		echo '<tr class="header">';
		echo '<td>SSID</td>';
		echo '<td>BSSID</td>';
		echo '<td>Signal level</td>';
		echo '<td colspan="2">Quality level</td>';
		echo '<td>Ch</td>';
		echo '<td>Encryption</td>';
		echo '<td>Cipher</td>';
		echo '<td>Auth</td>';
		echo '</tr>';
	}
	else
	{
		echo "<em>No data...</em>";
	}

	for($i=1;$i<=count($p[$interface]);$i++)
	{
		$quality = $p[$interface][$i]["Quality"];

		if($quality <= 25) $graph = "red";
		else if($quality <= 50) $graph = "yellow";
		else if($quality <= 100) $graph = "green";

		echo '<tr class="odd" name="'.$p[$interface][$i]["ESSID"].'">';

		echo '<td>'.$p[$interface][$i]["ESSID"].'</td>';

		$MAC_address = explode(":", $p[$interface][$i]["Address"]);
		echo '<td>'.$p[$interface][$i]["Address"].'</td>';
		echo '<td>'.$p[$interface][$i]["Signal level"].'</td>';
		echo "<td>".$quality."%</td>";
		echo "<td width='150'>";
		echo '<div class="graph-border">';
		echo '<div class="graph-bar" style="width: '.$quality.'%; background: '.$graph.';"></div>';
		echo '</div>';
		echo "</td>";
		echo '<td>'.$p[$interface][$i]["Channel"].'</td>';

		if($p[$interface][$i]["Encryption key"] == "on")
		{
			$WPA = strstr($p[$interface][$i]["IE"], "WPA Version 1");
			$WPA2 = strstr($p[$interface][$i]["IE"], "802.11i/WPA2 Version 1");

			$auth_type = str_replace("\n"," ",$p[$interface][$i]["Authentication Suites (1)"]);
			$auth_type = implode(' ',array_unique(explode(' ', $auth_type)));

			$cipher = $p[$interface][$i]["Pairwise Ciphers (2)"] ? $p[$interface][$i]["Pairwise Ciphers (2)"] : $p[$interface][$i]["Pairwise Ciphers (1)"];
			$cipher = str_replace("\n"," ",$cipher);
			$cipher = implode(',',array_unique(explode(' ', $cipher)));

			if($WPA2 != "" && $WPA != "")
				echo '<td>WPA,WPA2</td>';
			else if($WPA2 != "")
				echo '<td>WPA2</td>';
			else if($WPA != "")
				echo '<td>WPA</td>';
			else
				echo '<td>WEP</td>';

			echo '<td>'.$cipher.'</td>';
			echo '<td>'.$auth_type.'</td>';
		}
		else
		{
			echo '<td>None</td>';
			echo '<td>&nbsp;</td>';
			echo '<td>&nbsp;</td>';
		}

		echo '</tr>';
	}
}

?>