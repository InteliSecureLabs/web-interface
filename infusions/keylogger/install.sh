#!/bin/sh

USBPATH="/usb/"
MODULEPATH="$(dirname $0)/"

# ebtables
opkg install ${MODULEPATH}dep/kmod-ebtables.ipk
opkg install ${MODULEPATH}dep/kmod-ebtables-ipv4.ipk
opkg install ${MODULEPATH}dep/ebtables.ipk --dest usb

# Update repository
opkg update 

# Ruby
opkg install ruby --dest usb
opkg install ruby-gems --dest usb 
opkg install ruby-core --dest usb 
opkg install ruby-enc --dest usb

# Ruby fix
cp ${MODULEPATH}dep/socket.so /usb/usr/lib/ruby/1.9/mips-linux

touch ${MODULEPATH}installed

echo "done" > ${MODULEPATH}status.php
