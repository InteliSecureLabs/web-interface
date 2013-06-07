#!/bin/sh

randomrollDir="$( cd "$( dirname "$0" )" && pwd)"

MYPATH="$randomrollDir""/logs/RandomRoll-"
randomrollFile="null"

x=1
while :
do
	randomrollFile=${MYPATH}${x}.log
	if [ ! -f $randomrollFile ]; then
			break
	fi
	x=$(( $x + 1 ))
done

if [ -f "$randomrollDir"/logs/RandomRoll.log ]; then
	cp "$randomrollDir"/logs/RandomRoll.log ${randomrollFile}
	rm "$randomrollDir"/logs/RandomRoll.log
fi