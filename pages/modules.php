<?php
#declare a boolean for updates
$updateWarning = false;

#Check if directory OR moduleList file does not exist.
#Prevent errors.
if(!file_exists("/pineapple/infusions/") || !file_exists("/pineapple/infusions/moduleList")){
        exec("mkdir -p /pineapple/infusions/");
        exec("touch /pineapple/infusions/moduleList");
}

?>

<div class="modules">

<?php

if(isset($_GET[install])){
	$name = $_GET[name];
	$version = $_GET[version];
	$size = $_GET[size];
	$md5 = $_GET[md5];
	$freeSize = disk_free_space("/")/1024;
	if($freeSize < 70 || $freeSize < $size+5){
		$warning = $strings["modules-sizeWarning"]."<br /><br /><br />";
		if(exec("mount | grep \"on /usb\" -c") >= 1) $warning .= "<a href=\"index.php?modules&doInstall=usb&name=$name&version=$version&md5=$md5&size=$size\">".$strings["modules-usbInstall"]."</a>";	}
	echo "<div class=\"contentTitle\">".$strings["modules-install-title"]."</div>";
	echo "<div class=\"contentContent\">";
	if($warning != ""){
		echo $warning;
	}else{
		echo $strings["modules-install-notification"]." \"$name\".<br />";
		echo "<br />";
		echo $strings["modules-install-destQuestion"]."<br />";
		echo "<a href=\"index.php?modules&doInstall=internal&name=$name&version=$version&md5=$md5\">".$strings["modules-install-internal"]."</a> ";
		if(exec("mount | grep \"on /usb\" -c") >= 1) echo "<a href=\"index.php?modules&doInstall=usb&name=$name&version=$version&md5=$md5&size=$size\">".$strings["modules-install-external"]."</a>";
	}
	echo "</div><br /><br />";
}


if(isset($_GET[doInstall])){
	installModule($_GET[name], $_GET[version], $_GET[doInstall], $_GET[md5], $_GET[size]);
	exit();

}

if(isset($_GET[remove])){
	removeModule($_GET[remove], $_GET[version], $_GET[dest]);
        echo "<font color=lime>".$strings["modules-js-removed"]."<br />".$strings["modules-js-pleaseWait"]."</font><br />";
        echo "<script type='text/javascript'>setTimeout(\"window.location='index.php?modules'\", 800);</script>";
        exit();
}

if(isset($_GET[update])){
	updateModule($_GET[name], $_GET[version], $_GET[md5], $_GET[size]);
	echo "<font color=lime>".$strings["modules-js-updated"]."<br />".$strings["modules-js-pleaseWait"]."</font><br />";
	exit();

}

if(isset($_GET[pin])){
	pinToNav($_GET[pin], $_GET[dest], $_GET[startPage]);
        echo "<script type='text/javascript'>window.location = 'index.php?modules';</script>";
}

if(isset($_GET[unpin])){
        unpinFromNav($_GET[unpin]);
        echo "<script type='text/javascript'>window.location = 'index.php?modules';</script>";
}



?>

<?php
if(isset($_GET[MD5error])){
	echo "<font color=red>Sorry, there was an MD5 missmatch. Please try again.</font><br /><br />";
}
?>

