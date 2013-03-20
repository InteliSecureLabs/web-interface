<div class=content style="min-width:1000px">

<div class=contentTitle id=dnsspoof><?=$strings["logs-dnsspoof-title"]?> (<a href="#dnsspoof" onClick="javascript:location.reload(true)"><?=$strings["logs-refresh"]?></a>)</div>
<div class=contentContent>
<?php
$cmd = "cat logs/dnsspoof.log";
exec ($cmd, $output);
foreach($output as $outputline) {
echo ("$outputline<br />");}
$output = "";
?>
</div><br /><br />

<div class=contentTitle id=phish><?=$strings["logs-phishingLog"]?> (<a href="#phish" onClick="javascript:location.reload(true)"><?=$strings["logs-refresh"]?></a>)</div>
<div class=contentContent>
<?php
$cmd = "cat logs/phish.log";
exec ($cmd, $output);
foreach($output as $outputline) {
echo ("$outputline<br />");}
$output = "";
?>
</div><br /><br />

<!--
<div class=contentTitle id=urlsnarf><?=$strings["logs-urlsnarf-title"]?> (<a href="#urlsnarf" onClick="javascript:location.reload(true)"><?=$strings["logs-refresh"]?></a>)</div>
<div class=contentContent>
<?php
$cmd = "cat logs/urlsnarf-clean.log | sort -nr";
exec ($cmd, $output);
foreach($output as $outputline) {
echo ("$outputline<br />");}
$output = "";
?>
</div><br /><br />
-->

<div class=contentTitle id=syslog><?=$strings["logs-syslog-title"]?> (<a href="#syslog" onClick="javascript:location.reload(true)"><?=$strings["logs-refresh"]?></a>)</div>
<div class=contentContent>
<?php
$cmd = "logread | sort -nr | cut -c 8-";
exec ($cmd, $output);
foreach($output as $outputline) {
echo ("$outputline<br />");}
$output = "";
?>
</div><br /><br />



</div>
