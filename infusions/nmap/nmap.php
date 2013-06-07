<?php

require("nmap_vars.php");

?>
<html>
<head>
<title>Pineapple Control Center - <?php echo $module_name." [v".$module_version."]"; ?></title>
<script type="text/javascript" src="/includes/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>

<script type="text/javascript" src="js/nmap.js"></script>
<link rel="stylesheet" type="text/css" href="css/nmap.css" />
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
if($is_nmap_installed)
{
	echo "nmap";
	echo "&nbsp;<font color=\"lime\"><strong>installed</strong></font><br />";
}
else
{
	echo "nmap";
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
		<li><a href="#Scan">Scan</a></li>
		<li><a href="#Ping">Ping</a></li>
		<li><a href="#Target">Target</a></li>
		<li><a href="#Other">Other</a></li>
	</ul>
	
<div id="General">
	<table class="grid" cellspacing="0">
		<tr>
			<td>Target: </td>
			<td><input type="text" id="target" name="target" value="" size="70"></td>
			
			<td>Profile: </td>
			<td><select id="profile" name="profile">
			<option>--</option>
			<?php
				foreach($profiles as $key => $value)
				{
					echo '<option value="'.$value.'">'.$key.'</option>';
				}
			?>
			</select></td>
		</tr>		
	</table>
</div>

<div id="Scan">
	<table class="grid" cellspacing="0">
		<tr>
			<td>Timing: </td>
			<td><select id="timing" name="timing">
			<option>--</option>
			<?php
				foreach($timmings as $key => $value)
				{
					echo '<option value="'.$value.'">'.$key.'</option>';
				}
			?>
			</select></td>
		</tr>
		<tr>
			<td>TCP scan: </td>
			<td><select id="tcp" name="tcp">
			<option>--</option>
			<?php
				foreach($tcp_scans as $key => $value)
				{
					echo '<option value="'.$value.'">'.$key.'</option>';
				}
			?>
			</select></td>
		</tr>
		<tr>
			<td>Non-TCP scan: </td>
			<td><select id="nontcp" name="nontcp">
			<option>--</option>
			<?php
				foreach($non_tcp_scans as $key => $value)
				{
					echo '<option value="'.$value.'">'.$key.'</option>';
				}
			?>
			</select></td>
		</tr>
		<tr>
			<td colspan="2">
			<?php
				foreach($scan_options as $key => $value)
				{
					echo '<input type="checkbox" name="'.$key.'" value="'.$value.'" />'.$key."<br />";
				}
			?>
			</td>
		</tr>
	</table>
</div>

<div id="Ping">
	<table class="grid" cellspacing="0">
		<tr>
			<td>
			<?php
				foreach($ping_options as $key => $value)
				{
					echo '<input type="checkbox" name="'.$key.'" value="'.$value.'" />'.$key."<br />";
				}
			?>
			</td>
		</tr>
	</table>
</div>

<div id="Target">
	<table class="grid" cellspacing="0">
		<tr>
			<td>
			<?php
				foreach($target_options as $key => $value)
				{
					echo '<input type="checkbox" name="'.$key.'" value="'.$value.'" />'.$key."<br />";
				}
			?>
			</td>
		</tr>
	</table>
</div>

<div id="Other">
	<table class="grid" cellspacing="0">
		<tr>
			<td>
			<?php
				foreach($other_options as $key => $value)
				{
					echo '<input type="checkbox" name="'.$key.'" value="'.$value.'" />'.$key."<br />";
				}
			?>
			</td>
		</tr>
	</table>
</div>

<div style="border-top: 1px solid black;">
Command: <input type="text" id="command" name="command" value="nmap " size="115"><br /><br />
<span id="control">
	<?php
	if($is_nmap_running)
	{
		echo '<a id="scan" href="javascript:scan_toggle(\'cancel\');"><font color="red"><strong>Cancel</strong></font></a>';
	}
	else
	{
		echo '<a id="scan" href="javascript:scan_toggle(\'scan\');"><font color="lime"><strong>Scan</strong></font></a>';
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
	<textarea id='output' name='output' cols='85' rows='29'></textarea>
</div>

<div id="History">
	[<a id="refresh" href="javascript:refresh_history();">Refresh</a>]<br />
	<div id="content"></div>
</div>

</div>

</body>
</html>
