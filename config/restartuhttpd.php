<?php
	if(isset($_GET[reload])){
		exec("/etc/init.d/uhttpd reload");
	}else{
		echo("done");
	}

?>

