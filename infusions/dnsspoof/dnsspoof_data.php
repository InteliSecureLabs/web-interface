<?php

require("dnsspoof_vars.php");

if (isset($_GET[history]))
{
	$log_list = array_reverse(glob($module_path."log/*"));

	if(count($log_list) == 0)
		echo "<em>No log history...</em>";
	
	for($i=0;$i<count($log_list);$i++)
	{
		if(basename($log_list[$i]) != "tmp")
		{
			$info = explode("_", basename($log_list[$i]));
			echo date('Y-m-d H-i-s', $info[1])." [";
			echo "<a href=\"javascript:load_file('".basename($log_list[$i])."');\">view</a> | ";
			echo "<a href=\"javascript:javascript:location.href='log/".basename($log_list[$i])."'\">download</a> | ";
			echo "<a href=\"javascript:delete_file('log','".basename($log_list[$i])."');\">delete</a>]<br />";
		}
	}
}

if (isset($_GET[lastlog]))
{
	if ($is_dnsspoof_running)
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

		if($latest_filename != "")
		{
			$log_date = date ("F d Y H:i:s", filemtime($module_path."log/".$latest_filename));
			echo "dnsspoof ".$latest_filename." [".$log_date."]\n";

			$cmd = "cat ".$module_path."log/".$latest_filename;
				
			exec ($cmd, $output); foreach($output as $outputline) { echo ("$outputline\n"); }
		}
	}
	else
	{
		echo "dnsspoof is not running...";
	}
}

?>