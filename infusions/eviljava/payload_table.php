<style>
td {vertical-align: top;}
body {background-color:black;}
#payloads table{
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
#payloads a { color: black; color: white; text-decoration: none;}
#payloads td {padding: 4px; border: 1px #333 dotted; border-radius: 1px;}
#payloads tr:nth-child(1) {background-color:#111; color:white;}
</style>

<div style="display:none;" id="payloads" align="center">
<table>
<tr><td>Windows</td><td>Mac</td><td>Linux</td><td>Other</td></tr>
<tr><td><pre>
<?php
	
	// ========================================================
	// WINDOWS
	// ========================================================
	exec("ls -1 /www/java/Win", $output1);
	$tmp="";
	foreach ($output1 as $outputline)
	{
		if ($outputline == exec('cat /www/java/payloads |grep Win | cut -d "|" -f 2'))
		{
			$tmp.=  "<a style='color:lime; opacity: 0.3;'>&#10004;</a> <font style='color:#F87217;'>" . $outputline . "</font>\n";
		}
		else
		{
			$tmp.=  "<a href='del_payload.php?os=Win&payload=$outputline' style='color:red; opacity: 0.3;'>&#10008;</a> <a href='set_payload.php?os=Win&payload=$outputline'>" . $outputline . "</a>\n";
		}
	}
	if ($tmp=='') 
	{
		echo "[*] Empty ...";
	} 
	else 
	{
		echo $tmp;
	}
	echo "</pre></td><td><pre>";

	// ========================================================
	// MACINTOSH
	// ========================================================
	exec("ls -1 /www/java/Mac", $output2);
	$tmp="";
	foreach ($output2 as $outputline)
	{
		if ($outputline == exec('cat /www/java/payloads |grep Mac | cut -d "|" -f 2'))
			{
					$tmp.=  "<a style='color:lime; opacity: 0.3;'>&#10004;</a> <font style='color:#F87217;'>" . $outputline . "</font>\n";
			}
			else
			{
					$tmp.=  "<a href='del_payload.php?os=Mac&payload=$outputline' style='color:red; opacity: 0.3;'>&#10008;</a> <a href='set_payload.php?os=Mac&payload=$outputline'>" . $outputline . "</a>\n";
			}
	}
	
	if ($tmp=='') 
	{
		echo "[*] Empty ...";
	} 
	else 
	{
		echo $tmp;
	}
	echo "</pre></td><td><pre>";
	
	// ========================================================
	// Nix
	// ========================================================
	exec("ls -1 /www/java/Nix", $output3);
	$tmp="";
	foreach ($output3 as $outputline)
	{
		if ($outputline == exec('cat /www/java/payloads |grep Nix | cut -d "|" -f 2'))
		{
			$tmp.=  "<a style='color:lime; opacity: 0.3;'>&#10004;</a> <font style='color:#F87217;'>" . $outputline . "</font>\n";
		}
		else
		{
			$tmp.=  "<a href='del_payload.php?os=Nix&payload=$outputline' style='color:red; opacity: 0.3;'>&#10008;</a> <a href='set_payload.php?os=Nix&payload=$outputline'>" . $outputline . "</a>\n";
		}
	}
	
	if ($tmp=='') 
	{
		echo "[*] Empty ...";
	} 
	else 
	{
		echo $tmp;
	}
	echo "</pre></td><td><pre>";
	
	
	// ========================================================
	// Other
	// ========================================================
	exec("ls -1 /www/java/Other", $output4);
	$tmp="";
	
	foreach ($output4 as $outputline)
	{
		$tmp.= "<a href='del_payload.php?os=Other&payload=$outputline' style='color:red; opacity: 0.3;'>&#10008;</a> " . $outputline  . "\n" ;
	}
	
	if ($tmp=='') 
	{
		echo "[*] Empty ...";
	} 
	else 
	{
		echo $tmp;
	}
	echo "</pre></td></tr></table>";
?>
</div>
