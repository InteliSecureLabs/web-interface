<?php

if(isset($_GET[goback])){
echo '<script type="text/javascript">
        window.location = "index.php"
        </script>';
}


$cronjobs = ( exec("ps -all | grep [c]ron"));
if(isset($_GET[start])){
exec("/etc/init.d/cron enable");
exec("/etc/init.d/cron start");
$cronjobs = "true";
}
if(isset($_GET[stop])){
exec("/etc/init.d/cron stop");
exec("/etc/init.d/cron disable");
$cronjobs = "";
}


$filename =$_POST['filename'];
$newdata = $_POST['newdata'];

if ($newdata != "") { $newdata = ereg_replace(13,  "", $newdata);
 exec("/etc/init.d/cron restart");
 $fw = fopen($filename, 'w') or die('Could not open file!');
 $fb = fwrite($fw,stripslashes($newdata)) or die('Could not write to file');
 fclose($fw);
 $message = $strings["jobs-fileUpdated"]." " . $filename . "<br /><br />";
}


?>

<div class=sidePanelLeft style="min-width:370px">
<div class=sidePanelTitle><?=$strings["jobs-title"]?></div>
<div class=sidePanelContent>
<?php
if ($cronjobs != ""){
echo $strings["jobs-cronjobs"]." <font color=\"lime\"><b>".$strings["jobs-cronjobs-enabled"]."</b></font>. | <a href=\"index.php?jobs&stop\"><b>".$strings["jobs-cronjobs-disable"]."</b></a><br />";
} else { echo "Cron Jobs <font color=\"red\"><b>".$strings["jobs-cronjobs-disabled"]."</b></font>. | <a href=\"index.php?jobs&start\"><b>".$strings["jobs-cronjobs-enable"]."</b></a><br />"; }
?>
</div>
</div>

<br />
<br />
<br />
<br />
<br />
<div class=content>
<?php echo "<font color=lime>".$message."</font><br />"; ?>
<br />

<div class=contentTitle><?=$strings["jobs-cronjobs-title"]?></div>
<div class=contentContent>
<?php
$filename = "/etc/crontabs/root";
  $fh = fopen($filename, "r") or die("Could not open file!");
  $data = fread($fh, filesize($filename)) or die("Could not read file!");
  fclose($fh);
 echo "<form action='$_SERVER[php_self]' method= 'post' >
<textarea name='newdata' rows='20' style='min-width:100%; max-width:100%; background-color:black; color:white; border-style:dashed;'>$data</textarea>
<input type='hidden' name='filename' value='/etc/crontabs/root'>
<br><center><input type='submit' value='".$strings["jobs-cronjobs-button"]."'>
</form>";
?>
</div>
<br /><br />

<div class=contentTitle><?=$strings["jobs-help-title"]?></div>
<div class=contentContent>
<pre>
<?=$strings["jobs-help"]?>
</pre>
</div>

</div>
