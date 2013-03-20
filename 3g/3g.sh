#!/bin/sh
# ---------------------------------------------------------
# 3G Connection Script for WiFi Pineapple. "Does the thing"
# 
# Version: 2012-02-17
# Supports:
# 
# ZTE MF591 (T-Mobile) -dkitchen 
# Novatel MC760 (Virgin) -dkitchen
# Novatel MC760 (Ting) -dkitchen
# Sierra 598u (Ting) -brianzimm
# And more.
# 
# Updates: wifipineapple.com
# ---------------------------------------------------------


# -----------------------------------------------------------
# Configure /etc/ppp/options with hard-coded working settings
# -----------------------------------------------------------
echo "
logfile /dev/null
noaccomp
nopcomp
nocrtscts
lock
maxfail 0" > /etc/ppp/options

# --------------------------------------------------------------------------------------------------
# Check for known usb modem vendor and product IDs then switch 'em from storage to serial modem mode
# --------------------------------------------------------------------------------------------------
echo "Searching for attached 3G Modems"
logger "3G: Connection Script here, searching for modems"
MODEM=$(lsusb | awk '{ print $6 }')
echo $MODEM

case "$MODEM" in

*19d2:1523*)    echo "ZTE MF591 (T-Mobile) detected. Attempting mode switch"
                uci delete network.wan2         
                uci set network.wan2=interface  
                uci set network.wan2.ifname=ppp0           
                uci set network.wan2.proto=3g           
                uci set network.wan2.service=umts       
                uci set network.wan2.device=/dev/ttyUSB0     
                uci set network.wan2.apn=epc.tmobile.com     
                uci set network.wan2.username=internet       
                uci set network.wan2.password=internet
                uci set network.wan2.defaultroute=1    
                uci commit network 
		usb_modeswitch -v 19d2 -p 1523 -V 19d2 -P 1525 -M 5553424312345678000000000000061b000000020000000000000000000000 -n 1 -s 20
		sleep 10; rmmod usbserial
		sleep 3; insmod usbserial vendor=0x19d2 product=0x1525
		sleep 5; /etc/init.d/firewall disable; /etc/init.d/firewall stop
		logger "3G: firewall stopped"
		iptables -t nat -A POSTROUTING -s 172.16.42.0/24 -o 3g-wan2 -j MASQUERADE
		iptables -A FORWARD -s 172.16.42.0/24 -o 3g-wan2 -j ACCEPT 
		iptables -A FORWARD -d 172.16.42.0/24 -m state --state ESTABLISHED,RELATED -i 3g-wan2 -j ACCEPT
		
		;;
*1410:6002* | *1410:5031*) echo "Novatel MC760 (Virgin Mobile) detected. Attempting mode switch"
		uci delete network.wan2
		uci set network.wan2=interface
		uci set network.wan2.ifname=ppp0
		uci set network.wan2.proto=3g
		uci set network.wan2.service=cdma
		uci set network.wan2.device=/dev/ttyUSB0
		uci set network.wan2.username=internet
		uci set network.wan2.password=internet
		uci set network.wan2.defaultroute=1
		uci set network.wan2.ppp_redial=persist
		uci set network.wan2.peerdns=0
		uci set network.wan2.dns=8.8.8.8
		uci set network.wan2.keepalive=1
		uci set network.wan2.pppd_options=debug
		uci set network.wan2.pppd_options=noauth
		uci commit network
		usb_modeswitch -v 1410 -p 5031 -V 1410 -P 6002 -M 5553424312345678000000000000061b000000020000000000000000000000 -n 1 -s 20
		sleep 10; rmmod usbserial
		sleep 3; insmod usbserial vendor=0x1410 product=0x6002
		sleep 5; /etc/init.d/firewall disable; /etc/init.d/firewall stop
		logger "3G: firewall stopped"
		iptables -t nat -A POSTROUTING -s 172.16.42.0/24 -o 3g-wan2 -j MASQUERADE
		iptables -A FORWARD -s 172.16.42.0/24 -o 3g-wan2 -j ACCEPT 
		iptables -A FORWARD -d 172.16.42.0/24 -m state --state ESTABLISHED,RELATED -i 3g-wan2 -j ACCEPT

		;;
