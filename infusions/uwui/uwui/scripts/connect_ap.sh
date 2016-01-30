ifconfig $1 down &>/dev/null
iwconfig $1 essid $2 key s:$3 &>/dev/null
ifconfig $1 up &>/dev/null
dhclient $1 2>../data/dhclient.log >/dev/null
