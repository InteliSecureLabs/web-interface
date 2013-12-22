<html>
    <head>
    <title>BlackLister</title>
    <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <link rel="stylesheet" type="text/css" href="/includes/styles.css" />
    <link rel="icon" href="/favicon.ico" type="image/x-icon"> 
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

<SCRIPT LANGUAGE="JavaScript">
<!-- Beginning of JavaScript -

function MsgBox (textstring) {
	alert (textstring)
}


function wopen(url, name, w, h)
{
	w += 32;
	h += 96;
 var win = window.open(url,
  name,
  'width=' + w + ', height=' + h + ', ' +
  'location=no, menubar=no, ' +
  'status=no, toolbar=no, scrollbars=no, resizable=no');
	win.resizeTo(w, h);
	win.focus();
}

// - End of JavaScript - -->
</SCRIPT>

<style type="text/css">
#notSelectableColour {
	color:#606060;
}
#blacklistColour {
	color:red;
}
#whitelistColour {
	color:cyan;
}
#blacklistColour2 {
	color:red;
	font-size:18px;
}
#whitelistColour2 {
	color:cyan;
	font-size:16px;
}
#divBarColour {
	border-style:solid;
	border-width:1px;
	border-color:darkgreen;
}
#enableColour {
	color:lime;
}
#disableColour {
	color:red;
}
</style>

    </head>
<body>

<?php if(file_exists("/pineapple/includes/navbar.php")) require('/pineapple/includes/navbar.php'); ?>
&nbsp;
<br /><br />


<?php

$BWMode = exec("hostapd_cli -p /var/run/hostapd-phy0 karma_get_black_white");


if (file_exists('/etc/config/mac.list') && file_exists('/etc/config/ssid.list')) {
	$macListFile = '/etc/config/mac.list';
	$ssidListFile = '/etc/config/ssid.list';
} else {
	$macListFile = 'mac.list';
	$ssidListFile = 'ssid.list';
}



if(isset($_GET['changeBWMode'])) {

	if(exec ("hostapd_cli -p /var/run/hostapd-phy0 karma_get_black_white") == "BLACK"){
		exec ("hostapd_cli -p /var/run/hostapd-phy0 karma_white");
		$BWMode = 'WHITE';
		$ssidActiveMessage = 'WHITE mode Enabled';
	} else {
		exec ("hostapd_cli -p /var/run/hostapd-phy0 karma_black");
		$BWMode = 'BLACK';
		$ssidActiveMessage = 'BLACK mode Enabled';
	}

}



if (!file_exists('/tmp/mac.blk')) {
	exec('touch /tmp/mac.blk');
}
if (!file_exists('/tmp/mac.wht')) {
	exec('touch /tmp/mac.wht');
}
if (!file_exists('/tmp/ssid.lst')) {
	exec('touch /tmp/ssid.lst');
}


if(isset($_GET['macAutoStart'])) {

	$module_path = exec("pwd")."/";
	exec("sed -i '/blacklister\/mac-autostart.sh/d' /etc/rc.local");

	if ($_GET['macAutoStart'] == 'enable') {
		exec("sed -i '/exit 0/d' /etc/rc.local"); 
		exec("echo ".$module_path."mac-autostart.sh >> /etc/rc.local");
		exec("echo exit 0 >> /etc/rc.local");
		$macMessage = 'Blacklist MAC AutoStart Enabled';
	} else {
		$macMessage = 'Blacklist MAC AutoStart Disabled';
	}
	unset($module_path);
}


if(isset($_GET['ssidAutoStart'])) {

	$module_path = exec("pwd")."/";
	exec("sed -i '/blacklister\/ssid-autostart.sh/d' /etc/rc.local");

	if ($_GET['ssidAutoStart'] == 'enable') {
		exec("sed -i '/exit 0/d' /etc/rc.local"); 
		exec("echo ".$module_path."ssid-autostart.sh ".$BWMode." >> /etc/rc.local");
		exec("echo exit 0 >> /etc/rc.local");
		$ssidMessage = $BWMode.' SSID AutoStart Enabled';
	} else {
		$ssidMessage = 'SSID AutoStart Disabled';
	}
	unset($module_path);
}



