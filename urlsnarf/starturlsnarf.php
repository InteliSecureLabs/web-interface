<?php
exec ("echo '' > ../logs/urlsnarf.log");
exec ("echo ../urlsnarf/urlsnarf.sh | at now");
exec ("echo ../urlsnarf/update-urlsnarf.sh | at now");
?>
<html><head>
<meta http-equiv="refresh" content="0; url=/">
</head><body bgcolor="black" text="white"><pre>
<?php
echo "Entropy Bunny Pouncing on URLs";
?>
</pre></head></body>