<div class="moduleTitle" align=left><?=$strings["modules-installed-title"]?></div>
<div class="moduleContent" align=left>
<?php
$localModules = getLocalList();
if($localModules[0] == ""){
	echo "<center>".$strings["modules-installed-noModules"]."</center>";
}else{

	#$usbPresent = (exec("mount | grep \"on /usb\" -c") >= 1)?true:false;
	$usbModules = false;
	$moduleArray = array();
	foreach($localModules as $module){
		$usbModules = false;
		$module = explode("|", $module);
		
		if($module[2] == "internal"){
			$size = dirSize("/pineapple/infusions/".$module[0]);
		}else{
			$size = dirSize("/pineapple/infusions/usbInfusions/".$module[0]);
		}
		if($module[4] == ""){
			$supportLink = "";
		}else{
			$supportLink = "<a href=\"".$module[4]."\" target=\"_blank\">".$strings["modules-links-supportLink"]."</a>";
		}
		if(isPinned($module[0], $module[2], $module[3])){
			$pinLink = "<a href='index.php?modules&unpin=$module[0]'>".$strings["modules-links-unpin"]."</a>";
		}else{
			$pinLink = "<a href='index.php?modules&pin=$module[0]&dest=$module[2]&startPage=$module[3]'>".$strings["modules-links-pin"]."</a>";
		}
		if($module[2] == "internal"){
			$launchLink = "<a href='/infusions/".$module[0]."/".$module[3]."'>".$module[0]."</a>";
		}else{
			$usbModules = true;
			$launchLink = "<a href='/infusions/usbInfusions/".$module[0]."/".$module[3]."'>".$module[0]."</a>";
		}
		$removeLink = "<a href='index.php?modules&remove=".$module[0]."&version=".$module[1]."&dest=".$module[2]."'>".$strings["modules-links-remove"]."</a>";
		
		if($usbPresent){
			array_push($moduleArray, "<tr><td>$launchLink</td><td>$module[1]</td><td>$module[2]</td><td>$size</td><td>$pinLink</td><td>$removeLink</td><td>$supportLink</td></tr>");
		}else if(!$usbModules){
			array_push($moduleArray, "<tr><td>$launchLink</td><td>$module[1]</td><td>$module[2]</td><td>$size</td><td>$pinLink</td><td>$removeLink</td><td>$supportLink</td></tr>");
		}

	}
	if(count($moduleArray) > 0){
		echo "<table><th>".$strings["modules-table-name"]."</th><th>".$strings["modules-table-version"]."</th><th>".$strings["modules-table-location"]."</th><th>".$strings["modules-table-size"]."</th>";
		foreach($moduleArray as $module) echo $module;
		echo "</table>";
	}else{
		echo "<center>There are no infusions installed on this device. Records show that there are some installed to a USB.<br />Please insert it to access the infusions again.</center>";
	}
}

?>
</div><br /><br />

<div class="moduleTitle" align=left><?=$strings["modules-available-title"]?></div>
<div class="moduleContent" align=left>
<?php
if(!isset($_GET[show])) echo "<a href=\"index.php?modules&show\">".$strings["modules-available-list"]."</a><br />".$strings["modules-available-warning"];
else{
        $remoteModules = getRemoteList();
        if(trim($remoteModules[0]) == "") echo "<center><font color=red>".$strings["modules-available-error"]."</font></center>";
        else drawRemoteModules($remoteModules);
}
?>
</div>


</div>


<?php

function isPinned($name, $dest, $startPage){
        if($dest == "internal") $link = "<b><a href='/infusions/".$name."/".$startPage."'><font color=black>$name</font></a></b>";
        else $link = "<b><a href='/infusions/usbInfusions/".$name."/".$startPage."'><font color=black>$name</font></a></b>";
	$links = explode("\n",file_get_contents("/pineapple/includes/moduleNav"));
	if(exec("cat /pineapple/includes/moduleNav | grep '$link'") != "")return true;
	return false;

}

function pinToNav($name, $dest, $startPage){
	if($dest == "internal") $link = "<b><a href='/infusions/".$name."/".$startPage."'><font color=black>$name</font></a></b>";
	else $link = "<b><a href='/infusions/usbInfusions/".$name."/".$startPage."'><font color=black>$name</font></a></b>";
	exec("echo '$link' >> /pineapple/includes/moduleNav");

}

function unpinFromNav($name){
	exec("sed -i '/$name/d' /pineapple/includes/moduleNav");

}

function dirSize($path){
	return exec( "/usr/bin/du -h $path | awk {'print $1'}" );
}

function removeModule($name, $version, $dest){

	exec("sed -i '/".$name."|".$version."/d' /pineapple/infusions/moduleList");
	if($dest == "internal") exec("rm -rf /pineapple/infusions/".$name);
	else exec("rm -rf /pineapple/infusions/usbInfusions/".$name);
	unpinFromNav($name);

}

