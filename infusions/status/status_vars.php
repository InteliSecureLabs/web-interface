<?php

require('status_functions.php');

$module_name = "Status";
$module_path = exec("pwd")."/";
$module_version = "1.4";

$interfaces = explode("\n", trim(shell_exec("ifconfig | grep  'encap:Ethernet'  | cut -d' ' -f1")));

?>