<html>
	<head>
		<title>UWUI</title>
		<link rel="stylesheet" type="text/css" href="../css/uwui.css" />
		<link rel="icon" href="../icons/uwui.jpg" type="image/x-icon">
		<link rel="shortcut icon" href="../icons/uwui.jpg" type="image/x-icon">
	</head>
	<body style='text-align:left;'>
	<?php
		include "uwui_vars.php";

		if ( isset($_GET["file"]) ) {
			$file=$_GET["file"];
			if (isset($_GET["lines"])) $max_lines=$_GET["lines"]; else $max_lines=500;
			echo "<pre>";
			$lines = exec("${sudo}strings $file 2>&1 | wc -l");
			if ($lines>$max_lines) system("${sudo}strings $file 2>&1 | tail -$max_lines"); else system("${sudo}strings $file 2>&1"); 
			echo "</pre>";
		}
		if ( isset($_GET["text"]) ) {
			$text=$_GET["text"];
			echo "<pre>$text</pre>";
		}

	?>
	</body>
</html>
