<style>
body {background-color:black; font-family: monospace; color: white;}
</style>
<?php
$os = $_POST['os'];
$ok= "<font style='color:green;'>[*]</font> ";
$error= "<font style='color:red;'>[!]</font> ";
if (!is_dir('/www/java'))
{
	$output = "$error Evil Java does not seem to be installed!<br />$ok Please install Evil Java and try again.";
}
elseif ($_FILES["file"]["error"] > 0)
{
	$output =  "$error Return Code: " . $_FILES["file"]["error"] . "<br />";
}
elseif ($_FILES["file"]["name"] == '')
{
	$output= "$error No file selected!";
}
else 
{
	if (file_exists("/www/java/" . $os . "/" . $_FILES["file"]["name"]))
	{
		$output=  $error . $_FILES["file"]["name"] . " already exists!"; 	
	}
	else
	{
		move_uploaded_file($_FILES["file"]["tmp_name"], "/www/java/" . $os . "/" . $_FILES["file"]["name"]);
		$output = "$ok Payload uploaded successfully!<br />$ok Stored in: " . "/www/java/"  . $os . "/" . $_FILES["file"]["name"] ;
	}
}

?>
<form id='form' action="index.php" method="post">
<input name="output" value="<?php echo $output ;?>" />
</form>

<script>
document.forms['form'].submit();
</script>

