<?php
if(!isset($strings)){
	$language = trim(file_get_contents("/pineapple/includes/languages/language"));
	$version = trim(file_get_contents("/pineapple/includes/fwversion"));
	$languageFile = "/pineapple/includes/languages/".$language."-".$version.".php";
	if(file_exists($languageFile)) require($languageFile);
	else require("/pineapple/includes/languages/english-".$version.".php");
}
?>


<div class=navbar>
| <a href="/index.php"><font color="black"><b><?=$strings["navbar-status"]?></b></font></a> | 
<a href="/index.php?config"><font color="black"><b><?=$strings["navbar-configuration"]?></b></font></a> | 
<a href="/index.php?advanced"><font color="black"><b><?=$strings["navbar-advanced"]?></b></font></a> | 
<a href="/index.php?usb"><font color="black"><b><?=$strings["navbar-usb"]?></b></font></a> | 
<a href="/index.php?jobs"><font color="black"><b><?=$strings["navbar-jobs"]?></b></font></a> | 
<a href="/index.php?ssh"><font color="black"><b><?=$strings["navbar-ssh"]?></b></font></a> | 
<a href="/index.php?scripts"><font color="black"><b><?=$strings["navbar-scripts"]?></b></font></a> | 
<a href="/index.php?logs"><font color="black"><b><?=$strings["navbar-logs"]?></b></font></a> | 
<a href="/index.php?resources"><font color="black"><b><?=$strings["navbar-resources"]?></b></font></a> | 
<a href="/index.php?modules"><font color="black"><b><?=$strings["navbar-modules"]?></b></font></a> | 
<a href="/index.php?about"><font color="black"><b><?=$strings["navbar-about"]?></b></font></a> |

<?php
$moduleLinks = explode("\n", file_get_contents("/pineapple/includes/moduleNav"));
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
