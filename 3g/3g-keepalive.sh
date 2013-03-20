#!/bin/sh
# --------------------------------------------------------------
# Check if 3G / WAN connection is online and bring it up if not.
# --------------------------------------------------------------

SERVER="8.8.8.8" # This is Google's DNS server - if it's down we've got bigger problems
logger "3G: Keep-Alive Script Executed"

if ! ( ifconfig 3g-wan2); then
	logger "3G: Interface 3g-wan2 seems down. Attempting 3g connect script again"
	/pineapple/3g/3g.sh
else
	logger "3G: Interface 3g-wan2 seems up"

	if ! ( ping -q -c 1 -W 10 $SERVER > /dev/null || ping -q -c 1 -W 10 $SERVER > /dev/null || ping -q -c 1 -W 10 $SERVER > /dev/null ); then
		logger "3G: Interface 3g-wan2 up however Internet connection seemed to have been down. Hello, IT. Is the modem activated? Have you tried turning it off and on again?"
		logger "3G: Attempting ifup wan2. Hopefully that solves the problem."
		ifup wan2
		
	else
		logger "3G: Interface 3g-wan2 up and Internet Connection seems to be up. woot"
	fi
fi
