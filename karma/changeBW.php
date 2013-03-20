<?php

if(exec ("hostapd_cli -p /var/run/hostapd-phy0 karma_get_black_white") == "BLACK"){
exec ("echo hostapd_cli -p /var/run/hostapd-phy0 karma_white | at now");
}else{ exec ("echo hostapd_cli -p /var/run/hostapd-phy0 karma_black | at now"); }
?>
<html><head>
<meta http-equiv="refresh" content="1; url=../index.php?config">
</head><body bgcolor="black" text="white"><pre>
<?php
echo "Entropy Bunny Bouncing";
?>
</pre></head></body>
