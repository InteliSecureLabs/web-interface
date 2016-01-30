<?php
	include("php/uwui_vars.php");
	if (! is_dir("data")) {
		shell_exec("${sudo}mkdir ./data &>/dev/null");
		shell_exec("${sudo}mkdir ./data/keys &>/dev/null");
		shell_exec("${sudo}mkdir ./data/vault &>/dev/null");
		shell_exec("${sudo}mkdir ./data/locations &>/dev/null");
	}
	if ($system!="pineapple") {
		shell_exec("${sudo}chown -R www-data:www-data ./data &>/dev/null");
	}
?>

<html>
	<head>
		<title>UWUI</title>
		<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
		<link rel="stylesheet" type="text/css" href="css/uwui.css" />
		<link rel="icon" href="icons/uwui.jpg" type="image/x-icon">
		<link rel="shortcut icon" href="icons/uwui.jpg" type="image/x-icon">
		<script type="text/javascript" charset="utf-8" src="js/jquery.js"></script>
		<script type="text/javascript" charset="utf-8" src="js/uwui_functions.js"></script>
	</head>
	<body>
		<div id="core">Loading...
		</div>
		<script>
			start();
		</script>
	</body>
</html>
