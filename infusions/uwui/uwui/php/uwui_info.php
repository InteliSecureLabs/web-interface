<?php
	include "uwui_vars.php";

	function show_mac($mac) {
		global $demo;
		$mac=strtoupper($mac);
		if ($demo) return substr($mac,0,9)."XX:XX:XX"; else return $mac;
	}

	if ( isset($_GET["tipo"]) && isset($_GET["valor"]) && isset($_GET["valor2"]) && isset($_GET["valor3"]) ) {
		$tipo = $_GET["tipo"];
		$valor = $_GET["valor"];
		$valor2 = $_GET["valor2"];
		$valor3 = $_GET["valor3"];
		if ($tipo=="system") {
			echo "<PRE>";
			echo "<U>TOP</U>\n";
			system("${sudo}top -b -n 1 | head -15");
			echo "<hr color=green>\n";		
			echo "<U>ROUTE</U>\n";
			system("${sudo}route -n");
			echo "<hr color=green>\n";
			echo "<U>IPTABLES</U>\n";
			system("${sudo}iptables --table nat --list|grep .");
			echo "<hr color=green>\n";
			echo "<U>IP FORWARD</U>\n";
			echo "IP Forward: ";
			system("${sudo}cat /proc/sys/net/ipv4/ip_forward");
			echo "</PRE>";
		} 
		if ($tipo=="interface_eth") { 
			echo "<PRE>";
			echo "<U>IFCONFIG</U>\n";
			system("${sudo}ifconfig $valor");
			echo "<hr color=green>\n";
			echo "<U>ROUTE</U>\n";
			system("${sudo}route -n | grep $valor");
			echo "</PRE>";
		}
		if ($tipo=="interface" ||$tipo=="monitor" ) { 
			echo "<PRE>";
			echo "<U>IFCONFIG</U>\n";
			system("${sudo}ifconfig $valor");
			echo "<hr color=green>\n";
			echo "<U>IWCONFIG</U>\n";
			system("${sudo}iwconfig $valor");
			echo "</PRE>";
		}
		if ($tipo=="interface" || $tipo=="interface_eth") { 
			echo "<PRE>";
			echo "<hr color=green>\n";
			echo "<U>ARP</U>\n";
			system("${sudo}cat /proc/net/arp | grep $valor");
			if ($system!="pineapple"){
				echo "<hr color=green>\n";
				echo "<U>ETHTOOL</U>\n";
				system("${sudo}ethtool -i $valor");
				echo "</PRE>";
			}
		}

		if ($tipo=="file") {
			echo "<PRE>";
			echo "<U>LS</U>\n";			
			system("${sudo}ls -lh $valor");
			if ( pathinfo($valor, PATHINFO_EXTENSION) == "log" ) {
				echo "<hr color=green>\n";
				echo "<U>COUNT</U>: ";
				system("${sudo}strings $valor | wc -l");
				echo "\n<U>STRINGS</U>\n";
				system("${sudo}strings $valor | tail -17");
			}
			echo "</PRE>";
		}
		if ($tipo=="proceso") {
			echo "<PRE>";
			echo "<U>PS</U>\n";			
			system("${sudo}ps -ef | grep -v grep | grep $valor");
			echo "</PRE>";
		}
		if ($tipo=="ap") {
			echo "<PRE>";
			echo "<U>IWLIST</U>\n";
			system('${sudo}iwlist '.$valor2.' scan last | tr -s " "|tr -s "\n" "&" | sed -e "s/Cell/\nCell/g" | grep "'.trim($valor).'" | tr -s "&" "\n" | grep -v "IE: Unknown"' );
			echo "</PRE>";
		}	
		if ($tipo=="ap_monitor") {
			echo "<PRE>";
			echo "BUILDER:\t ";
			$mac_min=strtoupper(substr($valor,0,8));
			system("${sudo}strings ../nic.txt | grep $mac_min | cut -d' ' -f2");
			$ap_info=exec("${sudo}strings $valor2 | grep '^$valor' | tr -s ' ' ");
			$ap_value = explode(",",$ap_info,15);
			echo "BSSID:\t\t ".show_mac(trim($ap_value[0]))."\n";
			echo "FIRST TIME SEEN: ".trim($ap_value[1])."\n";
			echo "LAST TIME SEEN:\t ".trim($ap_value[2])."\n";
			echo "CHANNEL:\t ".trim($ap_value[3])."\n";
			echo "SPPED:\t\t ".trim($ap_value[4])."\n";
			echo "PRIVACY:\t ".trim($ap_value[5])."\n";
			echo "CIPHER:\t\t ".trim($ap_value[6])."\n";
			echo "ATHENTICATION:\t ".trim($ap_value[7])."\n";
			echo "POWER:\t\t ".trim($ap_value[8])."\n";
			echo "# BEACONS:\t ".trim($ap_value[9])."\n";
			echo "# IVS:\t\t ".trim($ap_value[10])."\n";
			echo "LAN IP:\t\t ".trim($ap_value[11])."\n";
			echo "ID-LENGTH:\t ".trim($ap_value[12])."\n";
			echo "ESSID:\t\t ".trim($ap_value[13])."\n";
			echo "</PRE>";
		}	
		if ($tipo=="station") {
			echo "<PRE>";
			echo "BUILDER:\t ";
			$mac_min=strtoupper(substr($valor,0,8));
			system("${sudo}strings ../nic.txt | grep $mac_min | cut -d' ' -f2");
			$station_info=exec("${sudo}strings $valor2 | tr -s ' ' | grep '^$valor'");
			$station_value = explode(",",$station_info,7);	
			echo "STATION MAC:\t ".show_mac(trim($station_value[0]))."\n";
			echo "FIRST TIME SEEN: ".trim($station_value[1])."\n";
			echo "LAST TIME SEEN:\t ".trim($station_value[2])."\n";
			echo "POWER:\t\t ".trim($station_value[3])."\n";
			echo "# PACKETS:\t ".trim($station_value[4])."\n";
			echo "BSSID:\t\t ".show_mac(trim($station_value[5]))."\n";
			echo "PROBED ESSIDS:\t ".trim($station_value[6])."\n";
			echo "<hr color=green>\n";
			echo "<U>LOCATIONS</U>\n";
			system("${sudo}grep $valor ../data/locations/*.stations | cut -d':' -f1 | cut -d'/' -f3| cut -d'.' -f1");
			echo "</PRE>";
		}
		if ($tipo=="ip") {
			echo "<PRE>";
			echo "IP: $valor\n";
			echo "MAC: $valor3\n";
			echo "BUILDER: ";
			$mac_min=strtoupper(substr($valor3,0,8));
			system("strings ../nic.txt | grep $mac_min|cut -d' ' -f2");
			echo "</PRE>";
		}		
	}
?>
