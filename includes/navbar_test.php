<?php
if(!isset($strings)){
	$language = trim(file_get_contents("/opt/pwnpad/web-interface/includes/languages/language"));
	$version = trim(file_get_contents("/opt/pwnpad/web-interface/includes/fwversion"));
	$languageFile = "/opt/pwnpad/web-interface/includes/languages/".$language."-".$version.".php";
	if(file_exists($languageFile)) require($languageFile);
	else require("/opt/pwnpad/web-interface/includes/languages/english-".$version.".php");
}
?>
<html>
<script type="text/javascript" src="jquery.jpanelmenu.min.js"></script>
<header class="main">
<ul id="menu">
<div class="menu-trigger">Click Me</div>
<li><a href="/index.php"><font color="black"><b><?=$strings["navbar-status"]?></b></font></a></li>
<li><a href="/index.php?config"><font color="black"><b><?=$strings["navbar-configuration"]?></b></font></a></li>
<li><a href="/index.php?advanced"><font color="black"><b><?=$strings["navbar-advanced"]?></b></font></a></li>
<li><a href="/index.php?usb"><font color="black"><b><?=$strings["navbar-usb"]?></b></font></a></li>
<li><a href="/index.php?jobs"><font color="black"><b><?=$strings["navbar-jobs"]?></b></font></a></li>
<li><a href="/index.php?ssh"><font color="black"><b><?=$strings["navbar-ssh"]?></b></font></a></li>
<li><a href="/index.php?scripts"><font color="black"><b><?=$strings["navbar-scripts"]?></b></font></a></li>
<li><a href="/index.php?logs"><font color="black"><b><?=$strings["navbar-logs"]?></b></font></a></li>
<li><a href="/index.php?resources"><font color="black"><b><?=$strings["navbar-resources"]?></b></font></a></li>
<li><a href="/index.php?modules"><font color="black"><b><?=$strings["navbar-modules"]?></b></font></a></li>
<li><a href="/index.php?about"><font color="black"><b><?=$strings["navbar-about"]?></b></font></a></li>
</ul>
</header>
<script type="text/javascript">
    $(document).ready(function () {
        var jPM = $.jPanelMenu();
        jPM.on();
    });
</script>
</html>
<?php
$moduleLinks = explode("\n", file_get_contents("/opt/pwnpad/web-interface/includes/moduleNav"));
if(trim($moduleLinks != "")){
        echo "<br />";
        $first = true;
        foreach($moduleLinks as $link){
                if($first){
                        if($link != "") echo " ".$link." ";
                        $first = false;
                }else if($link != ""){
                        echo "| ".$link." ";
                }
        }
}
?>
</div>
