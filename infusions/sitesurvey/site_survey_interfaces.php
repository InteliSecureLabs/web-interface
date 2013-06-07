<?php

require("site_survey_vars.php");

if(isset($_GET[monitor]))
{
	echo '<select id="monitorInterfaces" name="monitorInterfaces">';
	foreach($monitorInterfaces as $value) { echo '<option value="'.$value.'">'.$value.'</option>'; }
	echo '</select>';
}

if(isset($_GET[interface]))
{
	echo '<select id="interfaces" name="interfaces">';
	foreach($interfaces as $value) { echo '<option value="'.$value.'">'.$value.'</option>'; }
	echo '</select>';
}

?>