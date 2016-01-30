<?php
$filename = $_POST['filename'];
$newdata = $_POST['newdata'];

if ($newdata != "") { $newdata = ereg_replace(13,  "", $newdata);
 $fw = fopen($filename, 'w') or die('Could not open file!');
 $fb = fwrite($fw,stripslashes($newdata)) or die('Could not write to file');
 fclose($fw);
 $fileMessage = $strings["config-updated"]." " . $filename . "<br /><br />";
} ?>

<?php
if(isset($_POST[newSSID])){
        if(isset($_POST[newSSIDPersistent])){
                exec("echo \"$(sed 's/option ssid.*/option ssid \"".$_POST[newSSID]."\"/g' /etc/config/wireless)\" > /etc/config/wireless");
                $message = $strings["config-message-persistent"]."<br />";
        }
exec("hostapd_cli -p /var/run/hostapd-phy0 karma_change_ssid \"".$_POST[newSSID]."\"");
	$message .= $strings["config-message-ssidChange"]." \"".$_POST[newSSID]."\"<br /><br />";

}

if(isset($_POST[ssidBW])){
        if(isset($_POST[addSSID])){
                exec("hostapd_cli -p /var/run/hostapd-phy0 karma_add_ssid ".$_POST[ssidBW]);
                $message = "\"".$_POST[ssidBW]."\" ".$strings["config-message-added"]."<br /><br />";
        }
        if(isset($_POST[removeSSID])){
                exec("hostapd_cli -p /var/run/hostapd-phy0 karma_del_ssid ".$_POST[ssidBW]);
                $message = "\"".$_POST[ssidBW]."\" ".$strings["config-message-removed"]."<br /><br />";
        }

}

if(isset($_POST[macBW])){
        if(isset($_POST[addMAC])){
                exec("hostapd_cli -p /var/run/hostapd-phy0 karma_add_black_mac  ".$_POST[macBW]);
                $message = "\"".$_POST[macBW]."\" ".$strings["config-message-added"]."<br /><br />";
        }
        if(isset($_POST[removeMAC])){
                exec("hostapd_cli -p /var/run/hostapd-phy0 karma_add_white_mac ".$_POST[macBW]);
                $message =  "\"".$_POST[macBW]."\" ".$strings["config-message-removed"]."<br /><br />";
        }

}

if(isset($_POST[language])){
	updateLanguage($_POST[language]);
	$languageFile = "includes/languages/".$_POST[language]."-".$version.".php";
	if(file_exists($languageFile)) require($languageFile);
	else require("includes/languages/english-".$version.".php");
	$languageMessage = "<font color=lime>".$strings["config-language-message"]." $_POST[language].</font><br />";
	$languageMessage2 = "<font color=lime><script type='text/javascript'>setTimeout(\"window.location='index.php?config'\", 1300);</script>".$strings["config-language-message2"]."<br /><br />";
}

if(isset($_GET[updateLanguages])){
        $connection = @file_get_contents("http://cloud.wifipineapple.com/ip.php");

        if(trim($connection) != ""){
                exec("wget -O /tmp/languages.tar.gz \"http://cloud.wifipineapple.com/?downloads&languages=$version\"");
                exec("tar -C /tmp/ -zxvf /tmp/languages.tar.gz");
                exec("cp /tmp/downloads/* ../includes/languages/");
                $languageMessage = "<font color=lime>".$strings["config-language-success"]."</font><br /><br />";
        }else $languageMessage = "<font color=red>".$strings["config-language-error"]."</font><br /><br />";
}

?>

<div class=content>
<center><?php echo $fileMessage; ?></center>
<center><?php echo $message; ?></center>
<div class=contentTitle><?=$strings["config-karmaConfig"]?></div>
<div class=contentContent>
<b><?=$strings["config-karmaConfig-ssid"]?> (<?= exec("hostapd_cli -p /var/run/hostapd-phy0 karma_get_ssid")?>)</b><br />
<form action='#' method= 'post' >
<input type="text" name="newSSID" size='25' value="SSID" onFocus="if(this.value == 'SSID') {this.value = '';}" onBlur="if (this.value == '') {this.value = 'SSID';}" >
<br><?=$strings["config-karmaConfig-ssid-persistent"]?>:<input type="checkbox" name="newSSIDPersistent">
<br><input type='submit' value='<?=$strings["config-karmaConfig-ssid-button"]?>'>
</form><br />

