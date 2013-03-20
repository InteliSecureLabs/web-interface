<?php

$strings = array(

#Author
	"author" => "",

#Navbar
	"navbar-status" => "Status",
	"navbar-configuration" => "Configuration",
	"navbar-advanced" => "Advanced",
	"navbar-usb" => "USB",
	"navbar-jobs" => "Jobs",
	"navbar-3g" => "3G",
	"navbar-ssh" => "SSH",
	"navbar-scripts" => "Scripts",
	"navbar-logs" => "Logs",
	"navbar-upgrade" => "Upgrade",
	"navbar-resources" => "Resources",
	"navbar-modules" => "Pineapple Bar",
	"navbar-about" => "About",


#Status
	"status-interfaces" => "Interfaces",
	"status-poe" => "POE / LAN Port:",
	"status-3g" => "USB 3G Modem:",
	"status-wan" => "WAN / LAN Port:",
	"status-public" => "Public Internet:",
	"status-reveal" => "reveal public ip",
	"status-connectionError" => "Error Connecting",

	"status-services" => "Services",
	"status-enabled" => "enabled",
	"status-disabled" => "disabled",
	"status-start" => "Start",
	"status-stop" => "Stop",
	"status-edit" => "Edit",
	"status-enable" => "Enable",
	"status-disable" => "Disable",
	"status-connect" => "Connect",
	"status-disconnect" => "Disconnect",
	"status-online" => "online",
	"status-offline" => "offline",

	"status-karma" => "Karma / Connection Status",
	"status-genReport" => "Generate Detailed Report",
	"status-report" => "Detailed Report",
	"status-dismiss" => "Dismiss",
	"status-reportWarning" => "CPU Intensive. Do not re-run reports in rapid succession",


#Configuration
	"config-updated" => "Updated",
	"config-message-persistent" => "Changes to SSID have been made persistently",
	"config-message-ssidChange" => "Karma SSID changed to",
	"config-message-added" => "was added to the list",
	"config-message-removed" => "was removed from the list",

	"config-karmaConfig" => "Karma Config",
	"config-karmaConfig-ssid" => "Change Karma SSID",
	"config-karmaConfig-ssid-persistent" => "Persistent?",
	"config-karmaConfig-ssid-button" => "Change SSID",
	"config-karmaConfig-ssid-bw" => "SSID Black and White listing",
	"config-karmaConfig-ssid-bw-change" => "change",
	"config-karmaConfig-ssid-bw-mode" => "Current Mode:",
	"config-karmaConfig-ssid-bw-add" => "Add to List",
	"config-karmaConfig-ssid-bw-remove" => "Remove from List",
	"config-karmaConfig-mac" => "Client Black Listing",
	
	"config-language-title" => "Change language",
	"config-language-current" => "The current language is",
	"config-language-how" => "To change your language, select one from the dropdown menu below",
	"config-language-button" => "Change Language",
	"config-language-message" => "Language changed to",
	"config-language-message2" => "Please wait, the page will reload.",
	"config-language-update" => "Update language pack",
	"config-language-error" => "Error connecting. Please check your internet connection.",
	"config-language-success" => "Language pack updated.",


	"config-button-title" => "Reset and WPS Button Config",
	"config-button-resetEnabled" => "enabled",
	"config-button-resetDisabled" => "disabled",
	"config-button-resetEnable" => "Enable",
	"config-button-resetDisable" => "Disable",
	"config-button-wpsButton" => "Update WPS Script",

	"config-dnsspoof-title" => "DNS Spoof Config",
	"config-dnsspoof-description" => "Specifies new destination IP for source Domain. May contain wildcards such as *.example.com.",
	"config-dnsspoof-button" => "Update Spoofhost",

	"config-landing-title" => "Landing Page (Phishing)",
	"config-landing-description" => "Root landing page for devices web server. Can be configured as captive portal or phishing page using Spoofhost. PHP allowed.",
	"config-landing-button" => "Update Landing Page",

	"config-css-title" => "CSS Editor",
	"config-css-button" => "Update CSS",


#Advanced

	"advanced-cacheCleared" => "Cache Cleared",
	"advanced-resetComplete" => "The pineapple is being reset. It will reboot when done.",

        "advanced-clearCache" => "Clear Pineapple Cache",
        "advanced-factoryReset" => "Factory Reset",
        "advanced-reboot" => "Reboot",

	"advanced-executeCommands" => "Execute Commands",
	"advanced-execute" => "Execute Commands",
	"advanced-executing" => "Execute",

	"advanced-routing-title" => "Routing Table",
	"advanced-routing-button" => "Update Routing Table",
	"advanced-routing-example" => "Example:",
	"advanced-routing-execute" => "Executing",

	"advanced-network-title" => "Network Tools",
	"advanced-ping" => "Ping",
	"advanced-traceroute" => "Traceroute",

	"advanced-password-title" => "Change Root Password",
	"advanced-password" => "Password:",
	"advanced-password-repeat" => "Repeat Password:",
	"advanced-password-button" => "Change Password",
	"advanced-password-success" => "Password changed successfully",
	"advanced-password-note" => "Note: You need to reboot the pineapple for the changes to take effect on the UI",
	"advanced-password-unsuccessful" => "Password change unsuccessful. Make sure the passwords match!",


#USB

	"usb-fileUpdate" => "Updated",
	"usb-lsusb" => "lsusb Output",
	"usb-fstab" => "fstab Configuration",
	"usb-fstab-button" => "Update fstab",


#Jobs

	"jobs-fileUpdated" => "Updated",
	"jobs-title" => "Cron Options",
	"jobs-cronjobs" => "Cron Jobs are currently",
	"jobs-cronjobs-enabled" => "enabled",
	"jobs-cronjobs-disabled" => "disabled",
	"jobs-cronjobs-enable" => "Enable",
	"jobs-cronjobs-disable" => "Disable",

	"jobs-cronjobs-title" => "Cron Jobs",
	"jobs-cronjobs-button" => "Update Crontab",

	"jobs-help-title" => "Help",
	"jobs-help" => "Cronjob Configuration.

	* * * * * command to be executed
	- - - - -
	| | | | |
	| | | | +- - - - day of week (0 - 6) (Sunday=0)
	| | | +- - - - - month (1 - 12)
	| | +- - - - - - day of month (1 - 31)
	| +- - - - - - - hour (0 - 23)
	+- - - - - - - - minute (0 - 59)
	
	Examples:
	
	Run myscript.sh at 2:30 AM every day
	30 2 * * * /root/myscript.sh
	
	Run myscript.sh every 15 minutes
	*/15 * * * * /root/myscript.sh",

#3G
	"3g-fileUpdate" => "Updated",

	"3g-force-message" => "Attempting 3G connection. This may take a minute and require manual refresh of this page. Check",
	"3g-force-logs" => "Logs",
	"3g-force-details" => "for details.",

	"3g-keepAlive-message" => "3G Keep Alive script added to Cron Jobs. Be sure to enable Cron Daemon from",
	"3g-keepAlive-jobs" => "Jobs",

	"3g-boot" => "3G on boot is currently",
	"3g-keepAlive" => "Keep Alive is currently",
	"3g-enabled" => "enabled",	
	"3g-disabled" => "disabled",
	"3g-enable" => "Enable",
	"3g-disable" => "Disable",
	"3g-force" => "Force",
	"3g-force-warning" => "connection now. This executes the below 3G script now, potentially saving a reboot.",
	"3g-force-experimental" => "Experimental",

	"3g-usbConnections" => "USB Connections",
	
	"3g-config-title" => "Mobile Broadband Configuration",
	"3g-config-button" => "Update 3G Script",

	"3g-interfaces" => "Interfaces",
	
	"3g-help-title" => "Help",
	"3g-help-content" => "If enabled this script executes on boot. It can also be forced.
Mobile Broadband requires a compatible USB 3G / 4G modem.

Connection is two phase. First the modem must be actived, then the network configuration sets paramaters used by pppd and gchat.
Since most 3G / 4G modems identify as CD-ROM or USB Storage devices an activation script, typically using usb_modeswitch or sdparm, is executed.
Activation forces the USB device to reveal its modem component.

The modem component is configured as a USB Serial device, typically /dev/ttyUSB0, which is addressed by the network configuration.
Network Configuration specifies the interface as WAN2. GSM and CDMA protocols are supported. ifconfig typically shows the interface as 3g-wan2.
The pppd is responsible for making the point-to-point connection with the USB Serial device. Configuration in /etc/ppp/options
Comgt is responsible for talking to the modem. EVDO and 3G (GSM) modem commands are specified in /etc/chatscripts/
For the most part neither of these files need modification.

Support outside of the listed supported modems is experimental, though help can be found on the Jasager forums. Most USB modems share similar 
configuration.

Updated 3G connection scripts with additional modem support can be found at wifipineapple.com
Additionally a 3G-KeepAlive script is available, which periodically checks for Internet connectivity and re-establishes if necessary.
This is done by attempting to send three pings to 8.8.8.8. If none are successful \"ifup wan\" is executed.",


#SSH

        "ssh-fileUpdate" => "Updated",
	"ssh-deleteKey-message" => "SSH Key Pair Removed from",
	"ssh-generateKey-message" => "SSH Key Pair Generated and stored in",
	"ssh-keepAlive-enabled" => "SSH Keep Alive script added to Cron Jobs. Be sure to enable the Cron Daemon from",
	"ssh-jobs" => "Jobs",

	"ssh-options" => "SSH Options",
	"ssh-boot" => "SSH on boot is currently",
	"ssh-persist" => "SSH Persist is currently",
	"ssh-session" => "SSH session currently",
	"ssh-enable" => "Enable",
	"ssh-disable" => "Disable",
	"ssh-enabled" => "enabled",
	"ssh-disabled" => "disabled",
	"ssh-connected" => "connected",
	"ssh-disconnected" => "disconnected",
	"ssh-connect" => "Connect",
	"ssh-disconnect" => "Disconnect",

	"ssh-connectCommand" => "SSH Connection Command",
	"ssh-connectCommand-button" => "Save",

	"ssh-publicKey" => "Public Key",
	"ssh-publicKey-note" => "This usually goes in %h/.ssh/authorized_keys on the remote host",
	"ssh-publicKey-noKey" => "No key?",
	"ssh-publicKey-generate" => "Generate",
	"ssh-publicKey-delete" => "Delete",
	"ssh-publicKey-delete-note" => "existing RSA SSH key pair",
	"ssh-knownHosts" => "Known Hosts",
	"ssh-knownHosts-button" => "Update Known Hosts",

	"ssh-help" => "Help",
	"ssh-help-content" => "
<b>On the local host (this pineapple)</b>
 - Generate an RSA key pair. The private key will be stored in /etc/dropbear/id_rsa
 - Note the RSA public key presented above. You'll need the from \"ssh-rsa\" to \"root@Pineapple\"
 - Add the Clients ssh-rsa public key (not the public key above) to ~/.ssh/known_hosts
   - This is most easily accomplished by issuing 'ssh user@host' and pressing 'y' when prompted to save the key
   - This must be done interactively (via a shell on this device) as AutoSSH does not pass the '-y' option.

<b>On the remote host (the server)</b>
 - Append the above noted RSA public key to the authorized_keys file. This is typically located in ~/.ssh/
 - The following are helpful opensshd configuration options. The conf file is typically /etc/ssh/sshd_config
        AllowTcpForwarding   yes
        GatewayPorts         yes
        RSAAuthentication    yes
        PubkeyAuthentication yes

<b>Example Usage</b>

<b>Simple Relay Server</b>
With the above key exchange and SSH configuration complete create an SSH session through a relay server
 - Pineapple's SSH command: autossh -M 20000 -f -N -R 4255:localhost:22 user@relayserver -i /etc/dropbear/id_rsa
 - 3rd party SSH command: ssh pineappleuser@relayserver -p 4255
   - The pineapple user is typically root
   - If the relay server does not support remote port forwarding first SSH to the relay server as usual then:
     ssh pineappleuser@localhost -p 4255
",
	

#Scripts

        "scripts-fileUpdate" => "Updated",

	"scripts-boot-title" => "Execute On Boot",
	"scripts-boot-button" => "Update rc.local",

	"scripts-cleanup-title" => "Clean-Up Script",
	"scripts-cleanup-button" => "Clean-Up Script",

	"scripts-ssh-title" => "SSH Keep Alive Script",
	"scripts-ssh-button" => "Update SSH Keep Alive Script",

	"scripts-3g-title" => "3G Keep Alive Script",
	"scripts-3g-button" => "Update 3G Keep Alive Script",

	"scripts-user-title" => "User Script",
	"scripts-user-button" => "Update User Script",



#Logs
	"logs-refresh" => "refresh",
	"logs-dnsspoof-title" => "DNS Spoof Log",
	"logs-urlsnarf-title" => "URLsnarf Log",	
	"logs-syslog-title" => "System Log",


#Upgrade

	"upgrade-connectError" => "Error connecting. Please make sure you have a working connection!",
	"upgrade-found" => "Upgrade found",
	"upgrade-doUpgrade" => "Perform Upgrade",
	"upgrade-notFound" => "No upgrade found.",	
	"upgrade-complete" => "Upgrade complete!",

	"upgrade-fileError" => "Error, please check the file you specified.",
	"upgrade-nameError" => "The upgrade file must be named upgrade.bin",
	"upgrade-md5Error" => "Error, MD5Sum does not match!",
	
	"upgrade-working" => "Upgrade in progress!<br />Please wait, do not leave or refresh this page.<br />The pineapple will reboot once the upgrade is complete and this page will refresh automatically!",

	"upgrade-check-title" => "Check for Upgrades",
	"upgrade-check-link" => "Check for Upgrades",
	"upgrade-firmware" => "The current firmware version is",
	"upgrade-reflash" => "Re-Flash",
	
	"upgrade-doUpgrade-title" => "Perform Upgrade",
	"upgrade-doUpgrade-confirm" => "Are you sure you want to upgrade? Make sure you have read the warning below. Remember that you will loose any changes you made.",
	"upgrade-doUpgrade-button" => "Upgrade",

	"upgrade-warning-title" => "Warning",
	"upgrade-warning" => "Power cycle the WiFi Pineapple and disable Karma, SSH, 3G and other services.
Under most circumstances a firmware flash is perfectly safe, however:
 - Bootloader recovery options can only be accessed via serial.
 - Do not flash firmware while running on battery power.
 - Do not flash firmware if memory is low.
 - Do not flash firmware via WiFi.
",

	"upgrade-memory" => "Memory",

	"upgrade-ota-md5" => "The download failed, please try again!<br /><br />",	
	"upgrade-ota-downloaded" => "Firmware downloaded.<br /><br />",
	"upgrade-ota-downloading" => "Downloading..<br />Do not leave or refresh this page. It will refresh and proceed with the upgrade automatically.",
	"upgrade-ota-connectionError" => "Error connecting. Please check your connection.<br /><br />",


#Resources
	
	"resources-memory" => "Free Memory",
	"resources-memory-experimental" => "Experimental",
	"resources-dropCachesExecuted" => "Executed drop_caches command",

	"resources-diskUsage" => "Disk Usage",

	"resources-lsusb" => "lsusb Output",

	"resources-processes" => "Processes",


#Pineapple Bar

	"modules-sizeWarning" => "Warning, you either have under 70kb of free space left or the infusion requires more space than you have available.<br />Installing infusions to main memory is not permitted.",
	"modules-usbInstall" => "Install to USB",

	"modules-install-title" => "Infusion Installation",
	"modules-install-notification" => "You are about to install the infusion",
	"modules-install-destQuestion" => "Where would you like to install the infusion to?",
	"modules-install-internal" => "Internal Storage",
	"modules-install-external" => "USB Storage",
	"modules-install-md5error" => "MD5 error. Please try re-installing the infusion",
		
	"modules-js-pleaseWait" => "Please wait while you are being redirected",
	"modules-js-installed" => "Infusion installed successfully",
	"modules-js-removed" => "Infusion removed successfully",
	"modules-js-updated" => "Infusion updated successfully",
	"modules-js-updateAlert" => "One of the infusions is out of date.\\nPlease check below for an update!",
	
	"modules-installed-title" => "Installed Infusions",
	"modules-installed-noModules" => "There are no infusions installed.",
	
	"modules-table-name" => "Name",
	"modules-table-version" => "Version",
	"modules-table-author" => "Author",
	"modules-table-description" => "Description",
	"modules-table-size" => "Size",
	"modules-table-location" => "Location",
	"modules-table-action" => "Action",
	
	"modules-links-remove" => "Remove",
	"modules-links-supportLink" => "Support Link",
	"modules-links-unpin" => "Unpin from navbar",
	"modules-links-pin" => "Pin to navbar",
	"modules-links-launch" => "Launch",
	"modules-links-update" => "Update",
	"modules-links-install" => "Install",

	"modules-available-title" => "Available Infusions",
	"modules-available-list" => "List available Infusions (aka modules)",
	"modules-available-warning" => "Warning: This will establish a connection to cloud.wifipineapple.com.",
	"modules-available-error" => "Error connecting!<br />Please make sure you have an internet connection.",
	


#About
#This will stay in english for now
#as it will be re-designed soon.


#Version 2.7.0
        "logs-phishingLog" => "Phishing log",

        "config-UIconfig-title" => "Pineapple UI Configuration",
        "config-UIconfig-current" => "The Pineapple UI currently runs on port",
        "config-UIconfig-changeText" => "If you wish to change it, do so below.",
        "config-UIconfig-enterPort" => "Enter a new port:",
        "config-UIconfig-buttonTitle" => "Change",
        "config-UIconfig-error" => "There was an error.<br />Please make sure that you have entered a port number above 1024.",
        "config-UIconfig-message1" => "Restarting Web Server. Please wait.",
        "config-UIconfig-message2" => "The webserver should have finished loading<br/>To load the new UI, click",
        "config-UIconfig-linkMessage" => "here",


);

?>
