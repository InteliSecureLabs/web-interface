<?php

require("nmap_vars.php");

if (isset($_GET[history]))
{
	$scans_list = array_reverse(glob($module_path."scans/*"));

	if(count($scans_list) == 0)
		echo "<em>No scan history...</em>";
	
	for($i=0;$i<count($scans_list);$i++)
	{
		if(basename($scans_list[$i]) != "tmp")
		{
			$info = explode("_", basename($scans_list[$i]));
			echo date('Y-m-d H-i-s', $info[1])." [";
			echo "<a href=\"javascript:load_file('".basename($scans_list[$i])."');\">view</a> | ";
			echo "<a href=\"javascript:javascript:location.href='scans/".basename($scans_list[$i])."'\">download</a> | ";
			echo "<a href=\"javascript:delete_file('".basename($scans_list[$i])."');\">delete</a>]<br />";
		}
	}
}

if (isset($_GET[control]))
{
	if($is_nmap_running)
	{
		echo '<a id="scan" href="javascript:scan_toggle(\'cancel\');"><font color="red"><strong>Cancel</strong></font></a>';
		echo '<script type="text/javascript" charset="utf-8">$(document).ready( function () { refresh_output(); });</script>';
	}
	else
	{
		echo '<a id="scan" href="javascript:scan_toggle(\'scan\');"><font color="lime"><strong>Scan</strong></font></a>';
		echo '<script type="text/javascript" charset="utf-8">$(document).ready( function () { clearInterval(auto_refresh); refresh_output(); refresh_history(); });</script>';
	}
}

if (isset($_GET[lastscan]))
{
	if(file_exists($module_path."scans/tmp"))
	{	
		echo file_get_contents($module_path."scans/tmp");
	}
	else
	{
		$path = $module_path."scans";

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
		
		echo file_get_contents($module_path."scans/".$latest_filename);
	}
}

?>