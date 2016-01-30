<?php
	$airodump_file = $_GET["file"];
?>

<html>
	<head>
		<title>UWUI</title>
		<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
		<link rel="stylesheet" type="text/css" href="../css/uwui.css" />
		<link rel="icon" href="../icons/uwui.jpg" type="image/x-icon">
		<link rel="shortcut icon" href="../icons/uwui.jpg" type="image/x-icon">
		<script type="text/javascript" charset="utf-8" src="../js/jquery.js"></script>
		<script type="text/javascript" charset="utf-8" src="../js/uwui_functions.js"></script>
	</head>
	<body>
		<?php echo "File: $airodump_file<BR><BR>"; ?>
		<div id=autodecrypter style='text-align:left;'></div>
		<?php
			echo '
			<script>
				$("#autodecrypter").load("autodecrypter_core.php?file='.$airodump_file.'");
				auto_refresh = setInterval(function (){$("#autodecrypter").load("autodecrypter_core.php?file='.$airodump_file.'");}, 2000);
			</script>
			';
		?>
	</body>
</html>
