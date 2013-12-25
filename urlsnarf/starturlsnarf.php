<?php
exec ("echo '' > /opt/pwnpad/web-interface/logs/urlsnarf.log");
exec ("echo /opt/pwnpad/web-interface/urlsnarf/urlsnarf.sh | at now");
exec ("echo /opt/pwnpad/web-interface/urlsnarf/update-urlsnarf.sh | at now");
?>
<html><head>
<meta http-equiv="refresh" content="0; url=/">
</head><body bgcolor="black" text="white"><pre>
<?php
echo "Entropy Bunny Pouncing on URLs";
?>
</pre></head></body>
