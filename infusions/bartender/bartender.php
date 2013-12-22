<?php
	#Create projects directory & Clean up
	if(!file_exists("projects")) mkdir("projects");
	if(!file_exists("downloads")) mkdir("downloads");
	if(!file_exists("/tmp/modules/downloads")) exec("mkdir -p /tmp/modules/downloads");
	exec("rm downloads/*");


	#Remove an existing project
	if(isset($_GET[remove])){
		if(remove($_GET[remove])) $message = "<font color=lime>Sucessfully removed $_GET[remove]</font>";
		else $message = "<font color=red>Error removing the infusion $_GET[remove]</font>";
	}

	#Edit Infusion code
	#Close / Discard
	if(isset($_POST[discard])){
	  header("Status: 302 Found");
	  header("Location: $_SERVER[PHP_SELF]"); 
	}

	#Create new infusion
	if(isset($_POST[create])){
		create($_POST[name]);
	}
	
	#Save Changes
	if(isset($_POST[save])){
		if(saveChanges($_POST)){
			$_POST[newName] = strtolower(str_replace(" ", "", $_POST[newName]));
			header("Status: 302 Found");
			header("Location: $_SERVER[PHP_SELF]?edit=$_POST[newName]&success");
		}

	}
	if(isset($_GET[edit])){
		if(isset($_GET[success])) $editMessage = "<font color=lime>Changes saved sucessfully</font>";
		else if(isset($_GET[fail])) $editMessage = "<font color=red>Changes failed to save. Please try again.</font>";
	}

	#Download
	if(isset($_GET[download])){
		$infusionConfig = getInfusionConfig($_GET[download]);
		download($_GET[download], $infusionConfig[1][1]);
	}
	
	$infusions = getInfusions();

?>

<html>
    <head>
    <title>Pineapple Bar: Bartender</title>
    <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <link rel="stylesheet" type="text/css" href="/includes/styles.css" /> 
    <link rel="icon" href="/favicon.ico" type="image/x-icon"> 
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"> 
    </head>
<body>
<?php include("/pineapple/includes/navbar.php"); ?>
<br /><br /><br />

<div class=content>

<?php if(isset($_GET[edit])){ 
$infusionConfig = getInfusionConfig($_GET[edit]);
if($infusionConfig == " ") $infusionCOnfig[0][1] = $_GET[edit];
?>
<div class=contentTitle>Editing <?=$_GET[edit]?></div>
<div class=contentContent>
<center>
<?=$editMessage?><br /><br />
<form action="" method="POST">
<input type="hidden" name="name" value="<?=$_GET[edit]?>">
<table>
<tr><td>Name: </td><td><input type="text" name="newName" value="<?=$infusionConfig[0][1]?>"></td></tr>
<tr><td>Version: </td><td><input type="text" name="version" value="<?=$infusionConfig[1][1]?>"></td></tr>
<tr><td>Author: </td><td><input type="text" name="author" value="<?=$infusionConfig[2][1]?>"></td></tr>
<tr><td>Start Page: </td><td><input type="text" name="startPage" value="<?=$infusionConfig[3][1]?>"></td></tr>
<tr><td>Support Link: </td><td><input type="text" name="supportLink" value="<?=$infusionConfig[4][1]?>"></td></tr>



</table>
<input type="submit" name="save" value="Save Changes"><input type="submit" name="discard" value="Close / Discard Changes">

</form>
</div>
<br /><br />
<?php } ?>



<div class=contentTitle>Existing Infusions</div>
<div class=moduleContent>
<?=$message?><br />
<table>
<?php
if($infusions[0] != ""){
foreach($infusions as $infusion){ ?>

<tr><td><?=$infusion?></td><td><a href="?remove=<?=$infusion?>" onClick="return confirm('Are you sure you want to remove this module?\nAny work will be lost!')">Remove</a></td><td><a href="?edit=<?=$infusion?>">Edit</td><td><a href="?download=<?=$infusion?>">Download</td><td><a href="projects/<?=$infusions[0]?>">Launch</a></td></tr>

<?php
}}else echo "<small>You have not created any infusions yet. Please create one below!</small>";
?>
</table>	
</div>
<br /><br />


