<?php

require("ettercap_vars.php");

if (isset($_GET['filter_list']))
{
	$filters_list = array_reverse(glob($module_path."filters/*.ef"));
	echo '<option>--</option>';
	for($i=0;$i<count($filters_list);$i++)
	{
		echo '<option value="-F '.$filters_list[$i].'">'.basename($filters_list[$i]).'</option>';
	}
}

if (isset($_GET['show_filter']))
{
	if (isset($_GET['which']))
	{
		$file = $module_path."filters/".$_GET['which'].".filter";
		echo file_get_contents($file);
	}
}

if (isset($_GET['delete_filter']))
{
	if (isset($_GET['which']))
	{
		exec("rm -rf ".$module_path."filters/".$_GET['which']."*");
	}
}

if (isset($_GET['compile_filter']))
{
	if (isset($_GET['which']))
	{
		$filename = $module_path."filters/".$_GET['which'].".filter";
		$filename_ef = $module_path."filters/".$_GET['which'].".ef";
		
		echo "Compile: ".$filename." to:".$filename_ef."\n";
		
		$output = shell_exec("etterfilter -o ".$filename_ef." ".$filename." 2>&1");
		echo trim($output);
	}
}

if (isset($_POST['new_filter']))
{
	if (isset($_POST['which']))
	{
		$filename = $module_path."filters/".$_POST['which'].".filter";
		
		if(!file_exists($filename))
		{
			$newdata = $_POST['newdata'];
			$newdata = ereg_replace(13,  "", $newdata);
			$fw = fopen($filename, 'w+');
			$fb = fwrite($fw,stripslashes($newdata));
			fclose($fw);
		
			$filename_ef = $module_path."filters/".$_POST['which'].".ef";
		}
	}
}

if (isset($_POST['save_filter']))
{
	if (isset($_POST['which']))
	{
		$filename = $module_path."filters/".$_POST['which'].".filter";

		$newdata = $_POST['newdata'];
		$newdata = ereg_replace(13,  "", $newdata);
		$fw = fopen($filename, 'w');
		$fb = fwrite($fw,stripslashes($newdata));
		fclose($fw);
	}
}

?>