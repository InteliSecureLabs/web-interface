#!/bin/sh

MYPATH="$(dirname $0)/"
MYTIME=`date +%s`

urlsnarf -i br-lan > ${MYPATH}log/output_${MYTIME}.log &