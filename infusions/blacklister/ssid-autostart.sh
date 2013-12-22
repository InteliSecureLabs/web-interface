#! /bin/bash

if [ ! -f /tmp/ssid.lst ]; then
    touch /tmp/ssid.lst
fi


if [ -f /etc/config/ssid.list ]; then
	ssidList="/etc/config/ssid.list"
else
	ssidList="$( cd "$( dirname "$0" )" && pwd)/ssid.list"
fi


if [ $1 = 'BLACK' ]; then
	hostapd_cli -p /var/run/hostapd-phy0 karma_black > /dev/null
elif [ $1 = 'WHITE' ]; then
	hostapd_cli -p /var/run/hostapd-phy0 karma_white > /dev/null
fi


while IFS= read -r line <&3; do
if [ "$line" != "" ]; then
	hostapd_cli -p /var/run/hostapd-phy0 karma_add_ssid "$line" > /tmp/commandOutput.txt
	commandOutput=`cat /tmp/commandOutput.txt`
	if `echo ${commandOutput} | grep "ADDED" 1>/dev/null 2>&1`
	then
		sed -i '/"'"$line"'"/d' /tmp/ssid.lst
		echo \""$line"\" >> /tmp/ssid.lst
	fi
fi
done 3< $ssidList


if [ "$line" != "" ]; then
	hostapd_cli -p /var/run/hostapd-phy0 karma_add_ssid "$line" > /tmp/commandOutput.txt
	commandOutput=`cat /tmp/commandOutput.txt`
	if `echo ${commandOutput} | grep "ADDED" 1>/dev/null 2>&1`
	then
		sed -i '/"'"$line"'"/d' /tmp/ssid.lst
		echo \""$line"\" >> /tmp/ssid.lst
	fi
fi

rm /tmp/commandOutput.txt