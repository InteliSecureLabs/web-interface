#!/bin/sh

FROMINTERFACE=
TOINTERFACE=

echo 1 > /proc/sys/net/ipv4/ip_forward

udhcpc -R -n -i ${FROMINTERFACE}

iptables -A FORWARD -i ${FROMINTERFACE} -o ${TOINTERFACE} -s 172.16.42.0 -m state --state NEW -j ACCEPT
iptables -A FORWARD -m state --state ESTABLISHED,RELATED -j ACCEPT
iptables -t nat -A POSTROUTING -o ${FROMINTERFACE} -j MASQUERADE