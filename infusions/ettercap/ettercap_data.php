<?php

require("ettercap_vars.php");

if (isset($_GET[history]))
{
	$log_list = array_reverse(glob($module_path."log/*.pcap"));

	if(count($log_list) == 0)
		echo "<em>No log history...</em>";
	
	for($i=0;$i<count($log_list);$i++)
	{
		$file = basename($log_list[$i],".pcap");
		$info = explode("_", basename($log_list[$i]));
		echo date('Y-m-d H-i-s', $info[1])." - ";
		echo dataSize($module_path."log/".basename($log_list[$i]))." [";
		echo "<a href=\"javascript:load_file('".$file.".log');\">view</a> | ";
		echo "<a href=\"javascript:javascript:location.href='log/".$file.".log'\">download</a> | ";
		echo "<a href=\"javascript:javascript:location.href='log/".$file.".pcap'\">pcap</a> | ";
		echo "<a href=\"javascript:delete_file('".$file."');\">delete</a>]<br />";
	}
}

if (isset($_GET[lastlog]))
{
	if ($is_ettercap_running)
	{
		$path = $module_path."log";

		$latest_ctime = 0;
		$latest_filename = '';  

		$d = dir($path);
		while (false !== ($entry = $d->read())) {
		  $filepath = "{$path}/{$entry}";
		  if (is_file($filepath) && filectime($filepath) > $latest_ctime) {
		      $latest_ctime = filectime($filepath);
		      $latest_filename = $entry;
		    }
		}
		
		echo $latest_filename;

		if($latest_filename != "")
		{
			$log_date = date ("F d Y H:i:s", filemtime($module_path."log/".$latest_filename));
			echo "ettercap ".$latest_filename." [".$log_date."]\n";
			echo file_get_contents($module_path."log/".$latest_filename);
		}
	}
	else
	{
		echo "ettercap is not running...";
	}
}

?>