if(isset($_GET['whiteListMAC'])) {
	$clientMAC = $_GET['whiteListMAC'];
	$cmd = "hostapd_cli -p /var/run/hostapd-phy0 karma_add_white_mac ".$clientMAC;
	exec($cmd);
	exec("sed -i /".$clientMAC."/d /tmp/mac.blk");
	exec("echo ".$clientMAC." >> /tmp/mac.wht");
	$clientMessage = $clientMAC.' WhiteListed';

	unset($cmd, $clientMAC);
}


if(isset($_GET['blacklistAndDeauthMAC'])) {
	$clientMAC = $_GET['blacklistAndDeauthMAC'];
	$cmd = "hostapd_cli -p /var/run/hostapd-phy0 karma_add_black_mac ".$clientMAC;
	exec($cmd);

	$cmd = "hostapd_cli -p /var/run/hostapd-phy0 disassociate ".$clientMAC;
	exec($cmd);
	exec("echo ".$clientMAC." >> /tmp/mac.blk");
	$clientMessage = $clientMAC.' BlackListed and Disassociated';

	unset($cmd, $clientMAC);
}



if(isset($_GET['deauthMAC'])) {
	$clientMAC = $_GET['deauthMAC'];
	$cmd = "hostapd_cli -p /var/run/hostapd-phy0 disassociate ".$clientMAC;
	exec($cmd);
	$clientMessage = $clientMAC.' Disassociated, Will most likely reconnect';

	unset($clientMAC);
}



if(isset($_GET['delMAC'])) {
	$clientMAC = $_GET['delMAC'];
	$cmd = "sed -i '/".$clientMAC."/d' ".$macListFile;
	exec($cmd);
	$clientMessage = $clientMAC.' Removed from '.$macListFile;
	unset($cmd, $clientMAC);
}



if(isset($_GET['addMAC'])) {
	$clientMAC = $_GET['addMAC'];
	$clientName = $_GET['clientName'];

	$fh = fopen($macListFile, 'r') or die("can't open file");

	$clientMACInUse = false;
	while (!feof($fh)) {

		$line = trim(fgets($fh));
		if (strtolower(substr($line, 0, 17)) == strtolower($clientMAC)) {
			$clientMACInUse = true;
		}

	}

	fclose($fh);

	if ($clientName != '') {
		if ($clientMACInUse == false) {
			if ($line == '') {
				exec('echo '.$clientMAC.' \# '.$clientName.' >> '.$macListFile);
			} else {
				exec('echo >> '.$macListFile);
				exec('echo '.$clientMAC.' \# '.$clientName.' >> '.$macListFile);
			}
		}
	} else {
		if ($clientMACInUse == false) {
			if ($line == '') {
				exec('echo '.$clientMAC.' >> '.$macListFile);
			} else {
				exec('echo >> '.$macListFile);
				exec('echo '.$clientMAC.' >> '.$macListFile);
			}
		}
	}

	$clientMessage = $clientMAC.' Added to '.$macListFile;

	unset($clientName, $clientMAC);

}



if(isset($_POST[addMACBlackList])){
	$macAddress = substr(strtolower($_POST['addMACAddress']), 0, 17);

	if (strlen($_POST['addMACAddress']) == 17) {

		$fh = fopen('/tmp/mac.blk', 'r') or die("can't open file");

		$isMACInList = false;
		while (!feof($fh)) {

			$line = strtolower(trim(fgets($fh)));
			if ($line != '') {
				if ($line == $macAddress) {
					$isMACInList = true;
					break;
				}
			}

		}
		fclose($fh);
		$fh = fopen('/tmp/mac.wht', 'r') or die("can't open file");
		while (!feof($fh)) {

			$line = strtolower(trim(fgets($fh)));
			if ($line != '') {
				if ($line == $macAddress) {
					$isMACInList = true;
					break;
				}
			}

		}
		fclose($fh);



		if ($macAddress == '' || $macAddress == '00:00:00:00:00:00') {
			$clientMessage = 'Not added to Black list';
		} elseif ($isMACInList) {
			$clientMessage = $macAddress.' Already in Black/White list';
		} else {
			exec("hostapd_cli -p /var/run/hostapd-phy0 karma_add_black_mac ".$macAddress." > /tmp/commandOutput.txt");
			$commandOutput = file_get_contents('/tmp/commandOutput.txt');
			exec("rm /tmp/commandOutput.txt");
			if (strpos($commandOutput, "ADDED")) {
				exec("hostapd_cli -p /var/run/hostapd-phy0 disassociate ".$macAddress);
				exec('echo '.$macAddress.' >> /tmp/mac.blk');
				$clientMessage = $macAddress.' Blacklisted';
			} else {
				$clientMessage = $macAddress.' Out of Bounds!';
			}
		}


	} elseif (strlen($_POST['addMACAddress']) > 18) {
		$clientMessage = $_POST['addMACAddress'].' Too big';
	} else {
		$clientMessage = $_POST['addMACAddress'].' Too small';
	}

	unset($IsMACInList, $macAddress, $commandOutput);
}



