#!/bin/sh

killall dnsspoof

if [ -f /www/index.php.bak ]; then
	cp /www/index.php.bak /www/index.php
	rm /www/index.php.bak
fi


if [ -f /pineapple/config/spoofhost.bak ]; then
	cp /pineapple/config/spoofhost.bak /pineapple/config/spoofhost
	rm /pineapple/config/spoofhost.bak
fi


if [ -L /www/randomroll ]; then
	rm /www/randomroll
fi

sed -i '/randomroll\/autostart.sh/d' /etc/rc.local