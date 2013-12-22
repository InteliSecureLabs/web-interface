<?php

require("mitm_vars.php");

?>
<html>
<head>
<title>Pineapple Control Center - <?php echo $module_name." [v".$module_version."]"; ?></title>
<script type="text/javascript" src="/includes/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>

<script type="text/javascript" src="js/mitm.js"></script>
<link rel="stylesheet" type="text/css" href="css/mitm.css" />
<link rel="stylesheet" type="text/css" href="css/firmware.css" />

<link rel="icon" href="/favicon.ico" type="image/x-icon"> 
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green">

<script type="text/javascript" charset="utf-8">
	$(document).ready( function () { init(); });
</script>

<?php if(file_exists("/pineapple/includes/navbar.php")) require('/pineapple/includes/navbar.php'); ?>

<p id="version">v<?php echo $module_version; ?></p>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $module_name; ?> <span id="refresh_text"></span></div>
<div class=sidePanelContent>
<?php
if ($usb_mnt)
{
	if($installed)
	{
		if ($is_mitm_installed)
		{
			echo "MITM proxy <span id=\"usb_status\"><font color=\"lime\"><strong>installed</strong></font></span><br /><br />";
		} 
		else 
		{ 
			echo "MITM proxy <span id=\"usb_status\"><font color=\"red\"><strong>not installed</strong></font></span><br /><br />";
			
			echo "All required dependencies have not been correctly installed.<br /><br />";
		
			echo "Please wait, do not leave or refresh this page. Once the install is complete, this page will refresh automatically.<br /><br />";
		
			echo '[<a id="Install" href="javascript:install();">Install</a>]';
				
			exit();
		}
		
		if ($is_mitm_running) 
		{
			echo "MITM proxy <span id=\"proxy_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
			echo " | <a id=\"proxy_link\" href=\"javascript:proxy_toggle('stop');\"><strong>Stop</strong></a><br /><br />";
		} 
		else 
		{ 
			echo "MITM proxy <span id=\"proxy_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
			echo " | <a id=\"proxy_link\" href=\"javascript:proxy_toggle('start');\"><strong>Start</strong></a><br /><br />"; 
		}

		if($is_mitm_running)
			echo '<select disabled="disabled" id="script" name="script">';
		else
			echo '<select id="script" name="script">';
	
		echo '<option>--</option>';
		$scripts_list = array_reverse(glob($module_path."scripts/*.py"));

		for($i=0;$i<count($scripts_list);$i++)
		{
			if($mitm_conf == basename($scripts_list[$i]))
				echo '<option selected value="'.basename($scripts_list[$i]).'">'.basename($scripts_list[$i]).'</option>';
			else
				echo '<option value="'.basename($scripts_list[$i]).'">'.basename($scripts_list[$i]).'</option>';
		}
		echo '</select> [<a id="refresh_script" href="javascript:refresh_script();">Refresh</a>]';
	}
	else
	{
		echo "All required dependencies have to be installed first. This may take a few minutes.<br /><br />";
		
		echo "Please wait, do not leave or refresh this page. Once the install is complete, this page will refresh automatically.<br /><br />";
		
		echo '[<a id="Install" href="javascript:install();">Install</a>]';
				
		exit();
	}
}
else
{	
	echo "USB mount <font color=\"red\"><strong>not found</strong></font><br /><br />";
	echo "Installing dependencies to main memory is not permitted.<br /><br />Please use a USB drive.";
			
	exit();	
}
?>
</div>
</div>

<div id="tabs" class="tab">
	<ul>
		<li><a id="Output_link" class="selected" href="#Output">Output</a></li>
		<li><a id="History_link" href="#History">History</a></li>
		<li><a id="Editor_link" href="#Editor">Editor</a></li>
		<li><a id="Helpers_link" href="#Helpers">Helpers</a></li>
	</ul>
	
<div id="Output">
	[<a id="refresh" href="javascript:refresh();">Refresh</a>]<br /><br />
	<textarea id='output' name='output' cols='85' rows='29'></textarea>
</div>

<div id="History">
	[<a id="refresh" href="javascript:refresh_history('history');">Refresh</a>]<br />
	<div id="content_history"></div>
</div>

<div id="Editor">
	<table class="grid" cellspacing="0">
		<tr>
			<td>Filter: </td>
			<td>
				<select id="script_editor" name="script_editor">
				<option>--</option>
				<?php
					$scripts_list = array_reverse(glob($module_path."scripts/*.py"));

					for($i=0;$i<count($scripts_list);$i++)
					{
						echo '<option value="'.basename($scripts_list[$i]).'">'.basename($scripts_list[$i]).'</option>';
					}
				?>
				</select> [<a id="delete_script" href="javascript:delete_script();">Delete Filter</a>]
			</td>
		</tr>
		<tr>
			<td>Name: </td>
			<td>
				<input type="text" id="script_name" name="script_name" value="" size="50"> [<a id="new_script" href="javascript:new_script();">New Filter</a>] <span id="error_text"></span>
			</td>
		</tr>	
		<tr>
			<td>&nbsp;</td>
			<td>
				<textarea id='script_content' name='script_content' cols='114' rows='29'></textarea><br/><br/>
				[<a id="save_script" href="javascript:save_script();">Save Filter</a>]	
			</td>
		</tr>
	</table>
</div>

<div id="Helpers">
	<?
		if ($is_snowstorm_installed)
		{
			echo "Snow Storm (javascript) <span id=\"snowstorm_status\"><font color=\"lime\"><strong>installed</strong></font></span>";
			echo " | <a id=\"snowstorm_link\" href=\"javascript:helpers_toggle('snowstorm','uninstall');\"><strong>Uninstall</strong></a><br />";
		} 
		else 
		{ 
			echo "Snow Storm (javascript) <span id=\"snowstorm_status\"><font color=\"red\"><strong>not installed</strong></font></span>";
			echo " | <a id=\"snowstorm_link\" href=\"javascript:helpers_toggle('snowstorm','install');\"><strong>Install</strong></a><br />";
		}
		
		if ($is_fool_installed) 
		{
			echo "Fool (javascript) <span id=\"fool_status\"><font color=\"lime\"><strong>installed</strong></font></span>";
			echo " | <a id=\"fool_link\" href=\"javascript:helpers_toggle('fool','uninstall');\"><strong>Uninstall</strong></a><br />";
		} 
		else 
		{ 
			echo "Fool (javascript) <span id=\"fool_status\"><font color=\"red\"><strong>not installed</strong></font></span>";
			echo " | <a id=\"fool_link\" href=\"javascript:helpers_toggle('fool','install');\"><strong>Install</strong></a><br />";
		}
	?>
</div>

</div>
<br />
Auto-refresh <select id="auto_time">
	<option value="1000">1 sec</option>
	<option value="5000">5 sec</option>
	<option value="10000">10 sec</option>
	<option value="15000">15 sec</option>
	<option value="20000">20 sec</option>
	<option value="25000">25 sec</option>
	<option value="30000">30 sec</option>
</select> <a id="auto_refresh" href="javascript:void(0);"><font color="red">Off</font></a>

</body>
</html>
