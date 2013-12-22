<style>
td {vertical-align: top;}
body {background-color:black;}
#clients table{
font-family: monospace;
font-weight: bold;
margin:2px;
border-radius:5px;
background-color: #41A317;
background-color:black;
color:black;
color:white;
border:1px white dotted;
}
#clients a { color: black; color: white; text-decoration: none;}
#clients td {padding: 4px; border: 1px #333 dotted; border-radius: 1px;}
#clients tr:nth-child(1) {background-color:#111; color:white;}
</style>

<div id="clients">
<table>
<tr id="ist"><td>MAC</td><td>Host Name</td><td>User Agent</td><td>Platform</td><td>Java</td>
<?php
$command = "cat /pineapple/modules/get/get.database |grep . |sed 's/<tr>/<tr>\\n/g' | grep 'MAC\\|Host\\|User Agent\\|Platform\\|Java enabled'";
$command.= "|sed 's/<td><\\/td>/<td>[*] Unknown<\\/td>/' ";
$command.= "|sed 's/<td><!--end--><\\/td>/<td>[*] Unknown<\\/td>/'";
$command.= "|sed 's/<\\/table>.*//' |sed 's/<td>MAC/>><\\/table><table>\\n<td>MAC/' ";
//$command.= " |sed 's/<td>MAC/<\\/tr><tr><td>MAC/g' ";
/* table switch */ $command.= " |cut -d '>' -f 3-  ";
/* optional */ // $command.= "|sed 's/)/)<\\/td><\\/tr>)/g' | cut -d ')' -f -2 | sed 's/<td>/\\n<td>/g' |sed 's/(/(<td>(/g' |cut -d '(' -f 2- " ; 
$command.= "|sed ':a;N;$!ba;s/\\n/ /g' ";
/* table switch */ $command .= "  |sed 's/<\\/tr>//g' |sed 's/<tr>//g' | sed 's/table/tr/g' | sed 's/<tr>/\\n<tr>/'  |sed ':a;N;$!ba;s/\\n/ /g'";
/* remove duplicates */ $command .= " | sed 's/<tr>/\\n<tr>/g' |sed 's/<\\/td>$/<\\/td> <\\/tr>/g' |sort -u |sed ':a;N;$!ba;s/\\n/ /g'"; 
echo exec($command);
?></tr>
</table>
</div>
<br />

