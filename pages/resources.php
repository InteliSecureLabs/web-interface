<?php

if(isset($_GET[drop_caches])) {
        exec("sync");
        exec("echo 3 > /proc/sys/vm/drop_caches");
        $message = $strings["resources-dropCachesExecuted"];
}

if(isset($_GET[kill])){
        echo "<font color=lime>Killed process $_GET[kill]</font><br /><br />";
        exec("kill ".$_GET[kill]);
}

?>

<div class=content>
<center><?php echo exec("uptime"); ?><br /><br /></center>
<div class=contentTitle><?=$strings["resources-memory"]?></div>
<div class=contentContent>
<?php echo $message; ?>
<pre>
<?php

$cmd = "free";
exec ($cmd, $output);
foreach($output as $outputline) {
echo ("$outputline\n");}
$output = "";
?>
</pre>
<a href='index.php?resources&drop_caches'>echo 3 > /proc/sys/vm/drop_caches</a> <font color='orange'><small><?=$strings["resources-memory-experimental"]?></small></font>

</div><br /><br />


<div class=contentTitle><?=$strings["resources-diskUsage"]?></div>
<div class=contentContent>
<pre>
<?php

$cmd = "df -h";
exec ($cmd, $output);
foreach($output as $outputline) {
echo ("$outputline\n");}
$output = "";
?>
</pre>

</div><br /><br />

<div class="contentTitle"><?=$strings["resources-lsusb"]?></div>
<div class="contentContent">
<?php
$exec = exec("lsusb", $return);
foreach ($return as $line) {
echo("$line <br />");
}
?>

</div><br /><br />


<div class=contentTitle><?=$strings["resources-processes"]?></div>
<div class=contentContent>
<pre>
<?php

$cmd = "ps";
exec ($cmd, $output);
foreach($output as $line){
        $lineArray = explode(" ", trim($line));
        $pid = $lineArray[0];
        if($pid != "PID") echo "<a href='?resources&kill=$pid'>Kill</a>".$line."\n";
        else echo "    ".$line."\n";
}
$output = "";
?>
</pre>

</div><br /><br />


</div>
