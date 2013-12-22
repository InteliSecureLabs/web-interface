<?php

require("networkmanager_vars.php"); 

if (isset($_POST['detect']))
{	
	if(shell_exec("wifi detect") != "")
	{
		exec("wifi detect >> /etc/config/wireless");
		exec("uci delete wireless.@wifi-iface[-1].network");
		
		echo '<font color="lime"><strong>done</strong></font>';
	}
	else
	{
		echo '<font color="orange"><strong>nothing detected</strong></font>';
	}
}

if (isset($_POST['action']) && isset($_POST['int']))
{
	if ($_POST['action'] == 'start') 
		exec("ifconfig ".$_POST['int']." up &");
	else
		exec("ifconfig ".$_POST['int']." down &");
}

if (isset($_POST['connect']) && isset($_POST['int']))
{
	$interface = $_POST['int'];
	exec("udhcpc -R -n -i ".$interface."");
	
	echo '<font color="lime"><strong>done</strong></font>';
}

if (isset($_POST['release']) && isset($_POST['int']))
{
	$interface = $_POST['int'];
	exec("kill `ps -ax | grep udhcp | grep ".$interface." | awk {'print $1'}`");
	
	echo '<font color="lime"><strong>done</strong></font>';
}

if (isset($_POST['remove']) && isset($_POST['phy']))
{
	$phy = $_POST['phy'];
	exec("uci delete wireless.radio".$phy);
	exec("uci delete wireless.@wifi-iface[".$phy."]");
	
	echo '<font color="lime"><strong>done</strong></font>';
}

if (isset($_POST['macchanger']) && isset($_POST['int']) && isset($_POST['phy']))
{
	$phy = $_POST['phy'];
	$interface = $_POST['int'];
		
	if(exec("ifconfig | grep ".$interface." | awk '{ print $1}'") != "")
	{
		exec("ifconfig ".$interface." down");
		exec("macchanger -r ".$interface);
		exec("ifconfig ".$interface." up");
	}
	else
	{
		print exec("macchanger -r ".$interface);
	}
	
	$mac_address = exec("ifconfig ".$interface." | grep HWaddr | awk '{ print $5}'");
	
	exec("uci set wireless.radio".$phy.".macaddr=".$mac_address);
	
	echo '<font color="lime"><strong>done</strong></font>';
}

if (isset($_POST['commit']))
{
	exec("uci commit wireless");
	exec("wifi");

	echo '<font color="lime"><strong>done</strong></font>';
}

if (isset($_POST['revert']))
{
	exec("uci revert wireless");
	exec("wifi");

	echo '<font color="lime"><strong>done</strong></font>';
}