if(isset($_POST[addMACWhiteList])){
	$macAddress = substr(strtolower($_POST['addMACAddress']), 0, 17);

	if (strlen($_POST['addMACAddress']) == 17) {

		$fh = fopen('/tmp/mac.blk', 'r') or die("can't open file");

		$isMACInList = false;
		while (!feof($fh)) {

			$line = strtolower(trim(fgets($fh)));
			if ($line != '') {
				if ($line == $macAddress) {
					$isMACInList = true;
					break;
				}
			}

		}
		fclose($fh);
		$fh = fopen('/tmp/mac.wht', 'r') or die("can't open file");
		while (!feof($fh)) {

			$line = strtolower(trim(fgets($fh)));
			if ($line != '') {
				if ($line == $macAddress) {
					$isMACInList = true;
					break;
				}
			}

		}
		fclose($fh);



		if ($macAddress == '' || $macAddress == '00:00:00:00:00:00') {
			$clientMessage = 'Not added to White list';
		} elseif ($isMACInList) {
			$clientMessage = $macAddress.' Already in Black/White list';
		} else {
			exec("hostapd_cli -p /var/run/hostapd-phy0 karma_add_white_mac ".$macAddress." > /tmp/commandOutput.txt");
			$commandOutput = file_get_contents('/tmp/commandOutput.txt');
			exec("rm /tmp/commandOutput.txt");
			if (strpos($commandOutput, "ADDED")) {
				exec("sed -i '/".$macAddress."/d' /tmp/mac.blk");
				exec('echo '.$macAddress.' >> /tmp/mac.wht');
				$clientMessage = $macAddress.' Whitelisted';
			} else {
				$clientMessage = $macAddress.' Out of Bounds!';
			}

		}


	} elseif (strlen($_POST['addMACAddress']) > 18) {
		$clientMessage = $_POST['addMACAddress'].' Too big';
	} else {
		$clientMessage = $_POST['addMACAddress'].' Too small';
	}

	unset($IsMACInList, $macAddress, $commandOutput);
}






if(isset($_GET['delSSID'])) {
	$ssidName = $_GET['delSSID'];

	exec('echo > /tmp/tempssid.list');

	$fh = fopen($ssidListFile, 'r') or die("can't open file");

	while (!feof($fh)) {

		$line = trim(fgets($fh));
		if ($line != '') {
			if ($ssidName != $line) {
				exec('echo '.$line.' >> /tmp/tempssid.list');
			}
		}

	}

	fclose($fh);

	exec('cp /tmp/tempssid.list '.$ssidListFile);

	$ssidActiveMessage = $ssidName.' Removed from '.$ssidListFile;

	unset($ssidName);
}



if(isset($_GET['addSSID'])) {
	$ssidName = trim($_GET['addSSID']);

	$fh = fopen($ssidListFile, 'r') or die("can't open file");

	while (!feof($fh)) {

		$line = fgets($fh);

	}

	fclose($fh);

	if ($ssidName != '') {
		if ($line != '') {
			exec('echo >> '.$ssidListFile);
			exec('echo '.$ssidName.' >> '.$ssidListFile);
		} else {
			exec('echo '.$ssidName.' >> '.$ssidListFile);
		}
	}

	$ssidActiveMessage = $ssidName.' Added to '.$ssidListFile;

	unset($ssidName);
}




