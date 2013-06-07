<?php

require("status_vars.php");

if($cmd != "")
{
	$output = shell_exec($cmd);
	
	if($output != "")
		echo trim($output);	
	else
		echo "-";
}

?>