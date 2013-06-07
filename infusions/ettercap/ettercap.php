<?php

require("ettercap_vars.php");

?>
<html>
<head>
<title>Pineapple Control Center - <?php echo $module_name." [v".$module_version."]"; ?></title>
<script type="text/javascript" src="/includes/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>

<script type="text/javascript" src="js/ettercap.js"></script>
<link rel="stylesheet" type="text/css" href="css/ettercap.css" />
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
if($is_ettercap_installed)
{
	echo "ettercap";
	echo "&nbsp;<font color=\"lime\"><strong>installed</strong></font><br />";
}
else
{
	echo "ettercap";
	echo "&nbsp;<font color=\"red\"><strong>not installed</strong></font><br /><br />";
	
	echo "Install to <a id=\"install_int\" href=\"javascript:install('internal');\">Internal Storage</a> or <a id=\"install_usb\" href=\"javascript:install('usb');\">USB Storage</a>";
		
	exit();
}

?>
</div>
</div>

<div id="tabs" class="tab">
	<ul>
		<li><a class="selected" href="#General">General</a></li>
		<li><a href="#Visualization">Visualization</a></li>
		<li><a href="#MITM">MITM</a></li>
		<li><a href="#Options">Options</a></li>
		<li><a href="#Filters">Filters</a></li>
		<li><a href="#Editor">Editor</a></li>
	</ul>
	
<div id="General">
	<table class="grid" cellspacing="0">
		<tr>
			<td>Interface: </td>
			<td><select id="interface" name="interface">
			<option>--</option>
			<?php
				foreach($interfaces as $key => $value)
				{
					echo '<option value="'.$value.'">'.$key.'</option>';
				}
			?>
			</select></td>
		</tr>	
		<tr>
			<td>Target 1: </td>
			<td><input type="text" id="target_1" name="target_1" value="" size="70"></td>
		</tr>
		<tr>
			<td>Target 2: </td>
			<td><input type="text" id="target_2" name="target_2" value="" size="70"></td>
		</tr>
	</table>
</div>

<div id="MITM">
	<table class="grid" cellspacing="0">
		<tr>
			<td>Perform a mitm attack: </td>
			<td><select id="mitm_options" name="mitm_options">
			<option>--</option>
			<?php
				foreach($mitm_options as $key => $value)
				{
					echo '<option value="'.$value.'">'.$key.'</option>';
				}
			?>
			</select>
			<select id="mitm_options_param" name="mitm_options_param">
			<option>--</option>
			</select>
			</td>
		</tr>
	</table>
</div>

<div id="Visualization">
	<table class="grid" cellspacing="0">
		<tr>
			<td>Visualization method: </td>
			<td><select id="visualization_format" name="visualization_format">
			<option>--</option>
			<?php
				foreach($visualization_format as $key => $value)
				{
					echo '<option value="'.$value.'">'.$key.'</option>';
				}
			?>
			</select></td>
		</tr>
		<tr>
			<td colspan="2">
			<?php
				foreach($visualization_options as $key => $value)
				{
					echo '<input type="checkbox" name="'.$key.'" value="'.$value.'" />'.$key."<br />";
				}
			?>
			</td>
		</tr>
	</table>
</div>

<div id="Options">
	<table class="grid" cellspacing="0">
		<tr>
			<td>Sniff only PROTO packets: </td>
			<td><select id="proto_options" name="proto_options">
			<option>--</option>
			<?php
				foreach($proto_options as $key => $value)
				{
					echo '<option value="'.$value.'">'.$key.'</option>';
				}
			?>
			</select></td>
		</tr>
		<tr>
			<td colspan="2">
			<?php
				foreach($sniffing_and_attack_options as $key => $value)
				{
					echo '<input type="checkbox" name="'.$key.'" value="'.$value.'" />'.$key."<br />";
				}
			?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
			<?php
				foreach($ui_type as $key => $value)
				{
					echo '<input type="checkbox" name="'.$key.'" value="'.$value.'" />'.$key."<br />";
				}
			?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
			<?php
				foreach($general_options as $key => $value)
				{
					echo '<input type="checkbox" name="'.$key.'" value="'.$value.'" />'.$key."<br />";
				}
			?>
			</td>
		</tr>
	</table>
</div>

<div id="Filters">
	<table class="grid" cellspacing="0">
		<tr>
			<td>Filter: </td>
			<td>
				<select id="filter" name="filter">
				<option>--</option>
				<?php
					$filters_list = array_reverse(glob($module_path."filters/*.ef"));

					for($i=0;$i<count($filters_list);$i++)
					{
						echo '<option value="-F '.$filters_list[$i].'">'.basename($filters_list[$i]).'</option>';
					}
				?>
				</select> [<a id="refresh_filter" href="javascript:refresh_filter();">Refresh Filter List</a>]
			</td>
		</tr>
	</table>
</div>

<div id="Editor">
	<table class="grid" cellspacing="0">
		<tr>
			<td>Filter: </td>
			<td>
				<select id="filter_editor" name="filter_editor">
				<option>--</option>
				<?php
					$filters_list = array_reverse(glob($module_path."filters/*.filter"));

					for($i=0;$i<count($filters_list);$i++)
					{
						echo '<option value="'.basename($filters_list[$i],".filter").'">'.basename($filters_list[$i]).'</option>';
					}
				?>
				</select> [<a id="delete_filter" href="javascript:delete_filter();">Delete Filter</a>]
			</td>
		</tr>
		<tr>
			<td>Name: </td>
			<td>
				<input type="text" id="filter_name" name="filter_name" value="" size="50"> [<a id="new_filter" href="javascript:new_filter();">New Filter</a>]
			</td>
		</tr>	
		<tr>
			<td>&nbsp;</td>
			<td>
				<textarea id='filter_content' name='filter_content' cols='114' rows='29'></textarea><br/><br/>
				[<a id="save_filter" href="javascript:save_filter();">Save Filter</a>] [<a id="compile_filter" href="javascript:compile_filter();">Compile Filter</a>]	
			</td>
		</tr>
	</table>
</div>

<div style="border-top: 1px solid black;">
Command: <input type="text" id="command" name="command" value="ettercap " size="115"><br /><br />
<span id="control">
	<?php
	if($is_ettercap_running)
	{
		echo '<a id="launch" href="javascript:ettercap_toggle(\'stop\');"><font color="red"><strong>Stop</strong></font></a>';
	}
	else
	{
		echo '<a id="launch" href="javascript:ettercap_toggle(\'start\');"><font color="lime"><strong>Start</strong></font></a>';
	}
	?>
</span>
</div>

</div>

<div id="tabs2" class="tab">
	<ul>
		<li><a id="Output_link" class="selected" href="#Output">Output</a></li>
		<li><a id="History_link" href="#History">History</a></li>
	</ul>
	
<div id="Output">
	[<a id="refresh" href="javascript:refresh();">Refresh</a>]<br /><br />
	<textarea id='output' name='output' cols='85' rows='29'></textarea>
</div>

<div id="History">
	[<a id="refresh" href="javascript:refresh_history();">Refresh</a>]<br />
	<div id="content"></div>
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
