#! /bin/bash

hostapd_cli -p /var/run/hostapd-phy0 karma_del_ssid "$1" > /tmp/commandOutput.txt
commandOutput=`cat /tmp/commandOutput.txt`

if `echo ${commandOutput} | grep "DELETED" 1>/dev/null 2>&1`
then
	sed -i '/"'"$1"'"/d' /tmp/ssid.lst
fi

hostapd_cli -p /var/run/hostapd-phy0 karma_del_ssid "$1" > /tmp/commandOutput.txt

rm /tmp/commandOutput.txt