function installModule($name, $version, $dest, $md5, $size){
	global $strings;

echo "

                              <script type='text/javascript' src='includes/jquery.min.js'></script>
                                <script type='text/javascript'>

                                $.ajax({
                                  url: 'modules/installer.php?name=".$name."&version=".$version."&dest=".$dest."&md5=".$md5."',
                                  cache: false,
                                  timeout: 10000,
                                  success: function(response){
                                  }
                                });

                                var loop=self.setInterval('checkInstall()',600);

                                function checkInstall(){

                                $.ajax({
                                  url: 'modules/installer.php?status&size=$size',
                                  cache: false,
                                  timeout: 10000,
                                  success: function(response){
                                        if(response == 'done') window.location = 'index.php?modules&done';
					if(response == 'md5') window.location = 'index.php?modules&MD5error';
                                  }
                                });

                                }

                                var sizeLoop=self.setInterval('checkSize()',400);

                                function checkSize(){

                                $.ajax({
                                  url: 'modules/installer.php?size=$size&name=$name&dest=$dest',
                                  cache: false,
                                  timeout: 10000,
                                  success: function(response){

					if(response == 'complete'){
						self.clearInterval(sizeLoop);
						$('#percent').text('Download complete. Installing the infusion.');
					}else{
						$('#percent').html(response);
					}
                                  }
                                });

                                }

                                </script>


";

echo "<font color=lime>Please wait, the infusion is being downloaded and installed.<br />Depending on network speed and infusion size, this could take a while.</font><br /><div id='percent' />";

}

function updateModule($name, $version, $md5, $size){

	$modules = getLocalList();
	foreach($modules as $module){
		$module =explode("|", $module);
		if($module[0] == $name){
			$localVersion = $module[1];
			$localDest = $module[2];
		}
	}
	removeModule($name, $localVersion, $localDest);
	installModule($name, $version, $localDest, $md5, $size);

}

function getRemoteList(){
	$remoteFile = trim(@file_get_contents("http://cloud.wifipineapple.com/index.php?downloads&moduleList"));
	$modules = explode("\n", $remoteFile);
	return $modules;
}

function getLocalList(){
	$localFile = trim(file_get_contents("infusions/moduleList"));
	$modules = explode("\n", $localFile);
	return $modules;
}

function isInstalled($module){
	global $strings;
	global $updateWarning;
	$localModules = getLocalList();
	foreach($localModules as $localModule){
		$localModule = explode("|", $localModule);
		if($localModule[0] == $module[0]){
			if($localModule[1] < $module[1]){
				if($updateWarning != true) echo "<script type='text/javascript'>alert('".$strings["modules-js-updateAlert"]."');</script>";
				$updateWarning = true;
				return 2;
			}
			else return 1;
		}
	}
	return 0;
}

function downloadLink($module){
	global $strings;
	$status = isInstalled($module);
	if($status == 2){
		return "<a href=\"index.php?modules&update&name=$module[0]&version=$module[1]&size=$module[4]&md5=$module[5]\">".$strings["modules-links-update"]."</a>";
	}else if($status == 1){
		return "Installed";	
	}else return"<a href=\"index.php?modules&install&name=$module[0]&version=$module[1]&size=$module[4]&md5=$module[5]\">".$strings["modules-links-install"]."</a>";
}

function drawRemoteModules($modules){
	global $strings;
	echo "<table>";
	echo "<tr><th>".$strings["modules-table-name"]."</th><span></span><th>".$strings["modules-table-version"]."</th><th>".$strings["modules-table-author"]."</th><th>".$strings["modules-table-description"]."</th><th>".$strings["modules-table-size"]."</th><th>".$strings["modules-table-action"]."</th></tr>";
	foreach($modules as $module){
		if($module != ""){
			$module = explode("|", $module);
			echo "<tr><td>$module[0]</td><td>$module[1]</td><td>$module[2]</td><td>$module[3]</td><td>$module[4]K</td><td align=right>".downloadLink($module)."</td></tr>\n";
		}
	}
	echo "</table>";
}

?>
