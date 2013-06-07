<?php

if(isset($_GET[goback])){
echo '<script type="text/javascript">
	window.location = "index.php"
	</script>';
}

if (isset($_GET[force])) {
$message = "<pre>".$strings["3g-force-message"]." <a href=\"index.php?logs\"><b><font color=white>".$strings["3g-force-logs"]."</font></b></a> ".$strings["3g-force-details"]."</pre>";
exec("echo /pineapple/3g/3g.sh | at now");}

if (isset($_GET[enablekeepalive])) {
        if (exec("grep 3g-keepalive.sh /etc/crontabs/root") == "") {
                exec("echo '*/5 * * * * /pineapple/3g/3g-keepalive.sh' >> /etc/crontabs/root");
		exec("/etc/init.d/crond restart");
                $message = "<pre>".$strings["3g-keepAlive-message"]." <a href='jobs.php'><b>".$strings["3g-keepAlive-jobs"]."</b></a>.</pre>";
        } else {
        }
}

if (isset($_GET[disablekeepalive])) {
        exec("sed -i '/3g-keepalive.sh/d' /etc/crontabs/root");
        exec("/etc/init.d/crond restart");
}



$auto3g = (exec("grep 3g.sh /etc/rc.local"));

if (isset($_GET[enable])) {

        if (exec("grep 3g.sh /etc/rc.local") == "") {
                exec("sed -i '/exit 0/d' /etc/rc.local");
                exec("echo /pineapple/3g/3g.sh >> /etc/rc.local");
                exec("echo exit 0 >> /etc/rc.local");
                $auto3g = "true";
        } else {
        }
}

if (isset($_GET[disable])) {
        exec("sed -i '/3g.sh/d' /etc/rc.local");
        $auto3g = "";
}

$filename =$_POST['filename'];
$newdata = $_POST['newdata'];

if ($newdata != "") { $newdata = ereg_replace(13,  "", $newdata);
 $fw = fopen($filename, 'w') or die('Could not open file!');
 $fb = fwrite($fw,stripslashes($newdata)) or die('Could not write to file');
 fclose($fw);
 $message = $strings["3g-fileUpdate"]." " . $filename . "<br /><br />";
}

?>


<div class=sidePanelLeft style="min-width:370px; max-width:380px">
<div class=sidePanelTitle>3G Options</div>
<div class=sidePanelContent>

<?php
if ($auto3g != ""){
echo $strings["3g-boot"]." <font color=\"lime\"><b>".$strings["3g-enabled"]."</b></font>.&nbsp; | <a href=\"index.php?3g&disable&disablekeepalive\"><b>".$strings["3g-disable"]."</b></a><br />";
} else { echo $strings["3g-boot"]." <font color=\"red\"><b>".$strings["3g-disabled"]."</b></font>. | <a href=\"index.php?3g&enable\"><b>".$strings["3g-enable"]."</b></a><br />"; }

if (exec("grep 3g-keepalive.sh /etc/crontabs/root") == "") {
echo $strings["3g-keepAlive"]." <font color='red'><b>".$strings["3g-disabled"]."</b></font>. | <a href='index.php?3g&enablekeepalive&enable'><b>".$strings["3g-enable"]."</b></a><br />";
} else { echo $strings["3g-keepAlive"]." <font color='lime'><b>".$strings["3g-enabled"]."</b></font>.&nbsp; | <a href='index.php?3g&disablekeepalive'><b>".$strings["3g-disable"]."</b></a><br />"; }

echo "<br /><a href=\"index.php?3g&force\"><b>".$strings["3g-force"]."</b></a> ".$strings["3g-force-warning"]." <font color='orange'><small>".$strings["3g-force-experimental"]."</small></font><br /><br />";
?>

</div>
</div>
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />



<div class=content style="min-width:1130px">
<font color=lime><?php echo $message; ?></font>
<div class=contentTitle><?=$strings["3g-usbConnections"]?></div>
<div class=contentContent>
<?php
exec("lsusb", $return);
foreach($output as $outputline) {
echo ("$outputline\n");}
$output = "";
?>

</div><br /><br />

<div class=contentTitle><?=$strings["3g-config-title"]?></div>
<div class=contentContent>
<?php
  $fh = fopen("/pineapple/3g/3g.sh", "r") or die("Could not open file!");
  $data = fread($fh, filesize("/pineapple/3g/3g.sh")) or die("Could not read file!");
  fclose($fh);
 echo "<form action='#' method= 'post' >
<textarea name='newdata' cols='140' rows='20' style='max-width: 100%; background-color:black; color:white; border-style:dashed;'>$data</textarea>
<input type='hidden' name='filename' value='/pineapple/3g/3g.sh'>
<br><center><input type='submit' value='".$strings["3g-config-button"]."'>
</form>";
?>

</div><br /><br />

<div class=contentTitle><?=$strings["3g-interfaces"]?></div>
<div class=contentContent>
<pre>
<?
exec ("ifconfig", $output);
foreach($output as $outputline) {
echo ("$outputline\n");}
$output = "";
?>
</pre>
</div><br /><br />

<div class=contentTitle><?=$strings["3g-help-title"]?></div>
<div class=contentContent><pre>
<?=$strings["3g-help-content"]?>
</pre></div><br /><br />

</div>
