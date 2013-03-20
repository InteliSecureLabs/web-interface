#!/bin/sh
while true
do cat /pineapple/logs/urlsnarf.log | awk {'print $1 $8'} | sed 's,http://, ,' | sed 's/.lan//' | sed 's%/.*$%%' | uniq > /pineapple/logs/urlsnarf-clean.log
sleep 10
done
