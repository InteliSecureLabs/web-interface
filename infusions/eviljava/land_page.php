<?php
$lp = $_POST['land_page'];
echo exec("echo $lp |tee /www/java/redirect");
?>
<meta http-equiv="REFRESH" content="0;url=index.php">