if(isset($_POST[addSSIDList])){
	$ssidName = '"'.$_POST['addSSIDName'].'"';

	if (strlen($ssidName) < 35) {

		$fh = fopen('/tmp/ssid.lst', 'r') or die("can't open file");

		$isSSIDInList = false;
		while (!feof($fh)) {

			$line = trim(fgets($fh));
			if ($line != '') {
				if ($line == $ssidName) {
					$isSSIDInList = true;
					break;
				}
			}

		}
		fclose($fh);


		if ($ssidName == '' || $ssidName == '"SSID"') {
			$ssidActiveMessage = $ssidName.' Not added to Black/White list';
		} elseif ($isSSIDInList) {
			$ssidActiveMessage = $ssidName.' Already in Black/White list';
		} else {
			exec("./ssid-add-one.sh ".$ssidName);
			$ssidActiveMessage = $ssidName.' added to '.$BWMode.' list';
		}

	} else {
		$ssidActiveMessage = $ssidName.' Too Big.';
	}

	unset($IsSSIDInList, $ssidName, $commandOutput);

}


if(isset($_POST[undoAllSSID])){

	$fh = fopen('/tmp/ssid.lst', 'r') or die("can't open file");

	while (!feof($fh)) {

		$line = trim(fgets($fh));
		if ($line != '') {
			exec("./ssid-undo-one.sh ".$line);
		}

	}

	fclose($fh);
	$ssidActiveMessage = 'All SSIDs removed from Black/White list';

	unset($ssidName);
}


if(isset($_GET['removeSSID'])) {
	$ssidName = "'".$_GET['removeSSID']."'";
	exec("./ssid-undo-one.sh ".$ssidName);

	$ssidActiveMessage = $ssidName.' Removed from Black/White list';

	unset($ssidName);
}



$is_ssidblacklister_onboot = exec("cat /etc/rc.local | grep 'blacklister/ssid-autostart.sh BLACK'") != "" ? 1 : 0;
$is_ssidwhitelister_onboot = exec("cat /etc/rc.local | grep 'blacklister/ssid-autostart.sh WHITE'") != "" ? 1 : 0;


if(isset($_POST['runSSIDBlackLister'])) {
	exec("./ssid-undo-all.sh NULL");
	exec("./ssid-autostart.sh BLACK");
	$ssidMessage = "SSID's Listed, BLACK mode ENABLED";
}
if(isset($_POST['undoSSIDBlackLister'])) {
	exec("./ssid-undo-all.sh BLACK");
	exec("./ssid-undo-all.sh BLACK");
	$ssidMessage = "SSID's Removed from Listing, BLACK mode ENABLED";
}
if(isset($_POST['runSSIDWhiteLister'])) {
	exec("./ssid-undo-all.sh NULL");
	exec("./ssid-autostart.sh WHITE");
	$ssidMessage = "SSID's Listed, WHITE mode ENABLED";
}
if(isset($_POST['undoSSIDWhiteLister'])) {
	exec("./ssid-undo-all.sh WHITE");
	exec("./ssid-undo-all.sh WHITE");
	$ssidMessage = "SSID's Removed from Listing, WHITE mode ENABLED";
}


$is_macblacklister_onboot = exec("cat /etc/rc.local | grep blacklister/mac-autostart.sh") != "" ? 1 : 0;

if(isset($_POST['runMacBlackLister'])) {
	exec("./mac-autostart.sh");
	$macMessage = "MAC addresses BlackListed.";
}
if(isset($_POST['undoMacBlackLister'])) {
	exec("./mac-undo-all.sh");
	$macMessage = "MAC addresses Added to WhiteList, Requires reboot to re-blacklist those MACs.";
}



