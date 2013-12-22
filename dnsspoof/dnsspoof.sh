#!/bin/sh
dnsspoof -i br-lan -f ../config/spoofhost > /dev/null 2>../logs/dnsspoof.log
