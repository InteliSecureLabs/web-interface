#!/bin/sh
while true
do cat /opt/pwnpad/web-interface/logs/urlsnarf.log | awk {'print $1 $8'} | sed 's,http://, ,' | sed 's/.lan//' | sed 's%/.*$%%' | uniq > /opt/pwnpad/web-interface/logs/urlsnarf-clean.log
sleep 10
done