<b><?=$strings["config-karmaConfig-ssid-bw"]?></b><br>
<?php
$BWMode = exec("hostapd_cli -p /var/run/hostapd-phy0 karma_get_black_white");
$changeLink = "<a href='karma/changeBW.php'>".$strings["config-karmaConfig-ssid-bw-change"]."</a>";
?>
<font color='lime' size='2'> <?=$strings["config-karmaConfig-ssid-bw-mode"]?>  <?php echo $BWMode ?> | <font color='red'><?php echo $changeLink ?></font></font><br>
<form action='#' method= 'post' >
<input type="text" name="ssidBW" size='27' value="SSID" onFocus="if(this.value == 'SSID') {this.value = '';}" onBlur="if (this.value == '') {this.value = 'SSID'}">
<br><input type='submit' name='addSSID' value='<?=$strings["config-karmaConfig-ssid-bw-add"]?>'><input type='submit' name='removeSSID' value='<?=$strings["config-karmaConfig-ssid-bw-remove"]?>'>
</form>
</td><td>
<b><?=$strings["config-karmaConfig-mac"]?></b>
<form action='#' method= 'post' >
<input type="text" name="macBW" size='27' value="MAC" onFocus="if(this.value == 'MAC') {this.value = '';}" onBlur="if (this.value == '') {this.value = 'MAC';}">
<br><input type='submit' name='addMAC' value='<?=$strings["config-karmaConfig-ssid-bw-add"]?>'><input type='submit' name='removeMAC' value='<?=$strings["config-karmaConfig-ssid-bw-remove"]?>'>
</form>
</div><br /><br />

<div class=contentTitle id=dnsspoof><?=$strings["config-dnsspoof-title"]?></div>
<div class=contentContent>
<?=$strings["config-dnsspoof-description"]?><br /><br />
<?php
  $fh = fopen("config/spoofhost", "r") or die("Could not open file!");
  $data = fread($fh, filesize("config/spoofhost")) or die("Could not read file!");
  fclose($fh);
 echo "<form action='#' method= 'post' >
<textarea name='newdata' rows='20' style='min-width:100%; background-color:black; color:white; border-style:dashed;'>$data</textarea>
<input type='hidden' name='filename' value='config/spoofhost'>
<br><center><input type='submit' value='".$strings["config-dnsspoof-button"]."'>
</form>";
?>

</td><td valign="top" align="left">
</div><br /><br />

<div class=contentTitle><?=$strings["config-landing-title"]?></div>
<div class=contentContent>
<?=$strings["config-landing-description"]?><br /><br />
<?php
  $fh = fopen("/www/index.php", "r") or die("Could not open file!");
  $data = fread($fh, filesize("/www/index.php")) or die("Could not read file!");
  fclose($fh);
 echo "<form action='#' method= 'post' >
<textarea name='newdata' rows='20' style='min-width:100%; background-color:black; color:white; border-style:dashed;'>$data</textarea>
<input type='hidden' name='filename' value='/www/index.php'>
<br><center><input type='submit' value='".$strings["config-landing-button"]."'>
</form>";
?>

</td><td valign="top" align="left">
</div><br /><br />

<div class=contentTitle><?=$strings["config-css-title"]?></div>
<div class=contentContent>
<br />
<?php
  $fh = fopen("includes/styles.css", "r") or die("Could not open file!");
  $data = fread($fh, filesize("includes/styles.css")) or die("Could not read file!");
  fclose($fh);
echo "<form action='#' method= 'post' >
<textarea name='newdata' rows='20' style='min-width:100%; background-color:black; color:white; border-style:dashed;'>$data</textarea>
<input type='hidden' name='filename' value='includes/styles.css'>
<br><center><input type='submit' value='".$strings["config-css-button"]."'>
</form>";
?>

</td><td valign="top" align="left">
</div>
