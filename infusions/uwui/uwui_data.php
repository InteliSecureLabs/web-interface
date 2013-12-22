<?php

require("uwui_vars.php");

if($installed)
{
	if(file_exists($log_file)) echo file_get_contents($log_file);
	else echo "Waiting for hooked clients...";
}
else
{	
	echo "BeEF Helper is not installed...";	
}

?>