<?php

require("logcheck_vars.php");

if($is_logcheck_running)
{
	exec("grep -hv -e ^# ".$match_path." -e ^$ > ".$module_path."rules/match.tmp");
	exec("grep -hv -e ^# ".$ignore_path." -e ^$ > ".$module_path."rules/ignore.tmp");

	exec("cat ".$module_path."events | grep -Ef ".$module_path."rules/match.tmp | grep -vEf ".$module_path."rules/ignore.tmp" , $output);

	if(empty($output)) echo "No Filtered logs\n"; else echo "Filtered logs\n";
	foreach($output as $outputline) echo ("$outputline\n");
}
else
{
	echo "Logcheck is not running...";
}
?>