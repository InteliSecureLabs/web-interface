#! /bin/bash

if [ ! -f /tmp/mac.wht ]; then
    touch /tmp/mac.wht
fi
if [ ! -f /tmp/mac.blk ]; then
    touch /tmp/mac.blk
fi


if [ -f /etc/config/mac.list ]; then
	macList="/etc/config/mac.list"
else
	macList="$( cd "$( dirname "$0" )" && pwd)/mac.list"
fi


while IFS= read -r line <&3; do
if [ "$line" != "" ]; then
	hostapd_cli -p /var/run/hostapd-phy0 karma_add_black_mac ${line:0:17} > /tmp/commandOutput.txt
	commandOutput=`cat /tmp/commandOutput.txt`
	if `echo ${commandOutput} | grep "ADDED" 1>/dev/null 2>&1`
	then
		sed -i '/'${line:0:17}'/d' /tmp/mac.blk
		hostapd_cli -p /var/run/hostapd-phy0 disassociate ${line:0:17} > /dev/null
		echo ${line:0:17} >> /tmp/mac.blk
	fi
fi
done 3< $macList

if [ "$line" != "" ]; then
	hostapd_cli -p /var/run/hostapd-phy0 karma_add_black_mac ${line:0:17} > /tmp/commandOutput.txt
	commandOutput=`cat /tmp/commandOutput.txt`
	if `echo ${commandOutput} | grep "ADDED" 1>/dev/null 2>&1`
	then
		sed -i '/'${line:0:17}'/d' /tmp/mac.blk
		hostapd_cli -p /var/run/hostapd-phy0 disassociate ${line:0:17} > /dev/null
		echo ${line:0:17} >> /tmp/mac.blk
	fi
fi

rm /tmp/commandOutput.txt