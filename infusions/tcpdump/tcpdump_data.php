<?php

require("tcpdump_vars.php");

if (isset($_GET[history]))
{
	$dumps_list = array_reverse(glob($module_path."dumps/*.pcap"));

	if(count($dumps_list) == 0)
		echo "<em>No dump history...</em>";
	
	for($i=0;$i<count($dumps_list);$i++)
	{
		if(basename($dumps_list[$i]) != "capture.log")
		{
			$info = explode("_", basename($dumps_list[$i]));
			echo date('Y-m-d H-i-s', $info[1])." - ";
			echo dataSize($module_path."dumps/".basename($dumps_list[$i]))." [";
			echo "<a href=\"javascript:javascript:location.href='dumps/".basename($dumps_list[$i])."'\">download</a> | ";
			echo "<a href=\"javascript:delete_file('".basename($dumps_list[$i])."');\">delete</a>]<br />";
		}
	}
}

?>