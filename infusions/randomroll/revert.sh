#!/bin/sh

killall dnsspoof

if [ -f /www/index.php.bak ]; then
	cp /www/index.php.bak /www/index.php
	rm /www/index.php.bak
fi


if [ -f /opt/pwnpad/web-interface/config/spoofhost.bak ]; then
	cp /opt/pwnpad/web-interface/config/spoofhost.bak /pineapple/config/spoofhost
	rm /opt/pwnpad/web-interface/config/spoofhost.bak
fi


if [ -L /www/randomroll ]; then
	rm /www/randomroll
fi

sed -i '/randomroll\/autostart.sh/d' /etc/rc.local
