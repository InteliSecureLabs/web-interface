#! /bin/bash

hostapd_cli -p /var/run/hostapd-phy0 karma_add_ssid "$1" > /tmp/commandOutput.txt
commandOutput=`cat /tmp/commandOutput.txt`

if `echo ${commandOutput} | grep "ADDED" 1>/dev/null 2>&1`
then
	sed -i '/"'"$1"'"/d' /tmp/ssid.lst
	echo \""$1"\" >> /tmp/ssid.lst
fi

rm /tmp/commandOutput.txt