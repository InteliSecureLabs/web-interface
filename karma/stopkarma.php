<?php
exec ("echo '' > /tmp/karma.log");
exec ("hostapd_cli -p /var/run/hostapd-phy0 karma_disable");
?>
<html><head>
<meta http-equiv="refresh" content="2; url=../wait.php">
</head><body bgcolor="black" text="white"><pre>
<?php
echo "Entropy Bunny Deactivated";
?>
</pre></head></body>
