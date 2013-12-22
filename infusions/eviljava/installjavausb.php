<?php
if (!is_dir('/usb/infusions')) 
{ 
	exec('mkdir /usb/infusions');
}

if (!is_dir('/usb/infusions/eviljava')) 
{ 
	exec('mkdir /usb/infusions/eviljava');
}

if (!is_dir('/usb/infusions/eviljava/user')) 
{ 
	exec('mkdir /usb/infusions/eviljava/user');
}
	
exec ("cp -r java/ /usb/infusions/eviljava/user/");
exec ("ln -s /usb/infusions/eviljava/user/java/ /www/");
?>
<html><head>
<meta http-equiv="refresh" content="0; url=index.php">
</head><body bgcolor="black" text="white"><pre>
<?php
echo "Entropy Bunny Updating<br/><br/>and having cake";
?>
</pre></head></body>
