export PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usb/usr/bin:/usb/usr/sbin
export LD_LIBRARY_PATH=/lib:/usr/lib:/usb/lib:/usb/usr/lib

nmap -e $1 $2/24
