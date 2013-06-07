<html>
    <head>
    <title>RandomRoll</title>
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
img.transbox
  {
  opacity:0.32;
  }


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

$rolls = array();
$rollsEnabled = array();
$rollsCreator = array();
$rollsCreatorLink = array();
$maxRolls = 0;
foreach(glob('randomroll/*', GLOB_ONLYDIR) as $dir) {
	$maxRolls = $maxRolls + 1;
	$rolls[$maxRolls] = str_replace("randomroll/","" ,$dir);

	if (file_exists('randomroll/'.$rolls[$maxRolls].'/creator.ini')) {
		$fh = fopen('randomroll/'.$rolls[$maxRolls].'/creator.ini', 'r') or die("can't open file");
		if (!feof($fh)) {
			$rollsCreator[$maxRolls] = trim(fgets($fh));
		}
		if (!feof($fh)) {
			$rollsCreatorLink[$maxRolls] = trim(fgets($fh));
		}

		fclose($fh);
	}

} 




$spoofHostIP = exec("ifconfig br-lan | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'");


function echoLog() {

	echo('<br/><br/><br/>
<div class=contentTitle id=randomroll>Random Roll Log (<a href="randomroll.php?blah='.time().'#randomroll">Refresh</a>)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/usb/infusions/randomroll/logs/RandomRoll.log</div>
<div class=contentContent>');


	if (file_exists('logs/RandomRoll.log')) {

		$fh = fopen('logs/RandomRoll.log', 'r') or die("can't open file");
		$count = 0;
		$outputArray[0] = "test";
		while (!feof($fh)) {
			if ($count < 256) {
				$line = trim(fgets($fh));
				if ($line != '') {

					$dateAndTime = trim(strtok($line, "|"));
					$clientMAC = trim(strtok("|"));
					$clientIP = trim(strtok("|"));
					$tempLen = strlen($clientIP);
					while ($tempLen < 16) {
						$clientIP = $clientIP.'&nbsp;';
						$tempLen = $tempLen + 1;
					}
					$clientName = trim(strtok("|"));
					$rollType = trim(strtok("|"));
					$tempLen = strlen($rollType);
					while ($tempLen < 10) {
						$rollType = $rollType.'&nbsp;';
						$tempLen = $tempLen + 1;
					} 

					$refererURLLong = strtok("|");
					$refererURL = substr(str_replace('http://', '', $refererURLLong), 0, 46);

					$clientMACOUI = 'http://standards.ieee.org/cgi-bin/ouisearch?'.strtoupper(str_replace(':', '-', substr($clientMAC, 0, 8)));
					$outputArray[$count] = '<tr><td>'.$dateAndTime." | <a title='".$clientName."' href='".$clientMACOUI."' target='popup' onClick='wopen(\"".$clientMACOUI."\", \"popup\", 660, 360); return false;'>".$clientMAC."</a> | ".$clientIP."| ".$rollType."| <a href='".$refererURLLong."' target='_blank'>".$refererURL."</a></td></tr>";
					$count = $count + 1;

				}
			} else {
				break;
			}

		}

		if ($count > 255) {
			echo('255+ entries<br/>Please refer to logs/RandomRoll.log for the rest<br/><br/>');
		} elseif ($count < 2) {
			echo('1 entry');
		} else {
			echo($count.' entries');
		}


		echo('<table>');

		$outputArrayReverse = array_reverse($outputArray);
		unset($outputArray);
		foreach($outputArrayReverse as $outputline) {
			echo ($outputline);
		}

		echo('</table>');
	}

	echo('</div>');

	unset($fh, $line, $count, $dateAndTime, $clientMAC, $clientIP, $clientName, $tempLen, $rollType, $refererURLLong, $refererURL, $outputArrayReverse);
}



if(isset($_GET['landingPage'])) {
	$landingPage = $_GET['landingPage'];

	if ($landingPage == 'enable') {
		$file1 = file_get_contents('/www/index.php');
		if (! strpos($file1, 'Random Roll by petertfm.')) {
			exec('cp /www/index.php /www/index.php.bak');
		}
		exec('cp files/index.php /www/index.php');
	} else {
		if (file_exists('/www/index.php.bak')) {
			exec('cp /www/index.php.bak /www/index.php');
			exec('rm /www/index.php.bak');
		} else {
			exec('cp files/index.php.bak /www/index.php');
		}
	}

	unset($landingPage);
}


if(isset($_GET['spoofHost'])) {
	$spoofHost = $_GET['spoofHost'];

	if ($spoofHost == 'enable') {
		exec('cp /pineapple/config/spoofhost /pineapple/config/spoofhost.bak');
		$brlanIP = exec("ifconfig br-lan | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'");
		exec('echo '.$brlanIP.'" *" > /pineapple/config/spoofhost');
		
		exec('cp /pineapple/config/spoofhost files/spoofhost');
	} else {
		exec('cp /pineapple/config/spoofhost.bak /pineapple/config/spoofhost');
		exec('rm /pineapple/config/spoofhost.bak');
	}

	unset($spoofHost, $brlanIP);
}


if(isset($_GET['symLink'])) {
	$symLink = $_GET['symLink'];
	$module_path = exec("pwd")."/";

	if ($symLink == 'enable') {
		exec('ln -s '.$module_path.'randomroll /www/randomroll');
	} else {
		exec('rm /www/randomroll');
	}

	unset($symLink);
}


if(isset($_GET['dnsspoof'])) {
	$dnsspoof = $_GET['dnsspoof'];

	if ($dnsspoof == 'enable') {
		exec('./setup-log.sh');
		exec("echo '' > /pineapple/logs/dnsspoof.log");
		exec("echo /pineapple/dnsspoof/dnsspoof.sh | at now");
	} else {
		exec('killall dnsspoof');
	}

	sleep(2);

	unset($dnsspoof);
}



if(isset($_GET['install'])) {
	$install = $_GET['install'];

	if ($install == 'install') {
		exec('./install.sh');
	} else {
		exec('./revert.sh');
		sleep(1);
	}

	unset($install);
}


if(isset($_GET['autoStart'])) {

	$module_path = exec("pwd")."/";
	exec("sed -i '/randomroll\/autostart.sh/d' /etc/rc.local");

	if ($_GET['autoStart'] == 'enable') {
		exec("sed -i '/logs\/dnsspoof.log/d' /etc/rc.local");
		exec("sed -i '/dnsspoof\/dnsspoof.sh/d' /etc/rc.local");

		exec("sed -i '/exit 0/d' /etc/rc.local"); 
		exec("echo ".$module_path."autostart.sh \& >> /etc/rc.local");
		exec("echo exit 0 >> /etc/rc.local");
	}
	unset($module_path);
}



if (isset($_POST['updateRolls'])) {

	exec('echo \'<?php\' > /tmp/temp-index.php');
	exec('echo "" >> /tmp/temp-index.php');

	$tempVar = '';
	$separator = 'false';
	$atLeastOne = 0;

	for ($i = 1; $i <= $maxRolls; $i++) {

		if ($_POST[$rolls[$i]] == '1') {
			$atLeastOne = 1;
			if ($separator == 'true') {
				$tempVar = $tempVar.', ';
			}
			$tempVar = $tempVar.'"randomroll/'.$rolls[$i].'/index.html"';
			$separator = 'true';
		}

	}

	if ($atLeastOne == 0) {
		$tempVar = '';
		$separator = 'false';

		for ($i = 1; $i <= $maxRolls; $i++) {
			if ($rolls[$i] != 'trap') {
				if ($separator == 'true') {
					$tempVar = $tempVar.', ';
				}
				$tempVar = $tempVar.'"randomroll/'.$rolls[$i].'/index.html"';
				$separator = 'true';

			}
		}

	}

	exec('echo \'$mypages = array('.$tempVar.');\' >> /tmp/temp-index.php');

	exec('echo "" >> /tmp/temp-index.php');

	exec('echo \'$myrandompage = $mypages[mt_rand(0, count($mypages) -1)];\' >> /tmp/temp-index.php');
	exec('echo \'include($myrandompage);\' >> /tmp/temp-index.php');
	exec('echo "" >> /tmp/temp-index.php');
	exec('echo \'/*\' >> /tmp/temp-index.php');
	exec('echo \'	Random Roll by petertfm.\' >> /tmp/temp-index.php');
	exec('echo \'	Original landing page backed up: /www/index.php.bak\' >> /tmp/temp-index.php');
	exec('echo "	Please Don\'t edit this file, Use RandomRoll module to uninstall/revert." >> /tmp/temp-index.php');
	exec('echo \'*/\' >> /tmp/temp-index.php');
	exec('echo "" >> /tmp/temp-index.php');
	exec('echo \'?>\' >> /tmp/temp-index.php');

	exec('cp /tmp/temp-index.php files/index.php');
	exec('cp /tmp/temp-index.php /www/index.php');
	exec('rm /tmp/temp-index.php');

	unset($tempVar);
}


$landingPage = file_get_contents('/www/index.php');

for ($count = 1; $count <= $maxRolls; $count++) {

	if (strpos($landingPage, $rolls[$count].'/index.html')) {
		$rollsEnabled[$count] = 'true';
	} else {
		$rollsEnabled[$count] = 'false';
	}

}

unset($landingPage);

?>

<div class=content style="min-width:975px; max-width:975px">


<div class=contentTitle><a name="randomroll">Random Roll Set-up</a></div>
<div class=contentContent>

<table>
<tr>
<td style="min-width:465px; max-width:465px">
<?php

echo('<table><tr><td>');

$isInstalled = 'false';
$isNotInstalled = 'false';

$file1 = file_get_contents('/www/index.php');

if (strpos($file1, 'Random Roll by petertfm.')) {
	echo('1. <font id="enableColour"><b>Landing Page: /www/index.php</b></font>&nbsp;&nbsp;<a href="randomroll.php?landingPage=revert"><b>Revert</b></a>');
	$isInstalled = 'true';
} else {
	echo('1. <font id="disableColour"><b>Landing Page: /www/index.php</b></font>&nbsp;&nbsp;<a href="randomroll.php?landingPage=enable"><b>Enable</b></a>');
	$isNotInstalled = 'true';
}

echo('</td></tr><tr><td>');

$file1 = file_get_contents('/pineapple/config/spoofhost');
$file2 = file_get_contents('files/spoofhost');

if (! file_exists('/pineapple/config/spoofhost.bak')) {
	if ($file1 == $file2) {
		exec('cp files/spoofhost.bak /pineapple/config/spoofhost.bak');
	}
}

$spoofHostIPSpaced = $spoofHostIP.' *';
$tempLen = strlen($spoofHostIPSpaced);
while ($tempLen < 18) {
	$spoofHostIPSpaced = $spoofHostIPSpaced.'&nbsp;';
	$tempLen = $tempLen + 1;
}

if ($file1 == $file2) {
	echo('2. <font id="enableColour"><b>Spoof Host: '.$spoofHostIPSpaced.'</b></font><a href="randomroll.php?spoofHost=revert"><b>Revert</b></a>');
	$isInstalled = 'true';
} else {
	echo('2. <font id="disableColour"><b>Spoof Host: '.$spoofHostIPSpaced.'</b></font><a href="randomroll.php?spoofHost=enable"><b>Enable</b></a>');
	$isNotInstalled = 'true';
}

echo('</td></tr><tr><td>');

if (is_link('/www/randomroll')) {
	echo('3. <font id="enableColour"><b>SymLink: /www/randomroll/</b></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="randomroll.php?symLink=remove"><b>Remove</b></a>');
	$isInstalled = 'true';
} else {
	echo('3. <font id="disableColour"><b>SymLink: /www/randomroll/</b></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="randomroll.php?symLink=enable"><b>Enable</b></a>');
	$isNotInstalled = 'true';
}

echo('</td></tr><tr><td>');

echo('<br/>');

$isdnsspoofup = exec("ps | grep dnsspoof.sh | grep -v -e grep");
if ($isdnsspoofup != "") {
	echo('4. DNSSpoof: <font id="enableColour"><b>Running</b></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="randomroll.php?dnsspoof=disable"><b>Stop</b></a>');
} else { 
	echo('4. DNSSpoof: <font id="disableColour"><b>Not Running</b></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="randomroll.php?dnsspoof=enable"><b>Start</b></a>');
}

echo('</td></tr></table>');


echo('</td><td style="min-width:465px; max-width:465px" valign="top" align="right">');


$is_randomroll_onboot = exec("cat /etc/rc.local | grep randomroll/autostart.sh") != "" ? 1 : 0;
if ($is_randomroll_onboot) {
	echo('AutoStart  <font id="enableColour"><b>Enabled</b></font>&nbsp; | <a href="randomroll.php?autoStart=disable"><b>Disable</b></a>');
} else {
	echo('AutoStart  <font id="disableColour"><b>Disabled</b></font> | <a href="randomroll.php?autoStart=enable"><b>Enable</b></a>');
}

echo('<br/><br/><br/><br/><br/>');

if ($isInstalled == 'true') {
	if ($isNotInstalled == 'true') {
		echo('<a href="randomroll.php?install=install#ssidList"><b>Install</b></a>&nbsp;|&nbsp;');
	} else {
		echo('<font id="notSelectableColour"><b>Install</b></font>&nbsp;|&nbsp;');
	}
	echo('<a href="randomroll.php?install=revert#ssidList"><b>Un-Install</b></a>');
} else {
	echo('<a href="randomroll.php?install=install#ssidList"><b>Install</b></a>&nbsp;|&nbsp;');
	if ($isNotInstalled != 'true') {
		echo('<a href="randomroll.php?install=revert#ssidList"><b>Un-Install</b></a>');
	} else {
		echo('<font id="notSelectableColour"><b>Un-Install</b></font>');
	}
}




?>
</td>
</tr>
</table>

<br/><br/>

<?php

if (! file_exists('/www/index.php.bak')) {

	echo('</div>');

	echoLog();

	echo('</div></body></html>');

	exit();
}


?>

<form action="randomroll.php" method="post">
<center>
<table>
<?php

echo('<tr>');
$count2 = 0;
for ($count = 1; $count <= $maxRolls; $count++) {
	#$rolls[$count] = str_replace("randomroll/","" ,$dir);

	if ($count2 >= 6) {
		echo('</tr><tr>');

		for ($i = $count - 6; $i < $count; $i++) {
			echo('<td></td><td><a href="'.$rollsCreatorLink[$i].'" target="_blank">'.$rollsCreator[$i].'</a></td>');
		}

		echo('</tr><tr><td><br/></td></tr><tr>');
		$count2 = 0;
	}

	echo('<td><input type="checkbox" name="'.$rolls[$count].'" value="1"');
	if ($rollsEnabled[$count] == 'true') {
		echo(' checked');
	}
	echo(' /></td>');


	echo('<td><a href="randomroll/'.$rolls[$count].'/index2.html" target="_blank"><img ');
	if ($rollsEnabled[$count] != 'true') {
		echo('class="transbox" ');
	}

	$thumbnail = exec('ls randomroll/'.$rolls[$count].'/thumbnail.*');

	echo('width="80px" hight="80px" src="'.$thumbnail.'" /></a>&nbsp;&nbsp;&nbsp;&nbsp;</td>');

	$count2 = $count2 + 1;

}


echo('</tr><tr>');

for ($i = $count - $count2; $i < $count; $i++) {
	echo('<td></td><td><a href="'.$rollsCreatorLink[$i].'" target="_blank">'.$rollsCreator[$i].'</a></td>');
}
echo('</tr><tr><td><br/></td></tr>');

?>
</table>

<input type='submit' name='updateRolls' value='Update Rolls'>

</center>
</form>



</div>

<?php
echoLog();
?>

</div>
<br/><br/><br/>

Version 3.0 up, you can now upload your own rolls via scp > /usb/infusions/randomroll/randomroll/YourRollName<br/>
Have a look at how (pbj) works and you will get the idea of how to make your own;-) "How to create your own roll.txt"<br/><br/>

</body>
</html>