if(isset($_GET['changeListLocation'])) {

	if (file_exists('/etc/config/mac.list')) {
		exec('cp /etc/config/mac.list mac.list');
		exec('rm /etc/config/mac.list');
		exec('cp /etc/config/ssid.list ssid.list');
		exec('rm /etc/config/ssid.list');
		$macListFile = 'mac.list';
		$ssidListFile = 'ssid.list';
		$listStoredMessage = '/etc/config/mac.list and /etc/config/ssid.list Moved back to Module';
	} else {
		exec('cp mac.list /etc/config/mac.list');
		exec('cp ssid.list /etc/config/ssid.list');
		$macListFile = '/etc/config/mac.list';
		$ssidListFile = '/etc/config/ssid.list';
		$listStoredMessage = 'mac.list and ssid.list Moved to /ect/config/';
	}

}



$filename = $_POST['filename'];
$newdata = $_POST['newdata'];

if ($newdata != "") { $newdata = ereg_replace(13,  "", $newdata);
 $fw = fopen($filename, 'w') or die('Could not open file!');
 $fb = fwrite($fw,stripslashes($newdata)) or die('Could not write to file');
 fclose($fw);
	if ($filename == $macListFile) {
		$macMessage = "Updated ".$filename;
	} else {
		$ssidMessage = "Updated ".$filename;
	}


}

?>

<div class=content>

<center><a name="clientList"><?php if ($clientMessage != '') { echo $clientMessage; } else { echo '&nbsp;'; } ?></a></center>


<div class=contentTitle>Status and Active Clients</div>
<div class=contentContent>

<?php

	$macList = 'z'.strtolower(file_get_contents($macListFile));
	$macBlkList = 'z'.strtolower(file_get_contents("/tmp/mac.blk"));
	$macWhtList = 'z'.strtolower(file_get_contents("/tmp/mac.wht"));
	$arpList = 'z'.file_get_contents("/proc/net/arp");

	$fh = fopen('/tmp/dhcp.leases', 'r') or die("can't open file");

	echo('<table>');

	exec("echo '' > /tmp/mac.usd");
	$macsUsed = '
