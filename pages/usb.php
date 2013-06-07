<?php
$filename =$_POST['filename'];
$newdata = $_POST['newdata'];

if ($newdata != "") { $newdata = ereg_replace(13,  "", $newdata);
 $fw = fopen($filename, 'w') or die('Could not open file!');
 $fb = fwrite($fw,stripslashes($newdata)) or die('Could not write to file');
 fclose($fw);
 $fileMessage = $strings["usb-fileUpdate"]." " . $filename . "<br /><br />";
} ?>




<div class=content>
<center><?php echo $fileMessage; ?></center>
<div class="contentTitle"><?=$strings["usb-lsusb"]?></div>
<div class="contentContent">
<?php
$exec = exec("lsusb", $return);
foreach ($return as $line) {
echo("$line <br />");
}
?>

</div><br /><br />

<div class="contentTitle"><?=$strings["usb-fstab"]?></div>
<div class="contentContent">
<?php
  $fh = fopen("/etc/config/fstab", "r") or die("Could not open file!");
  $data = fread($fh, filesize("/etc/config/fstab")) or die("Could not read file!");
  fclose($fh);
 echo "<br />
<form action='#' method= 'post' >
<textarea name='newdata' rows='20' style='min-width:100%; background-color:black; color:white; border-style:dashed;'>$data</textarea>
<input type='hidden' name='filename' value='/etc/config/fstab'>
<br><center><input type='submit' value='".$strings["usb-fstab-button"]."'>
</form>"; ?>
</div>

</div>
