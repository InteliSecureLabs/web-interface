<?php
$os = $_GET['os'];
$payload = $_GET['payload'];
exec("sed -i 's/$os|.*/$os|$payload/g' /www/java/payloads");
?>
<meta http-equiv="REFRESH" content="0;url=index.php">
