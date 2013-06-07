<?php

require("sslstrip_vars.php");

if (isset($_GET['history']))
{
	$log_list = array_reverse(glob($module_path."log/*"));

	if(count($log_list) == 0)
		echo "<em>No log history...</em>";
	
	for($i=0;$i<count($log_list);$i++)
	{
		$tags = array("FILENAME" => $module_path."log/".basename($log_list[$i]));
		$custom_command = addslashes(replace_tags($tags, $custom_commands[0]));
			
		$info = explode("_", basename($log_list[$i]));
		echo date('Y-m-d H-i-s', $info[1])." [";
		echo "<a href=\"javascript:load_file('log','".basename($log_list[$i])."');\">view</a> | ";
		echo "<a href=\"javascript:javascript:location.href='log/".basename($log_list[$i])."'\">download</a> | ";
		echo "<a href=\"javascript:execute_custom_script('".base64_encode($custom_command)."');\">exec</a> | ";
		echo "<a href=\"javascript:delete_file('log','".basename($log_list[$i])."');\">delete</a>]<br />";
	}
}

if (isset($_GET['custom']))
{
	$log_list = array_reverse(glob($module_path."custom/*"));

	if(count($log_list) == 0)
		echo "<em>No custom history...</em>";
	
	for($i=0;$i<count($log_list);$i++)
	{
		$info = explode("_", basename($log_list[$i]));
		echo date('Y-m-d H-i-s', $info[1])." [";
		echo "<a href=\"javascript:load_file('custom','".basename($log_list[$i])."');\">view</a> | ";
		echo "<a href=\"javascript:javascript:location.href='custom/".basename($log_list[$i])."'\">download</a> | ";
		echo "<a href=\"javascript:delete_file('custom','".basename($log_list[$i])."');\">delete</a>]<br />";
	}
}

if (isset($_GET['lastlog']))
{
	if ($is_sslstrip_running)
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
			echo "sslstrip ".$latest_filename." [".$log_date."]\n";
			
			if (isset($_GET['filter']) && $_GET['filter'] != "")
			{
				$filter = stripslashes($_GET['filter']);
				echo "Filter: ".$filter."\n";
				
				$cmd = "cat ".$module_path."log/".$latest_filename." | ".$filter;
			}
			else
			{
				$cmd = "cat ".$module_path."log/".$latest_filename;
			}
				
			exec ($cmd, $output); foreach($output as $outputline) { echo ("$outputline\n"); }
		}
	}
	else
	{
		echo "sslstrip is not running...";
	}
}

?>