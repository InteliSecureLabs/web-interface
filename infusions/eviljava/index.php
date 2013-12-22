<html>
<head>
<title>Pineapple Control Center</title>
<!--<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">-->

</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green">
<?php require('/pineapple/includes/navbar.php'); ?>

<pre>

<table border="0" width="100%"><tr><td valign="top" align="left" width="50%">
<?php
	echo "<font style='color:lime'>[*]</font> DNS Spoof must be enabled for this attack!</br>";

	$isjavaup = exec("cat /www/index.php |grep /java/run.php");
	if ($isjavaup != "") 
	{
		echo "Evil Java redirection is currently <font color=\"lime\"><b>enabled</b></font>. | <a href=\"stopjava.php\"><b>Stop</b></a><br />";
	} 
	else 
	{ 
		echo "Evil Java redirection is currently <font color=\"red\"><b>disabled</b></font>. | <a href=\"startjava.php\"><b>Start</b></a><br />"; 
	}

	// display if java is currently installed or not.
	$isjavainstalled = is_dir('/www/java');	
	if ($isjavainstalled) 
	{
		echo "Evil Java is currently <font color=\"lime\"><b>installed</b></font>. | <a href=\"uninstalljava.php\"><b>Uninstall</b></a><br />";
	} 
	else 
	{ 
		echo "Evil Java is currently <font color=\"red\"><b>not installed</b></font>. | <a href=\"installjava.php\"><b>Install</b></a> [ <a href='installjavausb.php'><b>USB</b></a> ]<br />"; 
	}

	//echo "<br />";
	if (!(is_file('/pineapple/infusions/get/get.database')))
	{
		//echo "<font color=\"red\">[*]</font> Get module has to be installed in order to sync with it!";
	}
	elseif (exec('cat sync_get') == 'yes' && is_file('/pineapple/infusions/get/get.database')) 
	{
		echo "Synchronize with get <font color=\"lime\"><b>enabled</b></font>. | <a href=\"sync_get.php?sync_get=unsync\"><b>Unsync</b></a>";
	}
	else
	{
		echo "Synchronize with get <font color=\"red\"><b>disabled</b></font>. | <a href=\"sync_get.php?sync_get=sync\"><b>Sync</b></a>";
	}
?>
<?php 
	if ($_POST['output'] != '') 
	{
		echo "<br /><br /> Output:<div>" . str_replace("\'","'",$_POST['output']) . "</div>";
	}
	require('conf.php');
?>
</body>
</html>
<style>
.navbar {
	font-family:monospace;
	top:10px;
	min-width:100%;
	background:green;
        border-top-left-radius: 10px 10px;
        border-top-right-radius: 10px 10px;
        border-bottom-left-radius: 10px 10px;
        border-bottom-right-radius: 10px 10px;
	text-align:center;
	color:black;
	padding-top: 1px;
	padding-bottom: 1px;
}
</style>
