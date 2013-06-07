<?php

require("tcpdump_vars.php");

?>
<html>
<head>
<title>Pineapple Control Center - <?php echo $module_name." [v".$module_version."]"; ?></title>
<script type="text/javascript" src="/includes/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>

<script type="text/javascript" src="js/tcpdump.js"></script>
<link rel="stylesheet" type="text/css" href="css/tcpdump.css" />
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
if($is_tcpdump_installed)
{
	echo "tcpdump";
	echo "&nbsp;<font color=\"lime\"><strong>installed</strong></font><br />";
}
else
{
	echo "tcpdump";
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
		<li><a href="#Options">Options</a></li>
	</ul>
	
<div id="General">
	<table class="grid" cellspacing="0">
		<tr>
			<td>Filter: </td>
			<td><input type="text" id="filter" name="filter" value="" size="70"></td>
			
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
			<td>Type: </td><td><a href="javascript:append_filter('host');">host</a> <a href="javascript:append_filter('net');">net</a> <a href="javascript:append_filter('portrange');">portrange</a> <a href="javascript:append_filter('port');">port</a> <a href="javascript:append_filter('gateway');">gateway</a> <a href="javascript:append_filter('mask');">mask</a><td/>
		</tr>
		<tr>
			<td>Dir: </td><td><a href="javascript:append_filter('src');">src</a> <a href="javascript:append_filter('dst');">dst</a> <a href="javascript:append_filter('src or dst');">src or dst</a> <a href="javascript:append_filter('src and dst');">src and dst</a><td/>
		</tr>
		<tr>			
			<td>Proto: </td><td><a href="javascript:append_filter('ip');">ip</a> <a href="javascript:append_filter('proto');">proto</a> <a href="javascript:append_filter('icmp');">icmp</a> <a href="javascript:append_filter('tcp');">tcp</a> <a href="javascript:append_filter('udp');">udp</a> <a href="javascript:append_filter('arp');">arp</a> <a href="javascript:append_filter('ether');">ether</a> <a href="javascript:append_filter('http');">http</a> <a href="javascript:append_filter('ftp');">ftp</a> <a href="javascript:append_filter('smtp');">smtp</a><td/>
		</tr>
		<tr>
			<td>Length: </td><td><a href="javascript:append_filter('less');">less</a> <a href="javascript:append_filter('greater');">greater</a><td/>
		</tr>
		<tr>
			<td>Kind: </td><td><a href="javascript:append_filter('broadcast');">broadcast</a> <a href="javascript:append_filter('multicast');">multicast</a><td/>
		</tr>
		<tr>
			<td>Operator: </td><td><a href="javascript:append_filter('not');">not</a> <a href="javascript:append_filter('and');">and</a> <a href="javascript:append_filter('or');">or</a> <a href="javascript:append_filter('\(');">(</a> <a href="javascript:append_filter('\)');">)</a></td>
		</tr>	
	</table>
</div>

<div id="Options">
	<table class="grid" cellspacing="0">
		<tr>
			<td>Verbose: </td>
			<td><select id="verbose" name="verbose">
			<option>--</option>
			<?php
				foreach($verbose as $key => $value)
				{
					echo '<option value="'.$value.'">'.$key.'</option>';
				}
			?>
			</select></td>
		</tr>
		<tr>
			<td>Resolve: </td>
			<td><select id="resolve" name="resolve">
			<option>--</option>
			<?php
				foreach($resolve as $key => $value)
				{
					echo '<option value="'.$value.'">'.$key.'</option>';
				}
			?>
			</select></td>
		</tr>
		<tr>
			<td>Timestamp: </td>
			<td><select id="timestamp" name="timestamp">
			<option>--</option>
			<?php
				foreach($timestamp as $key => $value)
				{
					echo '<option value="'.$value.'">'.$key.'</option>';
				}
			?>
			</select></td>
		</tr>
		<tr>
			<td colspan="2">
			<?php
				foreach($options as $key => $value)
				{
					echo '<input type="checkbox" name="'.$key.'" value="'.$value.'" />'.$key."<br />";
				}
			?>
			</td>
		</tr>
	</table>
</div>

<div style="border-top: 1px solid black;">
Command: <input type="text" id="command" name="command" value="tcpdump " size="115"><br /><br />
<span id="control">
	<?php
	if($is_tcpdump_running)
	{
		echo '<a id="scan" href="javascript:dump_toggle(\'stop\');"><font color="red"><strong>Stop</strong></font></a>';
	}
	else
	{
		echo '<a id="scan" href="javascript:dump_toggle(\'capture\');"><font color="lime"><strong>Capture</strong></font></a>';
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
