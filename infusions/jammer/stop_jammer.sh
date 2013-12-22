#!/bin/sh

MYPATH="$(dirname $0)/"
SCANTMP=${MYPATH}scan
APLIST=${MYPATH}ap
LOG=${MYPATH}log
WHITELIST=${MYPATH}rules/whitelist.lst
TMPWHITELIST=${MYPATH}rules/whitelist.tmp
BLACKLIST=${MYPATH}rules/blacklist.lst
TMPBLACKLIST=${MYPATH}rules/blacklist.tmp

echo -e "Stopping WiFi Jammer..." >> ${LOG}

killall -9 start_jammer.sh
killall -9 aireplay-ng
rm ${MYPATH}stationlist/*.txt
rm ${SCANTMP}
rm ${APLIST}
rm ${TMPWHITELIST}
rm ${TMPBLACKLIST}
rm ${LOG}
