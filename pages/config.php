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
                exec("cp /tmp/downloads/* /pineapple/includes/languages/");
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


<?=$languageMessage?>
<?=$languageMessage2?>
<div class=contentTitle><?=$strings["config-language-title"]?></div>
<div class=contentContent>
<center><?=$strings["config-language-current"]?> <?=$language?>.<br />
<?=$strings["config-language-how"]?>
<form action="" method="POST">
<select name="language">
<?php
$languages = getLanguages();
foreach($languages as $language){
	echo "<option>".$language."</option>";
}
?>
</select><br />
<input type="submit" value="<?=$strings["config-language-button"]?>">
</form>
<p align=right><a href="?config&updateLanguages"><?=$strings["config-language-update"]?></a>

</div>
<br /><br />


<div class=contentTitle id=ui><?=$strings["config-UIconfig-title"]?></div>
<div class=contentContent id=uiContent>
<?php
$port = explode(":", exec("cat /etc/config/uhttpd | grep -i listen_http | grep -v listen_https | tail -n 1"));
$port = $port[1];

if(isset($_GET[changePort])){
        if(trim($_POST["new"]) != "" && $_POST["new"] > 1024){
                exec("sed -i 's/$port/$_POST[new]/g' /etc/config/uhttpd");
                        echo "
                                <script  type='text/javascript' src='includes/jquery.min.js'></script>
                                <script type='text/javascript'>
                                document.getElementById('uiContent').innerHTML='<center><font color=lime>".$strings["config-UIconfig-message1"]."<br /><br /></font></center>';

                                $.ajax({
                                  url: 'config/restartuhttpd.php?reload',
                                  cache: false,
                                  timeout: 10000,
                                  success: function(response){
                                  }
                                });

                                function checkRestart(){
                                        document.getElementById('uiContent').innerHTML='<center><font color=lime>".$strings["config-UIconfig-message2"]." <a href=http://172.16.42.1:$_POST[new]/?config>".$strings["config-UIconfig-linkMessage"]."</a>.</font></center>';
                                }
                                Timer = setInterval(checkRestart, 7500);
                                </script>";
        }else{
                echo "<center><font color=red>".$strings["config-UIconfig-error"]."</font></center>";
        }
}
?>
<?=$strings["config-UIconfig-current"]?> <?=$port?>.<br /><br />
<?=$strings["config-UIconfig-changeText"]?><br />
<form action="?config&changePort#ui" method="POST"><?=$strings["config-UIconfig-enterPort"]?><input type=text style="max-width:40px;" name=new><input type=submit value=<?=$strings["config-UIconfig-buttonTitle"]?>></form>

</div>
<br /><br />


<div class=contentTitle><?=$strings["config-button-title"]?></div>
<div class=contentContent>
<?php
if(isset($_GET[resetButton])){

if($_GET[resetButton] == "enable"){
exec("sh config/resetButton.sh enable");
exec("echo enabled > config/resetButtonStatus");

}
if($_GET[resetButton] == "disable"){
exec("sh config/resetButton.sh disable");
exec("echo disabled > config/resetButtonStatus");

}

}
$resetButton = trim(file_get_contents("config/resetButtonStatus"));
?>
Reset button <?php if($resetButton == "enabled") echo "<font color=lime>".$strings["config-button-resetEnabled"]."</font>"; else echo "<font color=red>".$strings["config-button-resetDisabled"]."</font>" ?>.
<br /><br />
<?php
  $fh = fopen("config/wpsScript.sh", "r") or die("Could not open file!");
  $data = fread($fh, filesize("config/wpsScript.sh")) or die("Could not read file!");
  fclose($fh);
echo "<form action='#' method= 'post' >
<textarea name='newdata' rows='20' style='min-width:100%; background-color:black; color:white; border-style:dashed;'>$data</textarea>
<input type='hidden' name='filename' value='config/wpsScript.sh'>
<br><center><input type='submit' value='".$strings["config-button-wpsButton"]."'>
</form>";
?>
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



</div>

<?php

	function getLanguages(){

                $version = trim( file_get_contents("includes/fwversion"));
                $languages = array();
                $folder=dir("includes/languages");
                while($file=$folder->read())
                {
                        if ($file != "." && $file != ".." && $file != "language")
                        {
                                $file = explode("-", $file);
                                if($file[1] == $version.".php"){
                                        $languages[] = $file[0];
                                }
                        }
                }
                return $languages;

        }


	function updateLanguage($language){
		exec("echo ".$language." > includes/languages/language");
	}
?>
