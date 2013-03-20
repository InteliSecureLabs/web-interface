<?php

if(isset($_GET[stealth])){
	if($_GET[stealth] == "on"){
		exec("echo 1 > /proc/sys/net/ipv4/icmp_echo_ignore_all");
	} else {
		exec("echo 0 > /proc/sys/net/ipv4/icmp_echo_ignore_all");
	}
}


?>

<script type="text/javascript" src="includes/ajax.js"> </script>
<script type="text/javascript" src="includes/logtail.js"> </script>

<script type="text/javascript">
	getLog('start');
</script>
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
<br />
<br />
<br />
<div class=content>
<center><?php echo $message; ?></center>

<?php
if(isset($_GET[report])){
?>

<div class=contentTitle><?=$strings["status-report"]?> (<a href="index.php"><?=$strings["status-dismiss"]?></a>)</div>
<div class=contentContent>
<pre>
<?php
        echo "<small><font color='lime'>".$strings["status-reportWarning"]."</font></small><br /><br />";
        $cmd="/pineapple/karma/karmaclients.sh";
        exec("$cmd 2>&1", $output);
        foreach($output as $outputline) {
                 echo ("$outputline\n");
         }
?>
</pre>
</div><br /><br />
<?php
}
?>


<div class=contentTitle><?= $strings["status-karma"] ?> (<a href="index.php?report"><?=$strings["status-genReport"]?></a>)</div>
<div class=contentContent>
<pre>
<div id='log'></div>
</pre>
</div>




</div>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?=$strings["status-services"]?></div>
<div class=sidePanelContent>

<?php


$iswlanup = exec("ifconfig wlan0 | grep UP | awk '{print $1}'");
if ($iswlanup == "UP") {
echo "&nbsp;Wireless  <font color=\"lime\"><b>".$strings["status-enabled"]."</b></font>.&nbsp; | <a href=\"wifi/wlan.php?stop\"><b>".$strings["status-stop"]."</b></a><br />";
} else { echo "&nbsp;Wireless  <font color=\"red\"><b>".$strings["status-disabled"]."</b></font>. | <a href=\"wifi/wlan.php?start\"><b>".$strings["status-start"]."</b></a><br />"; }

if ( exec("hostapd_cli -p /var/run/hostapd-phy0 karma_get_state | tail -1") == "ENABLED" ){
$iskarmaup = true;
}
if ($iskarmaup != "") {
echo "MK4 Karma  <font color=\"lime\"><b>".$strings["status-enabled"]."</b></font>.&nbsp; | <a href=\"karma/stopkarma.php\"><b>".$strings["status-stop"]."</b></a><br />";
} else { echo "MK4 Karma  <font color=\"red\"><b>".$strings["status-disabled"]."</b></font>. | <a href=\"karma/startkarma.php\"><b>".$strings["status-start"]."</b></a> <br />"; }

$autoKarma = ( exec("if grep -q 'hostapd_cli -p /var/run/hostapd-phy0 karma_enable' /etc/rc.local; then echo 'true'; fi") );
if ($autoKarma != ""){
echo "Autostart  <font color=\"lime\"><b>".$strings["status-enabled"]."</b></font>.&nbsp; | <a href=\"karma/autoKarmaStop.php\"><b>".$strings["status-stop"]."</b></a><br />";
} else { echo "Autostart  <font color=\"red\"><b>".$strings["status-disabled"]."</b></font>. | <a href=\"karma/autoKarmaStart.php\"><b>".$strings["status-start"]."</b></a><br />"; }

$cronjobs = ( exec("ps -all | grep [c]ron"));
if ($cronjobs != ""){
echo "Cron Jobs <font color=\"lime\"><b>".$strings["status-enabled"]."</b></font>.&nbsp; | <a href=\"index.php?jobs&stop&goback\"><b>".$strings["status-stop"]."</b></a><br />";
} else { echo "Cron Jobs <font color=\"red\"><b>".$strings["status-disabled"]."</b></font>. | <a href=\"index.php?jobs&start&goback\"><b>".$strings["status-start"]."</b></a> | <a href=\"index.php?jobs\"><b>Edit</b></a><br />"; }

