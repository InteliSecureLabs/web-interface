<html>
    <head>
    <title>Pineapple Control Center</title>
    <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <link rel="stylesheet" type="text/css" href="includes/styles.css" /> 
    <link rel="icon" href="favicon.ico" type="image/x-icon"> 
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> 
    </head>
<body>
<?php
include_once("includes/navbar.php");
?>
<br /><br />
<?php
if(isset($_GET["config"])){
	include("pages/config.php");
}
else if(isset($_GET["about"])){
        include("pages/about.php");
}
else if(isset($_GET["usb"])){
        include("pages/usb.php");
}
else if(isset($_GET["advanced"])){
        include("pages/advanced.php");
}
else if(isset($_GET["scripts"])){
        include("pages/scripts.php");
}
else if(isset($_GET["logs"])){
        include("pages/logs.php");
}
else if(isset($_GET["resources"])){
        include("pages/resources.php");
}
else if(isset($_GET["jobs"])){
	include("pages/jobs.php");
}
else if(isset($_GET["modules"])){
	include("pages/modules.php");
}
else if(isset($_GET["ssh"])){
        include("pages/ssh.php");
}
else if(isset($_GET["3g"])){
        include("pages/3g.php");
}
else if(isset($_GET["upgrade"])){
        include("pages/upgrade.php");
}
else {
	include("pages/status.php");
}

?>

