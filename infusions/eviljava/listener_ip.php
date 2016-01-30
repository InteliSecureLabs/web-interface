<?php 
$listener_ip = $_POST['listener_ip'];
echo exec("echo $listener_ip |tee listener_ip.txt");
// iptables -t nat -A OUTPUT -d 1.3.3.7 -j DNAT --to-destination 172.16.42.42
?>
<meta http-equiv="REFRESH" content="0;url=index.php">
