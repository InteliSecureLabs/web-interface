<?php
$filename =$_POST['filename'];
$newdata = $_POST['newdata'];

if ($newdata != "") { $newdata = ereg_replace(13,  "", $newdata);
 $fw = fopen($filename, 'w') or die('Could not open file!');
 $fb = fwrite($fw,stripslashes($newdata)) or die('Could not write to file');
 fclose($fw);
 $fileMessage = $strings["scripts-fileUpdate"]." " . $filename . "<br /><br />";
}
?>

<div class=content>
<center><?php echo $fileMessage; ?></center>
<div class=contentTitle><?=$strings["scripts-boot-title"]?></div>
<div class=contentContent>
<?php
  $fh = fopen("/etc/rc.local", "r") or die("Could not open file!");
    $data = fread($fh, filesize("/etc/rc.local")) or die("Could not read file!");
      fclose($fh);
       echo "<form action='#' method= 'post' >
       <textarea name='newdata' rows='20' style='min-width:100%; max-width:100%; background-color:black; color:white; border-style:dashed;'>$data</textarea>
       <input type='hidden' name='filename' value='/etc/rc.local'>
       <br><center><input type='submit' value='".$strings["scripts-boot-button"]."'>
       </form>";
?>
</div>

<br /><br />

<div class=contentTitle><?=$strings["scripts-cleanup-title"]?></div>
<div class=contentContent>
<?php
  $fh = fopen("scripts/cleanup.sh", "r") or die("Could not open file!");
  $data = fread($fh, filesize("scripts/cleanup.sh")) or die("Could not read file!");
  fclose($fh);
echo "<form action='#' method= 'post' >
<textarea name='newdata'  rows='14' style='min-width:100%; background-color:black; color:white; border-style:dashed;'>$data</textarea>
<input type='hidden' name='filename' value='scripts/cleanup.sh'><center><input type='submit' value='".$strings["scripts-cleanup-button"]."'>
</form>";
?>
</div><br /><br />

<div class=contentTitle><?=$strings["scripts-ssh-title"]?></div>
<div class=contentContent>
<?php
  $fh = fopen("ssh/ssh-keepalive.sh", "r") or die("Could not open file!");
  $data = fread($fh, filesize("ssh/ssh-keepalive.sh")) or die("Could not read file!");
  fclose($fh);
echo "<form action='#' method= 'post' >
<textarea name='newdata'  rows='14' style='min-width:100%; background-color:black; color:white; border-style:dashed;'>$data</textarea>
<input type='hidden' name='filename' value='ssh/ssh-keepalive.sh'><center><input type='submit' value='".$strings["scripts-ssh-button"]."'>
</form>";
?>
</div><br /><br />

<div class=contentTitle><?=$strings["scripts-3g-title"]?></div>
<div class=contentContent>
<?php
  $fh = fopen("3g/3g-keepalive.sh", "r") or die("Could not open file!");
  $data = fread($fh, filesize("3g/3g-keepalive.sh")) or die("Could not read file!");
  fclose($fh);
echo "<form action='#' method= 'post' >
<textarea name='newdata'  rows='14' style='min-width:100%; background-color:black; color:white; border-style:dashed;'>$data</textarea>
<input type='hidden' name='filename' value='3g/3g-keepalive.sh'><center><input type='submit' value='".$strings["scripts-3g-button"]."'>
</form>";
?>
</div><br /><br />

<div class=contentTitle><?=$strings["scripts-user-title"]?></div>
<div class=contentContent>
<?php
  $fh = fopen("scripts/user.sh", "r") or die("Could not open file!");
  $data = fread($fh, filesize("scripts/user.sh")) or die("Could not read file!");
  fclose($fh);
echo "<form action='#' method= 'post' >
<textarea name='newdata'  rows='14' style='min-width:100%; background-color:black; color:white; border-style:dashed;'>$data</textarea>
<input type='hidden' name='filename' value='scripts/user.sh'><center><input type='submit' value='".$strings["scripts-user-button"]."'>
</form>";
?>
</div><br /><br />



</div>
