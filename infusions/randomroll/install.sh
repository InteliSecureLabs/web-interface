#!/bin/sh

randomrollDir="$( cd "$( dirname "$0" )" && pwd)"

indexMD5=`md5sum /www/index.php`
var1=${indexMD5:0:32}
randomrollMD5=`md5sum "$randomrollDir"/files/index.php`
var2=${randomrollMD5:0:32}
if [ "$var1" != "$var2" ]; then
	if [ ! -f /www/index.php.bak ]; then
		cp /www/index.php /www/index.php.bak
	fi
	cp "$randomrollDir"/files/index.php /www/index.php
fi


spoofHostMD5=`md5sum /pineapple/config/spoofhost`
var1=${spoofHostMD5:0:32}
randomrollSpoofHostMD5=`md5sum "$randomrollDir"/files/spoofhost`
var2=${randomrollSpoofHostMD5:0:32}
if [ "$var1" != "$var2" ]; then
	if [ ! -f /pineapple/config/spoofhost.bak ]; then
		cp /pineapple/config/spoofhost /pineapple/config/spoofhost.bak
	fi
	spoofHostIP=`ifconfig br-lan | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'`
	echo '"'$spoofHostIP' *" > '$randomrollDir'/files/spoofhost'
	cp "$randomrollDir"/files/spoofhost /pineapple/config/spoofhost
fi


if [ ! -L /www/randomroll ]; then
	ln -s "$randomrollDir"/randomroll /www/randomroll
fi