*1410:5030*)	echo "Novatel MC760 (Ting) detected. Attempting mode switch"
		uci delete network.wan2
		uci set network.wan2=interface
		uci set network.wan2.ifname=ppp0
		uci set network.wan2.proto=3g
		uci set network.wan2.service=cdma
		uci set network.wan2.device=/dev/ttyUSB0
		uci set network.wan2.username=internet
		uci set network.wan2.password=internet
		uci set network.wan2.defaultroute=1
		uci set network.wan2.ppp_redial=persist
		uci set network.wan2.peerdns=0
		uci set network.wan2.dns=8.8.8.8
		uci set network.wan2.keepalive=1
		uci set network.wan2.pppd_options=debug
		uci set network.wan2.pppd_options=noauth
		uci commit network
		usb_modeswitch -v 1410 -p 5030 -V 1410 -P 6000 -M 5553424312345678000000000000061b000000020000000000000000000000 -n 1 -s 20
		sleep 10; rmmod usbserial
		sleep 3; insmod usbserial vendor=0x1410 product=0x6000
		sleep 5; /etc/init.d/firewall disable; /etc/init.d/firewall stop
		logger "3G: firewall stopped"
		iptables -t nat -A POSTROUTING -s 172.16.42.0/24 -o 3g-wan2 -j MASQUERADE
		iptables -A FORWARD -s 172.16.42.0/24 -o 3g-wan2 -j ACCEPT 
		iptables -A FORWARD -d 172.16.42.0/24 -m state --state ESTABLISHED,RELATED -i 3g-wan2 -j ACCEPT

		;;
*1199:0025*)    echo "Sierra 598u (Ting) detected. Attempting mode switch"
                uci delete network.wan2
                uci set network.wan2=interface
                uci set network.wan2.ifname=ppp0
                uci set network.wan2.proto=3g
                uci set network.wan2.service=cdma
                uci set network.wan2.device=/dev/ttyUSB0
                uci set network.wan2.username=internet
                uci set network.wan2.password=internet
                uci set network.wan2.defaultroute=1
                uci set network.wan2.ppp_redial=persist
                uci set network.wan2.peerdns=0
                uci set network.wan2.dns=8.8.8.8
                uci set network.wan2.keepalive=1
                uci set network.wan2.pppd_options=debug
                uci set network.wan2.pppd_options=noauth
                uci commit network
                usb_modeswitch -v 1199 -p 0025
                sleep 10; rmmod usbserial
                sleep 3; insmod usbserial vendor=0x1199 product=0x0025
                sleep 5; /etc/init.d/firewall disable; /etc/init.d/firewall stop
                logger "3G: firewall stopped"
                iptables -t nat -A POSTROUTING -s 172.16.42.0/24 -o 3g-wan2 -j MASQUERADE
                iptables -A FORWARD -s 172.16.42.0/24 -o 3g-wan2 -j ACCEPT 
                iptables -A FORWARD -d 172.16.42.0/24 -m state --state ESTABLISHED,RELATED -i 3g-wan2 -j ACCEPT

                ;;
*12d1:1436*)    echo "Huawei E173 detected. Attempting mode switch"
                uci delete network.wan2
                uci set network.wan2=interface
                uci set network.wan2.ifname=ppp0
                uci set network.wan2.proto=3g
                uci set network.wan2.service=umts
                uci set network.wan2.device=/dev/ttyUSB0
                uci set network.wan2.apn=apn
                uci set network.wan2.username=username
                uci set network.wan2.password=password
                uci set network.wan2.defaultroute=1
                uci commit network
                usb_modeswitch -v 12d1 -p 1436
                sleep 10; rmmod usbserial
                sleep 3; insmod usbserial vendor=0x12d1 product=0x1436
                sleep 5; /etc/init.d/firewall disable; /etc/init.d/firewall stop
                logger "3G: firewall stopped"
                iptables -t nat -A POSTROUTING -s 172.16.42.0/24 -o 3g-wan2 -j MASQUERADE
                iptables -A FORWARD -s 172.16.42.0/24 -o 3g-wan2 -j ACCEPT
                iptables -A FORWARD -d 172.16.42.0/24 -m state --state ESTABLISHED,RELATED -i 3g-wan2 -j ACCEPT

                ;;
*12d1:140c*)    echo "Huawei Modem (3-IRL) detected. Attempting mode switch"
                uci delete network.wan2
                uci set network.wan2=interface
                uci set network.wan2.ifname=ppp0
                uci set network.wan2.proto=3g
                uci set network.wan2.service=umts
                uci set network.wan2.device=/dev/ttyUSB0
                uci set network.wan2.apn=3internet
                uci set network.wan2.username=
                uci set network.wan2.password=
                uci set network.wan2.defaultroute=1
                uci commit network
                usb_modeswitch -v 12d1 -p 140c -V 12d1 -P 140c -M 5553424312345678000000000000061b000000020000000000000000000000 -n 1 -s 20
                sleep 10; rmmod usbserial
                sleep 3; insmod usbserial vendor=0x12d1 product=0x140c
                sleep 5; /etc/init.d/firewall disable; /etc/init.d/firewall stop
                logger "3G: firewall stopped"
                iptables -t nat -A POSTROUTING -s 172.16.42.0/24 -o 3g-wan2 -j MASQUERADE
                iptables -A FORWARD -s 172.16.42.0/24 -o 3g-wan2 -j ACCEPT
                iptables -A FORWARD -d 172.16.42.0/24 -m state --state ESTABLISHED,RELATED -i 3g-wan2 -j ACCEPT

                ;;
esac
