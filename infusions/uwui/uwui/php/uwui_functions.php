<?php
	include "uwui_vars.php";

	function show_mac($mac) {
		global $demo;
		$mac=strtoupper($mac);
		if ($demo) return substr($mac,0,9)."XX:XX:XX"; else return $mac;
	}
	function nodo($top,$left,$color,$color2,$icono,$icono2,$info,$tipo,$value,$value2,$value3) {
		$top_px=10+($top*(36+10));
		$left_px=10+($left*(140+10));
		echo "<div class='nodo' style='top:${top_px}px;left:${left_px}px;border-color:${color};background-color:${color2};'>";
		echo "<div class='icono' style='background-image:url(${icono});' onclick='info($top_px,$left_px+150,\"$tipo\",\"$value\",\"$value2\",\"$value3\")'>";
		if ($icono2!="") echo "<img src='$icono2'>";
		echo "</div><div class='info' onclick='actions($top_px,$left_px+150,\"$tipo\",\"$value\",\"$value2\",\"$value3\")'>${info}</div></div>\n";
	}
	function enlace($top,$left,$left2,$color) {
		$top_px=10+($top*(36+10))+(36/2);
		$left_px=10+($left*(140+10))+(140+10)-7;
		$width_px=(($left2-1)*(140+10))-($left*(140+10))+5;
		echo "<div class='enlace' style='top:${top_px}px;left:${left_px}px;min-width:${width_px}px;background:${color};'></div>\n";
	}
	function nodo_system($top,$left) {
		global $sudo,$system;
		if (exec("${sudo}cat /proc/sys/net/ipv4/ip_forward")=="1") $icon="icons/ipforward.png"; else $icon="";
		if ($system!="pineapple") $hostname = exec("${sudo}hostname 2>/dev/null"); else $hostname = exec("cat /etc/config/system | grep hostname | cut -d\"'\" -f2");
		nodo($top,$left,"green","black","icons/equipo.png","$icon","$hostname","system","","","");
	}
	function nodo_interface($top,$left,$name,$mac,$status) {
		global $sudo;
		$essid=exec("${sudo}iwconfig $name | grep ESSID | grep '\"' | cut -d'\"' -f2");
		if ($essid!="") $mac_essid="<font color=blue>${essid}</font>"; else $mac_essid=show_mac($mac);
		if ($status=="UP") {$color="green";} else {$color="red";}
		$info="$name<br>$mac_essid<br><font color=$color><strong>Status:$status</strong></font>";
		nodo($top,$left,"blue","black","icons/monitor.png","","$info","interface","$name","$status","");
	}
	function nodo_interface_eth($top,$left,$name,$mac,$status) {
		if ($status=="UP") {$color="green";} else {$color="red";} 
		$info="$name<br>".show_mac($mac)."<br><font color=$color><strong>Status:$status</strong></font>";
		nodo($top,$left,"blue","black","icons/interface.png","","$info","interface_eth","$name","$status","");	
	}
	function nodo_monitor($top,$left,$name) {
		$info="$name";
		nodo($top,$left,"blue","black","icons/monitor.png","","$info","monitor","$name","","");	
	}
	function nodo_file($top,$left,$name) {
		global $sudo;
		$file_size=exec("${sudo}ls -lh $name 2>/dev/null | tr -s ' ' | cut -d' ' -f5");
		$info=basename($name)."<br>$file_size";
		nodo($top,$left,"orange","black","icons/fichero.png","","$info","file","$name","","");
	}
	function nodo_proceso($top,$left,$name,$pid) {
		$info="$name<br>PID: $pid";
		nodo($top,$left,"red","black","icons/proceso.png","","$info","proceso","$pid","$name","");
	}
	function nodo_ap($top,$left,$essid,$bssid,$quality,$signal_level,$name_interface) {
		$essid=substr($essid, 0, 17);
		$info="$essid<br>$signal_level [$quality]<br>".show_mac($bssid);
		if (file_exists("../data/vault/".trim($bssid).".key")) $color="green"; else $color="yellow";
		nodo($top,$left,"$color","black","icons/ap.png","","$info","ap","$essid","$name_interface","$bssid");
	}
	function nodo_ap_monitor($top,$left,$essid,$bssid,$secu,$file_name,$state) {
		$color="green"; $icon="";
		if ($secu=="WEP") {$color="yellow";$icon="icons/wep.png";}
		if ($secu=="WPA") {$color="blue";$icon="icons/wpa.png";}
		if ($secu=="WPA2" || $secu=="WPA2WPA") {$color="red";$icon="icons/wpa2.png";}
		$essid=substr($essid, 0, 17);
		$info="$essid<br>".show_mac($bssid)."<br><font color=$color>$secu</font>";
		if (file_exists("../data/vault/".trim($bssid).".key")) $color="yellow"; else $color="green";
		if ($state=="online") {
			nodo($top,$left,"$color","black","icons/ap.png","$icon","$info","ap_monitor","$bssid","$file_name","");
		} else {
			nodo($top,$left,"#444444","#333333","icons/ap.png","$icon","$info","ap_monitor","$bssid","$file_name","");
		}
	}
	function nodo_station($top,$left,$mac,$bssid,$file_name,$state,$time,$match) {
		global $sudo,$system;
		$icon2="";
		$bssid=trim($bssid);
		if (strlen($bssid)==17) {$color="white";} else {$color="red";$icon="icons/offline.png";}
		if (file_exists("../data/vault/$mac.target")) $icon2="icons/target.png";
		if (file_exists("../data/vault/$mac.alias")) {
			$text=exec("${sudo}cat ../data/vault/$mac.alias 2>/dev/null");
			$alias="<br><font color=red>$text</font>";
		} else $alias="";
		if ($match===true) $icon="icons/target.png"; else $icon="icons/station.png";
		$mac_min=strtoupper(substr($mac,0,8));
		$fabricante=show_mac($bssid);
		if ($system!="pineapple") $fabricante="<font color=green>".substr(exec("strings ../nic.txt | grep $mac_min | cut -d' ' -f2"),0,17)."</font>";
		$info=show_mac($mac)."<br>$fabricante$alias";
		if ($state=="online") {
			nodo($top,$left,"yellow","black","$icon","$icon2","$info","station","$mac","$file_name","");
		} else {
			nodo($top,$left,"#444444","#333333","$icon","$icon2","$info","station","$mac","$file_name","");
		}	
	}
	function nodo_ip($top,$left,$ip,$mac,$file_name) {
		global $sudo;
		$mac=strtoupper($mac);
		if (file_exists("../data/vault/$mac.target")) $icon="icons/target.png"; else $icon="";
		if (file_exists("../data/vault/$mac.alias")) {
			$text=exec("${sudo}cat ../data/vault/$mac.alias 2>/dev/null");
			$alias="<br><font color=red>$text</font>";
		} else $alias="";
		$info="$ip<br>".show_mac($mac)."${alias}";
		nodo($top,$left,"green","black","icons/ip.png","$icon","$info","ip","$ip","$file_name","$mac");
	}
	function nodo_port($top,$left,$port) {
		$info="$port";
		nodo($top,$left,"white","black","icons/monitor.png","","$info","port","$port","","");
	}
?>