if (isset($_POST['conf']))
{
	if($_POST['conf'] == "wireless")
	{		
		if ( isset( $_POST['parameters'] ) )
		{
			for($i=0;$i<count($interfaces);$i++)
			{		
				// Section - Wifi Network
				exec("uci set wireless.@wifi-iface[".$i."].ssid=\"".$_POST['parameters'][$i]['ssid']."\"");
				exec("uci set wireless.@wifi-iface[".$i."].mode=".$_POST['parameters'][$i]['mode']);
				exec("uci set wireless.@wifi-iface[".$i."].network=".$_POST['parameters'][$i]['network']);
				exec("uci set wireless.@wifi-iface[".$i."].hidden=".$_POST['parameters'][$i]['broadcast']);

				// Section - Wifi Device
				exec("uci set wireless.radio".$i.".channel=".$_POST['parameters'][$i]['channel']);
				
				exec("uci set wireless.radio".$i.".disabled=".(isset($_POST['parameters'][$i]['disabled']) ? 0 : 1));
				
				if($_POST['parameters'][$i]['security_mode'] == "psk" || $_POST['parameters'][$i]['security_mode'] == "psk2" || $_POST['parameters'][$i]['security_mode'] == "mixed-psk")
				{
					exec("uci set wireless.@wifi-iface[".$i."].key=".$_POST['parameters'][$i]['shared_key']);
					
					$encryption = $_POST['parameters'][$i]['security_mode']."+".$_POST['parameters'][$i]['encryption'];
					exec("uci set wireless.@wifi-iface[".$i."].encryption=".$encryption);
					
					// Delete unecessary value
					exec("uci delete wireless.@wifi-iface[".$i."].server");
					exec("uci delete wireless.@wifi-iface[".$i."].port");
					exec("uci delete wireless.@wifi-iface[".$i."].eap_type");
					exec("uci delete wireless.@wifi-iface[".$i."].identity");
					exec("uci delete wireless.@wifi-iface[".$i."].password");
				}
				else if($_POST['parameters'][$i]['security_mode'] == "wpa" || $_POST['parameters'][$i]['security_mode'] == "wpa2" || $_POST['parameters'][$i]['security_mode'] == "mixed-wpa")
				{					
					$encryption = $_POST['parameters'][$i]['security_mode']."+".$_POST['parameters'][$i]['encryption'];
					exec("uci set wireless.@wifi-iface[".$i."].encryption=".$encryption);
					
					if($_POST['parameters'][$i]['mode'] == "ap")
					{
						exec("uci set wireless.@wifi-iface[".$i."].server=".$_POST['parameters'][$i]['server']);
						exec("uci set wireless.@wifi-iface[".$i."].port=".$_POST['parameters'][$i]['port']);
						exec("uci set wireless.@wifi-iface[".$i."].key=".$_POST['parameters'][$i]['shared']);
						
						// Delete unecessary value
						exec("uci delete wireless.@wifi-iface[".$i."].eap_type");
						exec("uci delete wireless.@wifi-iface[".$i."].identity");
						exec("uci delete wireless.@wifi-iface[".$i."].password");
					}
					else if($_POST['parameters'][$i]['mode'] == "sta")
					{
						exec("uci set wireless.@wifi-iface[".$i."].eap_type=".$_POST['parameters'][$i]['eap_type']);
						exec("uci set wireless.@wifi-iface[".$i."].identity=".$_POST['parameters'][$i]['identity']);
						exec("uci set wireless.@wifi-iface[".$i."].password=".$_POST['parameters'][$i]['password']);
						
						// Delete unecessary value
						exec("uci delete wireless.@wifi-iface[".$i."].server");
						exec("uci delete wireless.@wifi-iface[".$i."].port");
						exec("uci delete wireless.@wifi-iface[".$i."].key");
					}
				}
				else if($_POST['parameters'][$i]['security_mode'] == "wep")
				{
					exec("uci set wireless.@wifi-iface[".$i."].key=".$_POST['parameters'][$i]['key']);
					
					$encryption = $_POST['parameters'][$i]['security_mode']."+".$_POST['parameters'][$i]['wep_mode'];
					
					exec("uci set wireless.@wifi-iface[".$i."].encryption=".$encryption);
				}
				else if($_POST['parameters'][$i]['security_mode'] == "none")
				{
					// Delete unecessary value
					exec("uci delete wireless.@wifi-iface[".$i."].encryption");
					exec("uci delete wireless.@wifi-iface[".$i."].key");
					exec("uci delete wireless.@wifi-iface[".$i."].server");
					exec("uci delete wireless.@wifi-iface[".$i."].port");
					exec("uci delete wireless.@wifi-iface[".$i."].eap_type");
					exec("uci delete wireless.@wifi-iface[".$i."].identity");
					exec("uci delete wireless.@wifi-iface[".$i."].password");
				}
			}
			echo '<font color="lime"><strong>saved</strong></font>';
		}
	}
	
	if($_POST['conf'] == "ics")
	{
		$interface_from = $_POST['interface_from'];
		$interface_to = $_POST['interface_to'];
		
		$filename = $module_path."networkmanager.conf";

		$newdata = $interface_from."\n".$interface_to;
		$newdata = ereg_replace(13,  "", $newdata);
		$fw = fopen($filename, 'w');
		$fb = fwrite($fw,stripslashes($newdata));
		fclose($fw);
		
		if($interface_from != "none" && $interface_to != "none")
		{
			exec("iptables -F");
			exec("echo 1 > /proc/sys/net/ipv4/ip_forward");
			
			exec("iptables -A FORWARD -i ".$interface_from." -o ".$interface_to." -s 172.16.42.0 -m state --state NEW -j ACCEPT");
			exec("iptables -A FORWARD -m state --state ESTABLISHED,RELATED -j ACCEPT");
			exec("iptables -t nat -A POSTROUTING -o ".$interface_from." -j MASQUERADE");
		
			exec("sed -i 's/FROMINTERFACE=\(.*\)/FROMINTERFACE=".$interface_from."/g' ".$module_path."autostart.sh");
			exec("sed -i 's/TOINTERFACE=\(.*\)/TOINTERFACE=".$interface_to."/g' ".$module_path."autostart.sh");
		
			if($_POST['enable_at_boot'])
			{
				if(!$enable_at_boot)
				{
					exec("sed -i '/exit 0/d' /etc/rc.local"); 
					exec("echo ".$module_path."autostart.sh >> /etc/rc.local");
					exec("echo exit 0 >> /etc/rc.local");
				}
			}
			else
			{
				exec("sed -i '/networkmanager\/autostart.sh/d' /etc/rc.local");
			}
		}
		else
		{
			exec("iptables -F");
			exec("sed -i '/networkmanager\/autostart.sh/d' /etc/rc.local");
		}
		
		echo '<font color="lime"><strong>saved</strong></font>';
	}
}

?>