<div class=contentTitle>Create New Infusion</div>
<div class=contentContent>
<center><form method="POST" action="">
Enter a name below and hit "create"!<br /><br />
<input type=text name=name><br />
<input type=submit name=create value="Create">

</form>
<small>Once you have created a new infusion, you can start uploading your files to "/pineapple/infusions/bartender/projects/YourInfusionName/"</small>
</div>
<br /><br />


<div class=contentTitle>Help</div>
<div class=contentContent><center>
To use the Bartender, please register a module developer account at <a href="https://wifipineapple.com/?portal">http://cloud.wifipineapple.com</a>.<br /><br />After having done this, you can start by creating a new Infusion above. Then upload your files to "/pineapple/infusions/bartender/projects/YourInfusionName/".<br /><br />To view your modules, click the launch link above.<br /><br />When you are happy with the way your module looks and works, simply hit the download link and submit the resulting file through the <a href="https://wifipineapple.com/?portal&portal_infusions">Module Submission System</a>.
</div>
<small><font color=red>Note: This module is horrible. It will be obsolete very soon which is why development hasn't really continued.
Apologies to anyone using this.</font></small>


<?php

	function getInfusions(){
		
		$infusions = array();
		$folder=dir("projects");
		while($file=$folder->read())
		{
			if ($file !="." && $file !="..")
			{
				$infusions[] = $file;
			} 
		}
		return $infusions;
		
	}


	function remove($infusion){
		exec("rm -rf projects/$infusion");
		return true;
	}

	function getInfusionConfig($infusion){
		$array = explode("\n", file_get_contents("projects/".$infusion."/module.conf"));
		$infusionConfig = array();

		foreach($array as $line){
			$line = explode("=", $line);
			$infusionConfig[] = $line;
		}
		return $infusionConfig;
	}

	
	function saveChanges($posts){
		$posts[newName] = strtolower(str_replace(" ", "", $posts[newName]));
		if($posts[name] != $posts[newName]){
			rename("projects/".$posts[name], "projects/".$posts[newName]);
		}

		$string = "";
		foreach($posts as $post=>$value){
			if($post != "name" && $post != "save" && $post != "discard"){
				$string .= $post."=".$value."\n";
			}
		}
		$fh = fopen("projects/".$posts[newName]."/module.conf", "w");
		fwrite($fh, $string);
		fclose($fh);
		return true;
	}

	function create($name){
		$name = strtolower(str_replace(" ", "", $name));
		if($name == "") return;

	        $infusions = getInfusions();
		foreach($infusions as $infusion){
			if($infusion == $name) return;
		}

		exec("mkdir projects/".$name);
		exec("touch projects/".$name."/module.conf");
		exec("echo name=".$name." > projects/".$name."/module.conf");
	}

	function download($name, $version){

		exec("mkdir /tmp/modules/downloads/mk4-module-".$name."-".$version);
		exec("cp -r projects/".$name." /tmp/modules/downloads/mk4-module-".$name."-".$version."/");
		exec("mv /tmp/modules/downloads/mk4-module-".$name."-".$version."/".$name."/module.conf /tmp/modules/downloads/mk4-module-".$name."-".$version."/module.conf");
		exec("cd /tmp/modules/downloads/ && tar -pczf /tmp/modules/downloads/mk4-module-".$name."-".$version.".tar.gz mk4-module-".$name."-".$version);
		exec("cp /tmp/modules/downloads/mk4-module-".$name."-".$version.".tar.gz downloads/");
		header("Status: 302 Found");
		header("Location: downloads/mk4-module-".$name."-".$version.".tar.gz");
		
	}
?>