$isurlsnarfup = exec("ps auxww | grep urlsnarf.sh | grep -v -e grep");
if ($isurlsnarfup != "") {
echo "URL Snarf  <font color=\"lime\"><b>".$strings["status-enabled"]."</b></font>.&nbsp; | <a href=\"urlsnarf/stopurlsnarf.php\"><b>".$strings["status-stop"]."</b></a><br />";
} else { echo "URL Snarf  <font color=\"red\"><b>".$strings["status-disabled"]."</b></font>. | <a href=\"urlsnarf/starturlsnarf.php\"><b>".$strings["status-start"]."</b></a><br />"; }

$isdnsspoofup = exec("ps auxww | grep dnsspoof.sh | grep -v -e grep");
if ($isdnsspoofup != "") {
echo "DNS Spoof  <font color=\"lime\"><b>".$strings["status-enabled"]."</b></font>.&nbsp; | <a href=\"dnsspoof/stopdnsspoof.php\"><b>".$strings["status-stop"]."</b></a><br />";
} else { echo "DNS Spoof  <font color=\"red\"><b>".$strings["status-disabled"]."</b></font>. | <a href=\"dnsspoof/startdnsspoof.php\"><b>".$strings["status-start"]."</b></a> | <a href=\"index.php?config#dnsspoof\"><b>".$strings["status-edit"]."</b></a><br/>"; }

if (exec("grep 3g.sh /etc/rc.local") != ""){
echo "3G bootup  <font color=\"lime\"><b>".$strings["status-enabled"]."</b></font>.&nbsp; | <a href=\"index.php?3g&disable&disablekeepalive&goback\"><b>".$strings["status-disable"]."</b></a><br />";
} else { echo "3G bootup <font color=\"red\"><b>".$strings["status-disabled"]."</b></font>. | <a href=\"index.php?3g&enable&goback\"><b>".$strings["status-enable"]."</b></a><br />"; }

if (exec("grep 3g-keepalive.sh /etc/crontabs/root") == "") {
echo "3G redial <font color='red'><b>".$strings["status-disabled"]."</b></font>. | <a href='index.php?3g&enablekeepalive&enable&goback'><b>".$strings["status-enable"]."</b></a><br />";
} else { echo "3G redial <font color='lime'><b>".$strings["status-enabled"]."</b></font>.&nbsp; | <a href='index.php?3g&disablekeepalive&goback'><b>".$strings["status-disable"]."</b></a><br />"; }

if (exec("ps aux | grep [s]sh | grep -v -e ssh.php | grep -v grep") == "") {
echo "&nbsp; &nbsp; &nbsp; SSH <font color=\"red\"><b>".$strings["status-offline"]."</b></font>. &nbsp;| <a href=\"index.php?ssh&connect&goback\"><b>".$strings["status-connect"]."</b></a><br />";
} else {
echo "&nbsp; &nbsp; &nbsp; SSH <font color=\"lime\"><b>".$strings["status-online"]."</b></font>. &nbsp; | <a href=\"index.php?ssh&disconnect&goback\"><b>".$strings["status-disconnect"]."</b></a><br />";
}

if(exec("cat /proc/sys/net/ipv4/icmp_echo_ignore_all") == "0"){
echo "&nbsp; Stealth <font color=red><b>".$strings["status-disabled"]."</b></font>. | <a href=\"index.php?stealth=on\"><b>".$strings["status-enable"]."</b></a><br />";
} else {
echo "&nbsp; Stealth <font color=lime><b>".$strings["status-enabled"]."</b></font>. &nbsp;| <a href=\"index.php?stealth=off\"><b>".$strings["status-disable"]."</b></a><br />";
}

?>


</div><br /><br />
</div>
<div class=sidePanelRight>
<div class=sidePanelTitle><?=$strings["status-interfaces"]?></div>
<div class=sidePanelContent></b>
<?php
echo "&nbsp;".$strings["status-poe"]." " . exec("ifconfig br-lan | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'") . "<br />";
echo "&nbsp;&nbsp; ".$strings["status-3g"]." " . exec("ifconfig 3g-wan2 | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'") . "<br />";
echo "&nbsp;".$strings["status-wan"]." " . exec("ifconfig eth1 | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'") . "<br />";
echo $strings["status-public"]." ";
if (isset($_GET[revealpublic])) {
        $ip = @file_get_contents("http://cloud.wifipineapple.com/ip.php");
        if($ip != "") echo $ip."<br />";
        else echo "<font color=red>".$strings["status-connectionError"]."</font><br />";
} else {
        echo "<a href=\"index.php?revealpublic\">".$strings["status-reveal"]."</a><br />";
}

?>

</div>
</div>
