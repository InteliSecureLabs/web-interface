<?php
exec ("echo '' > /pineapple/logs/urlsnarf.log");
exec ("kill `ps | grep \"urlsnarf.sh\" | grep -v -e grep | awk '{print $1}'`");
exec ("killall update-urlsnarf.sh");
exec ("kill `ps | grep \"urlsnarf -i br-lan\" | grep -v -e grep | awk '{print $1}'`");
?>
<html><head>
<meta http-equiv="refresh" content="0; url=/">
</head><body bgcolor="black" text="white"><pre>
<?php
echo "Entropy Bunny stops snarfing urls, having a mojito instead.";
?>
</pre></head></body>
