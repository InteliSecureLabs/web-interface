<?php
	include "uwui_functions.php";
	include "uwui_vars.php";

	function get_matches($file_name,$x,$y){
		global $sudo;
		if (file_exists("../cfg/show_offline.flag")) $show_offline=true; else $show_offline=false;
		if (file_exists("../data/locations/stations.lst")){
			exec("strings $file_name | grep -v '  0.  0.\|BSSID, First time\|Station MAC, First' | cut -d',' -f1 | grep . >/tmp/candidates.stations");
			exec("strings ../data/locations/stations.lst | grep -f /tmp/candidates.stations >/tmp/matches.stations");
		}
		$num_aps = 0;
		$start_x = $x;
		$end_x = $x;
		exec("strings $file_name | grep -v '  0.  0.' | tr -s ' ' | sort",$output6);
		foreach ($output6 as $line6){
			list($v1,$v2,$v3,$v4,$v5,$v6,$v7) = explode(",",$line6,7);
			if (strlen($v1)==17){
				if (file_exists("../data/locations/stations.lst") && exec("grep $v1 /tmp/matches.stations 2>/dev/null")!="") $match=true; else $match=false; 
				$state="offline";
				$f0 = strtotime($v3);
				$f1 = strtotime(date("Y-m-d H:i:s"));
				$result = ($f1 - $f0);
				if ($result < 60) $state="online";
				if ($match && ($state=="online" || $show_offline) ) { 
					if ($num_aps>0) $x++; $num_aps++;
					nodo_station($y,$x,"$v1","$v6","$file_name","$state",$result,$match);
					if ($num_aps == 4){
						$y++;
						$end_x = $x;						
						$x = $start_x;
						$num_aps = 0;
					}
				}
			}
		}
		$output6=null;
		if ( $x > $end_x ) $final_x=$x; else $final_x=$end_x;
		return $final_x;
	}

	function get_aps_monitor($file_name,$x,$y){
		global $sudo;
		if (file_exists("../cfg/show_stations.flag")) $show_stations=true; else $show_stations=false;
		if (file_exists("../cfg/show_offline.flag")) $show_offline=true; else $show_offline=false;
		if (file_exists("../data/locations/stations.lst")){
			exec("strings $file_name | grep -v '  0.  0.\|BSSID, First time\|Station MAC, First' | cut -d',' -f1 | grep . >/tmp/candidates.stations");
			exec("strings ../data/locations/stations.lst | grep -f /tmp/candidates.stations >/tmp/matches.stations");
		}
		exec("strings $file_name | grep '  0.  0.' | tr -s ' ' | sort",$output5);
		$num_aps = 0;
		$start_x = $x;
		$end_x = $x;
		foreach ($output5 as $line5){
			$valores = explode(",",$line5,15);
			if (strlen($valores[0])==17){
				$state="offline";
				$f0 = strtotime($valores[2]);
				$f1 = strtotime(date("Y-m-d H:i:s"));
				$result = ($f1 - $f0);
				if ($result < 60) $state="online";
				if (($state=="online" || $show_offline ) && trim($valores[13])!="") { 
					if ($num_aps>0) $x++; $num_aps++;
					nodo_ap_monitor($y,$x,$valores[13],$valores[0],trim($valores[5]),"$file_name","$state");					
					if ($num_aps == 4){
						$y++;
						$end_x = $x;
						$x = $start_x;
						$num_aps = 0;
					}
					if ($show_stations){
						exec("strings $file_name | grep -v '  0.  0.' | grep $valores[0] | tr -s ' ' | sort",$output6);
						foreach ($output6 as $line6){
							list($v1,$v2,$v3,$v4,$v5,$v6,$v7) = explode(",",$line6,7);
							if (strlen($v1)==17){
								if (file_exists("../data/locations/stations.lst") && exec("grep $v1 /tmp/matches.stations 2>/dev/null")!="") $match=true; else $match=false; 
								$state="offline";
								$f0 = strtotime($v3);
								$f1 = strtotime(date("Y-m-d H:i:s"));
								$result = ($f1 - $f0);
								if ($result < 60) $state="online";
								if ($state=="online" || $show_offline) { 
									if ($num_aps>0) $x++; $num_aps++;
									nodo_station($y,$x,"$v1","$v6","$file_name","$state",$result,$match);
									if ($num_aps == 4){
										$y++;
										$end_x = $x;						
										$x = $start_x;
										$num_aps = 0;
									}
								}
							}
						}
						$output6=null;
					}
				}
			}
			$valores="";
		}
		if ($show_stations){
			$output5=null;
			if ($num_aps == 0) $y=$y+0.3; else $y=$y+1.3; 
			exec("strings $file_name | grep -v '  0.  0.' | grep 'not' | tr -s ' ' | sort",$output5);
			if (count($output5)>0){
				$num_aps = 0;
				$x = $start_x;
				foreach ($output5 as $line5){
					list($v1,$v2,$v3,$v4,$v5,$v6,$v7) = explode(",",$line5,7);
					if (strlen($v1)==17){
						$state="offline";
						$f0 = strtotime($v3);
						$f1 = strtotime(date("Y-m-d H:i:s"));
						$result = ($f1 - $f0);
						if ($result < 60) $state="online";
						if ($state=="online" || $show_offline) { 
							if ($num_aps>0) $x++; $num_aps++;
							if (file_exists("../data/locations/stations.lst") && exec("grep $v1 /tmp/matches.stations 2>/dev/null")!="") $match=true; else $match=false; 
							nodo_station($y,$x,"$v1","$v6","$file_name","$state",$result,$match);
							if ($num_aps == 4){
								$y++;
								$end_x = $x;								
								$x = $start_x;
								$num_aps = 0;
							}
						}
					}
				}
			}
			$output5=null;
		}
		if ( $x > $end_x ) $final_x=$x; else $final_x=$end_x;
		return $final_x;
	}

	function get_files($y,$x,$pid){
		global $sudo;
		exec("${sudo}ls -l /proc/$pid/fd 2>/dev/null | grep 'data' | grep -v 'pipe\|dev\|socket\|total\|event' | cut -d'>' -f2",$output_file);
		$prevX_file=$x;
		$num_file=0;
		foreach ($output_file as $line_file){
			$file_name=trim($line_file);
			if ($num_file>0) $x++; $num_file++;
			if ($x>$prevX_file+1) enlace($y+1,$prevX_file,$x,"orange");
			nodo_file($y+1,$x,$file_name);
			$prevX_file=$x;
			if (pathinfo($file_name, PATHINFO_EXTENSION) == "csv"){
				if (file_exists("../cfg/show_only_matches.flag")) $x=get_matches($file_name,$x,$y+2); else $x=get_aps_monitor($file_name,$x,$y+2);
			}
		}
		$output_file=null;
		return $x;
	}

	function get_processes($y,$x,$name,$hay_aps){
		global $sudo;
		if (file_exists("../cfg/hide_processes.flag")) $hide_processes=true; else $hide_processes=false;
		$hay_processes=false;
		exec("${sudo}pgrep -f $name 2>/dev/null",$output_process);
		$prevX_process=$x;
		$num_process=0;
		foreach ($output_process as $line2){
			$pid=$line2;
			if ($hide_processes){
				$name=exec("${sudo}cat /proc/$pid/comm 2>/dev/null| grep -vf ../cfg/hide_process.cfg");
			} else {
				$name=exec("${sudo}cat /proc/$pid/comm 2>/dev/null");
			}
			if ($name=="sh") {
				$temp_name=trim(exec("${sudo}strings /proc/$pid/cmdline 2>/dev/null"));
				$pos = strpos($temp_name,"/scripts/");
				if ($pos === false) {
					$name="script";
				} else {
					$name=substr($temp_name,$pos+9,strpos($temp_name,".sh")-$pos-6);
				}
			}
			if ($name!="" && is_numeric($pid)){
				$hay_processes=true;
				if ($num_process>0 || $hay_aps) $x++; $num_process++; 
				if ($x>$prevX_process+1) enlace($y,$prevX_process,$x,"red");
				nodo_proceso($y,$x,$name,$pid);
				$prevX_process=$x;
				$x = get_files($y,$x,$pid);					
			}
		}
		$output_process=null;
		return array($x,$hay_processes);
	}

	function get_monitors($y,$x,$mac_monitor,$hay_aps){
		global $sudo;
		$mac_convert=strtoupper(str_replace(":", "-",$mac_monitor));
		exec("${sudo}ifconfig | grep $mac_convert | tr -s ' '| cut -d' ' -f1",$output_monitor);
		$prevX_monitor=$x;
		$num_monitor=0;
		foreach ($output_monitor as $line_monitor){
			if ($num_monitor>0 || $hay_aps) $x++; $num_monitor++;
			if ($x>$prevX_monitor+1) enlace(2,$prevX_monitor,$x,"blue");
			nodo_monitor($y,$x,$line_monitor);
			$prevX_monitor=$x;
			list($x,$hay_processes)=get_processes($y+1,$x,$line_monitor,false);
		}
		$output_monitor=null;
		return $x;
	}

	function get_aps($name_interface,$x,$y){
		global $sudo;
		$hay_aps=false;
		exec('${sudo}iwlist '.$name_interface.' scan 2>/dev/null | grep "Cell\|ESSID\|Quality" | tr -s " " | tr -s "\n" "&" | sed -e "s/Cell/\nCell/g" | tr -s " " ";" | tr -s "=" ";" | tr -s "\"" ";" | cut -d";" -f5,7,10,14| tr -d "&" | grep .',$output_aps);
		foreach ($output_aps as $line_aps){
			$hay_aps=true;
			list($bssid,$quality,$signal_level,$essid) = explode(";",$line_aps,4);
			nodo_ap($y,$x,$essid,$bssid,$quality,$signal_level,$name_interface);
			$y++;
		}
		$output_aps=null;
		return $hay_aps;
	}

	function get_ips($name_interface,$x,$y){
		global $sudo;
		$hay_ips=false;
		$ip=exec("${sudo}ifconfig $name_interface 2>/dev/null|grep 'inet addr'|tr -s ' '|tr -s ':' ' '|cut -d' ' -f 4");
		$mac=exec("${sudo}ifconfig $name_interface 2>/dev/null|grep HWaddr| tr -s ' '|cut -d' ' -f5");
		if ($ip!="" && $mac!="") {
			nodo_ip($y,$x,$ip,$mac,$name_interface);
			$y++;
			$hay_ips=true;
		}
		exec("${sudo}cat /proc/net/arp | grep $name_interface | tr -s ' ' | cut -d' ' -f1,4 | sort",$output_ip);
		foreach ($output_ip as $line_ip){
			$hay_ips=true;
			list($ip,$mac) = explode(" ",$line_ip,2);
			nodo_ip($y,$x,$ip,$mac,$name_interface);
			$y++;
		}
		$output_ip=null;
		return $hay_ips;
	}

	function get_interfaces($x,$y){
		global $sudo;
		exec("${sudo}ifconfig -a 2>/dev/null | grep 'Ethernet' | tr -s ' '| cut -d' ' -f1,5",$output_interface);
		$prevX_interface=$x;
		$num_interface=0;
		foreach ($output_interface as $line_interface){
			list($name_interface,$mac_interface) = explode(" ",$line_interface,2);
			$status1_interface = exec("${sudo}ifconfig 2>/dev/null | grep $name_interface");
			$status2_interface = exec("${sudo}ifconfig -a 2>/dev/null | grep $name_interface");
			$status3_interface = exec("${sudo}iw $name_interface info 2>/dev/null | grep $name_interface");
			if ($status1_interface == $status2_interface) $status="UP"; else $status="DOWN";
			if ($num_interface > 0) $x = $x + 1.1; $num_interface++; 
			if ($x > $prevX_interface+1) enlace($y,$prevX_interface,$x,"blue");
			$hay_aps=false;
			$hay_ips=false;
			$hay=false;
			if ($status3_interface == ""){		
				nodo_interface_eth($y,$x,$name_interface,$mac_interface,$status);
				$prevX_interface = $x;
			} else {
				nodo_interface($y,$x,$name_interface,$mac_interface,$status);
				$prevX_interface = $x;
			}
			if ($status=="UP") {
				$hay_ips = get_ips($name_interface,$x,$y+1);
				if ($hay_ips) $x=$x+1;
				if ( exec("${sudo}ifconfig $name_interface | grep 'inet addr'") == "" ){
					if (file_exists("../cfg/hide_aps_${name_interface}.flag")) $show_aps=false; else $show_aps=true;
					if ($show_aps) $hay_aps = get_aps($name_interface,$x,$y+1);
				}
				if ($hay_ips && $hay_aps===false) $x--;
				$hay = $hay_ips || $hay_aps; 
			}
			list($x,$hay_processes)=get_processes($y+1,$x,$name_interface,$hay);
			$x = get_monitors($y+1,$x,$mac_interface,$hay);
		}
		$output_interface=null;
	}

	function get_data($y,$x,$dir){
		global $sudo;
		$hay_data=false;

		exec("${sudo}ls $dir/* | grep . | grep -v :$ | grep '^\.\.' 2>/dev/null",$output_file);
		foreach ($output_file as $line_file){
			$hay_data=true;
			$file_name=trim($line_file);
			nodo_file($y,$x,$file_name);
			$y++;
		}
		$output_file=null;
		return $hay_data;
	}

	//$time_start = microtime(true);
	$time_start = time();
		$y = 0.5; $x = 0;
		nodo_system($y,$x);
		$hay_data=get_data($y+1,$x,"../data"); if ($hay_data) $x++;
		list($x,$hay_processes)=get_processes($y+1,$x,"nslookup\|aircrack-ng\|hostap\|beef\|set\|live\|uhttpd",false); if ($hay_processes) $x++;
		if ($hay_data || $hay_processes) $x=$x+0.1;
		if ($x==0) $x=1.1;
		enlace($y,0,$x,"green");get_interfaces($x,$y);
	//$time_end = microtime(true);
	$time_end = time();
	$time = $time_end - $time_start;
	//echo "<b>UWUI v0.5 (Universal Web User Interface).</b> RT: ".number_format($time,3)."<br>";
	//echo "<b>UWUI v0.51 (Universal Web User Interface).</b> RT: ".$time."<br>";

?>
