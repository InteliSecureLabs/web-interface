<?php
$os = $_GET['os'];
$payload = $_GET['payload'];
exec("rm /www/java/$os/$payload");
?>
<meta http-equiv="REFRESH" content="0;url=index.php">