';

	while (!feof($fh)) {

		$line = trim(fgets($fh));
		if ($line != '') {
			$clientNumber = strtok($line, " ");
			$clientMAC = strtok(" ");
			$clientIP = strtok(" ");
			$clientName = strtok(" ");
			if (strpos($arpList, $clientMAC)) {

				$macsUsed .= $clientMAC.'
';

				echo('<tr><td>');
				echo('DHCP&nbsp; | ');
				if (strpos($macList, $clientMAC)) {
					echo('<a href="blacklister.php?delMAC=');
					echo($clientMAC);
					echo('#clientList">Remove</a>');
				} else {
					echo('<a href="blacklister.php?addMAC=');
					echo($clientMAC);
					echo('&clientName=');
					if ($clientName != '*') {
						echo($clientName);
					}
					echo('#clientList">Add</a>&nbsp;&nbsp;&nbsp;');
				}

				if (strpos($macWhtList, $clientMAC)) {
					echo(' <a href="blacklister.php?deauthMAC=');
					echo($clientMAC);
					echo('&null='.time().'#clientList" title="Will most likely reconnect">Kick</a>');
				} elseif (strpos($macBlkList, $clientMAC)) {
					echo(' <font id="notSelectableColour">Kick</font>');
				} else {
					echo(' <a href="blacklister.php?deauthMAC=');
					echo($clientMAC);
					echo('&null='.time().'#clientList" title="Will most likely reconnect">Kick</a>');
				}

				$clientMACOUI = 'http://standards.ieee.org/cgi-bin/ouisearch?'.strtoupper(str_replace(':', '-', substr($clientMAC, 0, 8)));

				if (strpos($macWhtList, $clientMAC)) {
					echo(' <font id="notSelectableColour" title="Cannot Blacklist after being Whitelisted">Blacklist</font> ');
					echo('<a id="whitelistColour" href="'.$clientMACOUI.'" target="popup" onClick="wopen(\''.$clientMACOUI.'\', \'popup\', 660, 360); return false;">'.$clientMAC.'</a><font id="whitelistColour"> '.$clientName.'</font>');
				} elseif (strpos($macBlkList, $clientMAC)) {
					echo(' <a href="blacklister.php?whiteListMAC=');
					echo($clientMAC);
					echo('#clientList">Whitelist</a>&nbsp;');
					echo('<a id="blacklistColour" href="'.$clientMACOUI.'" target="popup" onClick="wopen(\''.$clientMACOUI.'\', \'popup\', 660, 360); return false;">'.$clientMAC.'</a><font id="blacklistColour"> '.$clientName.'</font>');
				} else {
					echo(' <a href="blacklister.php?blacklistAndDeauthMAC=');
					echo($clientMAC);
					echo('#clientList">Blacklist</a>&nbsp;');
					echo('<a href="'.$clientMACOUI.'" target="popup" onClick="wopen(\''.$clientMACOUI.'\', \'popup\', 660, 360); return false;">'.$clientMAC.'</a> ');
					echo($clientName);
				}

				echo('</td></tr>');
			}
		}

	}

	fclose($fh);
	unset($arpList, $macWhtList, $macBlkList, $clientNumber, $clientMAC, $clientIP, $clientName);


	$fh = fopen('/tmp/mac.wht', 'r') or die("can't open file");

	while (!feof($fh)) {

		$line = trim(fgets($fh));
		if ($line != '') {
			$clientMAC = substr($line,0,17);

			if (!strpos($macsUsed, $clientMAC)) {

				$macsUsed .= $clientMAC.'
';

				echo('<tr><td>');
				echo('WHITE | ');
				if (strpos($macList, $clientMAC)) {
					echo('<a href="blacklister.php?delMAC=');
					echo($clientMAC);
					echo('#clientList">Remove</a>');
				} else {
					echo('<a href="blacklister.php?addMAC=');
					echo($clientMAC);
					echo('&clientName=');
					echo('');
					echo('#clientList">Add</a>&nbsp;&nbsp;&nbsp;');
				}

				$clientMACOUI = 'http://standards.ieee.org/cgi-bin/ouisearch?'.strtoupper(str_replace(':', '-', substr($clientMAC, 0, 8)));

				echo(' <font id="notSelectableColour">Kick</font>');
				echo(' <font id="notSelectableColour" title="Cannot Blacklist after being Whitelisted">Blacklist</font> ');
				echo('<a id="whitelistColour" href="'.$clientMACOUI.'" target="popup" onClick="wopen(\''.$clientMACOUI.'\', \'popup\', 660, 360); return false;">'.$clientMAC.'</a>');
				
				echo('</td></tr>');
			}
		}

	}

	fclose($fh);

	$fh = fopen('/tmp/mac.blk', 'r') or die("can't open file");
	while (!feof($fh)) {

		$line = trim(fgets($fh));
		if ($line != '') {
			$clientMAC = substr($line,0,17);

			if (!strpos($macsUsed, $clientMAC)) {

				$macsUsed .= $clientMAC.'
';

				echo('<tr><td>');
				echo('BLACK | ');
				if (strpos($macList, $clientMAC)) {
					echo('<a href="blacklister.php?delMAC=');
					echo($clientMAC);
					echo('#clientList">Remove</a>');
				} else {
					echo('<a href="blacklister.php?addMAC=');
					echo($clientMAC);
					echo('&clientName=');
					echo('');
					echo('#clientList">Add</a>&nbsp;&nbsp;&nbsp;');
				}

				
				echo(' <font id="notSelectableColour">Kick</font>');

				$clientMACOUI = 'http://standards.ieee.org/cgi-bin/ouisearch?'.strtoupper(str_replace(':', '-', substr($clientMAC, 0, 8)));

				echo(' <a href="blacklister.php?whiteListMAC=');
				echo($clientMAC);
				echo('#clientList">Whitelist</a> ');
				echo('<a id="blacklistColour" href="'.$clientMACOUI.'" target="popup" onClick="wopen(\''.$clientMACOUI.'\', \'popup\', 660, 360); return false;">'.$clientMAC.'</a>');
								
				echo('</td></tr>');
			}
		}

	}


	echo('</table>');


	fclose($fh);
	unset($macList, $macsUsed, $clientMACOUI);

	echo('<hr id="divBarColour">');


