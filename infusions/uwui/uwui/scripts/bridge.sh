ifconfig $2 0.0.0.0
ifconfig $2 up

ifconfig $1 0.0.0.0
ifconfig $1 up

brctl addbr br0
brctl addif br0 $2
brctl addif br0 $1 

ifconfig br0 192.168.1.99 netmask 255.255.255.0
ifconfig br0 up
route add default gw 192.168.1.1
