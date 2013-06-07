#!/bin/sh

MYPATH="$(dirname $0)/"
MYTIME=`date +%s`

dnsspoof -i br-lan -f /pineapple/config/spoofhost > /dev/null 2> ${MYPATH}log/output_${MYTIME}.log &