?>
<table>
<tr>
<td>
<form action='<?php echo $_SERVER[php_self]."?null=".time()."#clientList" ?>' method= 'post' >
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MAC Address <input type="text" name="addMACAddress" size='17' value="00:00:00:00:00:00" onFocus="if(this.value == '00:00:00:00:00:00') {this.value = '';}" onBlur="if (this.value == '') {this.value = '00:00:00:00:00:00'}">
<input type='submit' name='addMACBlackList' value='BlackList'> <input type='submit' name='addMACWhiteList' value='WhiteList'>
</form>
</td>
</tr>
</table>

</div>

<center><a name="macList"><?php if ($macMessage != '') { echo $macMessage; } else { echo '&nbsp;'; } ?></a></center>


<div class=contentTitle>MAC Address BlackListing</div>
<div class=contentContent>


<?php

if ($is_macblacklister_onboot) {
	echo "AutoStart  <font id='enableColour'><b>Enabled</b></font>&nbsp; | <a href=\"blacklister.php?macAutoStart=disable#macList\"><b>Disable</b></a><br />";
} else {
	echo "AutoStart  <font id='disableColour'><b>Disabled</b></font> | <a href=\"blacklister.php?macAutoStart=enable#macList\"><b>Enable</b></a>";
}

?>

<br /><br />

<table border="0">
<tr>
<td><form action="<?=$_SERVER['PHP_SELF'];?>#clientList" method="post"><input type="submit" name="runMacBlackLister" value="Add all to BlackList"></form></td>
<td><form action="<?=$_SERVER['PHP_SELF'];?>#clientList" method="post">&nbsp; &nbsp;<input title='Requires reboot to blacklist those MACs again!!!' type="submit" name="undoMacBlackLister" value="Add all to WhiteList"></form></td>
</tr>
</table>


<center style="padding-bottom:10px;">List of MAC addresses (one per line)</center>

<?php
$data = file_get_contents($macListFile);
echo "<form action='blacklister.php#macList' method= 'post' >
<textarea name='newdata' rows='20' style='min-width:100%; background-color:black; color:white; border-style:dashed;'>".$data."</textarea>
<input type='hidden' name='filename' value='".$macListFile."'>
<br><center><input type='submit' value='Update MAC List'>
</form>";

unset($data);

?>
<br />
</div>
<br /><br />

<center><a name="ssidActiveList"><?php if ($ssidActiveMessage != '') { echo $ssidActiveMessage; } else { echo '&nbsp;'; } ?></a></center>


<div class=contentTitle>SSID's Active/Listed</div>
<div class=contentContent>

