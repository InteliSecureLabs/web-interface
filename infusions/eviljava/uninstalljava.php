<?php
exec ("rm -r /www/java/");
if (is_dir('/usb/infusions/eviljava/java')) {
	exec('rm -r /usb/infusions/eviljava/java');
}
?>
<html><head>
<meta http-equiv="refresh" content="0; url=index.php">
</head><body bgcolor="black" text="white"><pre>
<?php
echo "Entropy Bunny uninstalling java ;(";
?>
</pre></head></body>
