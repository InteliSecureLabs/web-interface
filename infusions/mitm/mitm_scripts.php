<?php

require("mitm_vars.php");

if (isset($_GET['script_list']))
{
	$scripts_list = array_reverse(glob($module_path."scripts/*.py"));
	echo '<option>--</option>';
	for($i=0;$i<count($scripts_list);$i++)
	{
		echo '<option value="'.basename($scripts_list[$i]).'">'.basename($scripts_list[$i]).'</option>';
	}
}

if (isset($_GET['show_script']))
{
	if (isset($_GET['which']))
	{
		$file = $module_path."scripts/".$_GET['which'];
		echo file_get_contents($file);
	}
}

if (isset($_GET['delete_script']))
{
	if (isset($_GET['which']))
	{
		exec("rm -rf ".$module_path."scripts/".$_GET['which']."*");
	}
}

if (isset($_POST['new_script']))
{
	if (isset($_POST['which']))
	{
		$filename = $module_path."scripts/".$_POST['which'];
		
		if(!file_exists($filename))
		{
			$newdata = $_POST['newdata'];
			$newdata = ereg_replace(13,  "", $newdata);
			$fw = fopen($filename, 'w+');
			$fb = fwrite($fw,stripslashes($newdata));
			fclose($fw);
		}
	}
}

if (isset($_POST['save_script']))
{
	if (isset($_POST['which']))
	{
		$filename = $module_path."scripts/".$_POST['which'];

		$newdata = $_POST['newdata'];
		$newdata = ereg_replace(13,  "", $newdata);
		$fw = fopen($filename, 'w');
		$fb = fwrite($fw,stripslashes($newdata));
		fclose($fw);
	}
}

?>