<?php

	$fh = fopen($ssidListFile, 'r') or die("can't open file");

	$ssidsInList = ' ';
	while (!feof($fh)) {

		$line = trim(fgets($fh));
		if ($line != '') {
			$ssidsInList = $ssidsInList.'"'.$line.'"';
		}

	}
	fclose($fh);


	$fh = fopen('/tmp/ssid.lst', 'r') or die("can't open file");

	echo('<table>');

	while (!feof($fh)) {

		$line = trim(fgets($fh));
		if ($line != '') {

			$ssidName = str_replace('"', '', $line);
			
			echo('<tr><td>');

			if (strpos($ssidsInList, $line)) {
				echo('<a href="blacklister.php?delSSID=');
				echo($ssidName);
				echo('#ssidActiveList">Remove</a>&nbsp;&nbsp;');
			} else {
				echo('<a href="blacklister.php?addSSID=');
				echo($ssidName);
				echo('#ssidActiveList">Add</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			}


			echo('<a href="blacklister.php?removeSSID=');
			echo($ssidName);
			echo('#ssidActiveList">Undo</a>&nbsp;&nbsp;');

			if ($BWMode == 'BLACK') {
				echo('<font id="blacklistColour">'.$ssidName.'</font>');
			} else {
				echo('<font id="whitelistColour">'.$ssidName.'</font>');
			}

			echo('</td></tr>');

		}

	}

	fclose($fh);
	echo('</table>');
	unset($ssidsInList);

	echo('<hr id="divBarColour">');

?>
<table>
<tr>
<td>
<?php

if ($BWMode == 'BLACK') { 
	echo("<a id='blacklistColour2'"); 
} else {
	echo("<a id='whitelistColour2'");
}
echo(" href='blacklister.php?changeBWMode=".$BWMode."#ssidActiveList'>".$BWMode."LIST-MODE</a>");
?>

</td>
<td style="padding-top:15px;">
<form action='<?php echo $_SERVER[php_self]."?null=".time()."#ssidActiveList" ?>' method= 'post' >
<?php if ($BWMode == 'BLACK') { echo('&nbsp;&nbsp;'); } else { echo('&nbsp;&nbsp;&nbsp;&nbsp;'); } ?><input type="text" name="addSSIDName" size='32' value="SSID" onFocus="if(this.value == 'SSID') {this.value = '';}" onBlur="if (this.value == '') {this.value = 'SSID'}">
<input type='submit' name='addSSIDList' value='Add to List'></form>
</td>
<td style="padding-top:15px;"><form action="<?=$_SERVER['PHP_SELF'];?>#ssidActiveList" method="post">&nbsp;&nbsp;<input type="submit" name="undoAllSSID" value="Undo All"></form></td>
</tr>
</table>

</div>


<center><a name="ssidList"><?php if ($ssidMessage != '') { echo $ssidMessage; } else { echo '&nbsp;'; } ?></a></center>


<div class=contentTitle><a name="ssidList">SSID Black/White Listing</a></div>
<div class=contentContent>



<?php

if ($is_ssidblacklister_onboot) {
	echo "AutoStart BLACK  <font id='enableColour'><b>Enabled</b></font> | <a href=\"blacklister.php?ssidAutoStart=disable#ssidList\"><b>Disable</b></a>";
} elseif ($is_ssidwhitelister_onboot) {
	echo "AutoStart WHITE  <font id='enableColour'><b>Enabled</b></font> | <a href=\"blacklister.php?ssidAutoStart=disable#ssidList\"><b>Disable</b></a>";
} else {
	echo("AutoStart ".$BWMode." <font id='disableColour'><b>Disabled</b></font> | <a href='blacklister.php?ssidAutoStart=enable#ssidList'><b>Enable</b></a>");
}

?>


<br /><br />
<table border="0">
<tr>
<?php

	if ($BWMode == 'BLACK') {
		echo("<td><form action='".$_SERVER['PHP_SELF']."#ssidActiveList' method='post'><input type='submit' name='runSSIDBlackLister' value='Add all to BlackList'></form></td>");
		echo("<td><form action='".$_SERVER['PHP_SELF']."#ssidActiveList' method='post'>&nbsp; &nbsp;<input type='submit' name='undoSSIDBlackLister' value='Undo all from BlackList'></form></td>");
	} else {
		echo("<td><form action='".$_SERVER['PHP_SELF']."#ssidActiveList' method='post'><input type='submit' name='runSSIDWhiteLister' value='Add all to WhiteList'></form></td>");
		echo("<td><form action='".$_SERVER['PHP_SELF']."#ssidActiveList' method='post'>&nbsp; &nbsp;<input type='submit' name='undoSSIDWhiteLister' value='Undo all from WhiteList'></form></td>");
	}
?>
</tr>
</table>


<center style="padding-bottom:10px;">List of SSID's (one per line)</center>

<?php

$data = file_get_contents($ssidListFile);
echo "<form action='blacklister.php#ssidList' method= 'post' >
<textarea name='newdata' rows='20' style='min-width:100%; background-color:black; color:white; border-style:dashed;'>".$data."</textarea>
<input type='hidden' name='filename' value='".$ssidListFile."'>
<br><center><input type='submit' value='Update SSID List'>
</form>";

unset($data);

?>
</div><br /><br />


</div>


<center><a name="listStored"><?php if ($listStoredMessage != '') { echo $listStoredMessage; } else { echo '&nbsp;'; } ?></a></center>
<?php

if (file_exists('/etc/config/mac.list')) {
	echo("Lists stored on Pineapple | <a title='Having the lists stored on the pineapple keeps them from being wiped when upgrading/removing blacklister' href='blacklister.php?changeListLocation=disable#listStored'><b>Move back to Module</b></a>");
} else {
	echo("Lists stored within Module | <a title='Having the lists stored on the pineapple keeps them from being wiped when upgrading blacklister' href='blacklister.php?changeListLocation=enable#listStored'><b>Move to Pineapple</b></a>");
}


?>

</body>
</html>