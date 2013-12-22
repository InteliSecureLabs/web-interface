<script type="text/javascript">
	$(document).ready(function(){
		$("#tabs ul").idTabs();
	});
</script>
<?php

require("monitor_vars.php");

if(!file_exists("/var/lib/"))
{
	exec("mkdir -p /var/lib/");
}

if(file_exists("/usb/var/lib/vnstat/") && !file_exists("/var/lib/vnstat/"))
{
	exec("ln -s /usb/var/lib/vnstat /var/lib/vnstat");
}

if(!file_exists("/var/lib/vnstat/"))
{
	exec("mkdir -p /var/lib/vnstat/");
}

for($i=0;$i<count($interfaces);$i++)
{
	if(!file_exists("/var/lib/vnstat/".$interfaces[$i]))
		exec("vnstat -u -i ".$interfaces[$i]);
}

//exec("echo ".$module_path."graphs-vnstat.sh | at now");

echo '<div id="tabs" class="tab"><ul>';
for($i=0;$i<count($interfaces);$i++)
{
	if($i == 0) $class = "selected"; else $class = "";
	$tmp_int = str_replace(".", "_", $interfaces[$i]);
	
	echo '<li><a class="'.$class.'" href="#'.$tmp_int.'">'.$interfaces[$i].'</a></li>';
}
echo '</ul>';

$count=0;
for($i=0;$i<count($interfaces);$i++)
{
	$tmp_int = str_replace(".", "_", $interfaces[$i]);
	echo '<div id="'.$tmp_int.'">';
	
	for($j=0;$j<count($options);$j++)
	{
		if(file_exists($module_path.'vnstat/vnstat_'.$interfaces[$i].'_'.$options[$j].'.png'))
		{
			$count++;
			echo '<img src="vnstat/vnstat_'.$interfaces[$i].'_'.$options[$j].'.png?'.time().'" alt="'.$interfaces[$i].' '.$options_title[$options[$j]].'" />&nbsp;';
		
			if($j % 2 && $j != (count($options)-1)) echo "<br />";
		}
	}
	
	if($count == 0)
		echo "<em>No data available</em>";
	
	echo '</div>';
}

echo '</div>';

?>