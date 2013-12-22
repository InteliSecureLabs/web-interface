#!/bin/sh

# Custom script
cat /tmp/dhcp.leases; echo -e '\n'; cat /proc/net/arp; echo -e '\n'; grep KARMA /tmp/karma.log | grep -v -e enabled | grep -v -e malloc | grep -v -e CTRL_IFACE | grep -v -e KARMA_STATE | grep -v -e Request | grep -v -e KARMA_ | uniq | sed '1!G;h;$!d'