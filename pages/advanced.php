<?php

if(isset($_POST['clearcache'])) {
	exec("echo '' > logs/associations.log");
	exec("echo '' > logs/urlsnarf.log");
	exec("echo '' > logs/urlsnarf-clean.log");
	exec("echo '' > logs/ngrep.log");
	exec("echo '' > logs/ngrep-clean.log");
	echo "<font color='lime'><b>".$strings["advanced-cacheCleared"]."</b></font><br />";
}

if(isset($_POST['reboot'])) {
	exec("reboot");
}
?>

<div class=content>
<center>
<form action="" method="POST">
<input type=submit name=clearcache value="<?=$strings["advanced-clearCache"]?>">
<input type=submit name=reboot value="<?=$strings["advanced-reboot"]?>">
</form>
</center>
<br />

<?php
if(isset($_POST[pinghost])) {
?>
<div class=contentTitle><?=$strings["advanced-ping"]?></div>
<div class=contentContent>
<pre>
<?php
$cmd = "ping $_POST[pinghost] -c 4";
exec ($cmd, $output);
foreach($output as $pingline) {
echo ("$pingline\n");}
?>
</div><br /><br />
<?php
$output = "";
}
?>

<?php
if(isset($_POST[traceroutehost])) {
?>
<div class=contentTitle><?=$strings["advanced-traceroute"]?></div>
<div class=contentContent>
<pre>
<?php
$cmd = "traceroute $_POST[traceroutehost]";
exec ($cmd, $output);
foreach($output as $pingline) {
echo ("$pingline\n");}
?>
</div><br /><br />
<?php
$output = "";
}
?>


<div class=contentTitle><?=$strings["advanced-executeCommands"]?></div>
<div class=contentContent>
<form action='' method= 'post' ><textarea rows="10" name="zcommand" style='min-width:100%; font-family:courier; font-weight:bold; background-color:black; color:white; border-style:dashed;'></textarea>
<center><input type='submit' value='<?=$strings["advanced-execute"]?>'> <small><font color="black"><br />Will execute one command per line</font></small></form></center>
<?php
if(isset($_POST['zcommand']) && $_POST['zcommand'] != "") {
$zcommand = $_POST['zcommand'];
$keyarr=explode("\n",$zcommand);
foreach($keyarr as $key=>$value)
{
  $value=trim($value);
  if (!empty($value)) {
      echo "\n<font color='lime'>".$strings["advanced-executing"].": $value</font><br />";
      $zoutput = "";
      $zoutputline = "";
      exec ($value, $zoutput);
      foreach($zoutput as $zoutputline) {
      echo ("$zoutputline<br />");}
  }
}
echo "<br /><br />";
}
?>
</div><br /><br />


<div class=contentTitle><?=$strings["advanced-routing-title"]?></div>
<div class=contentContent>
<pre>

<?php $cmd = "route | grep -v 'Kernel IP routing table'";
exec("$cmd 2>&1", $output);
foreach($output as $outputline) {echo ("$outputline\n");}?>
</pre>
<form action='index.php?advanced' method= 'post' >
<input type="text" name="route" value="route" style="width:100%;"><br />
<input type='submit' value='<?=$strings["advanced-routing-button"]?>'> <small><?=$strings["advanced-routing-example"]?> <i>route add default gw 172.16.42.42 br-lan</i> <br /></small></form>
<br />

<?php
if(isset($_POST['route']) && $_POST['route'] != "") {
exec($_POST['route'], $routeoutput);
echo "<br /><font color='yellow'>".$strings["advanced-routing-execute"]." " . $_POST['route'] . "</font><br /><br /><b>";
foreach($routeoutput as $routeoutputline) { echo ("$routeoutputline\n"); }
echo "</b></font><br />"; }
?>

</div><br /><br />


<div class=contentTitle><?=$strings["advanced-network-title"]?></div>
<div class=contentContent>
<br />
<form method="post" action=""><input type="text" name="pinghost" > <input type="submit" value="<?=$strings["advanced-ping"]?>" name="submit"></form>
<form method="post" action=""><input type="text" name="traceroutehost" > <input type="submit" value="<?=$strings["advanced-traceroute"]?>" name="submit"></form>

</div><br /><br />