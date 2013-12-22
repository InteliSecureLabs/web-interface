#!/bin/sh

USBPATH="/usb/"
MODULEPATH="$(dirname $0)/"

mkdir ${USBPATH}pip-build

opkg update
		
opkg install python --dest usb

opkg install python-openssl --dest usb 

#opkg install curl --dest usb

# start of Imagemagick
opkg install imagemagick-tools --dest usb

ln -s /usb/usr/lib/ImageMagick-6.7.8/ /usr/lib/ImageMagick-6.7.8

mkdir /usb/usr/lib/ImageMagick-6.7.8/config
#wget -O /usb/usr/lib/ImageMagick-6.7.8/config/delegates.xml http://www.imagemagick.org/source/delegates.xml
cp ${MODULEPATH}dep/delegates.xml /usb/usr/lib/ImageMagick-6.7.8/config/
#wget -O /usb/usr/lib/ImageMagick-6.7.8/config/coder.xml http://www.imagemagick.org/source/coder.xml
cp ${MODULEPATH}dep/coder.xml /usb/usr/lib/ImageMagick-6.7.8/config/

# end of Imagemagick
		
opkg install setuptools -dest usb
		
#curl -k https://raw.github.com/pypa/pip/master/contrib/get-pip.py | python
python ${MODULEPATH}dep/get-pip.py
		
pip install -b ${USBPATH}pip-build mitmproxy

pip install -b ${USBPATH}pip-build pyasn1

pip install -b ${USBPATH}pip-build BeautifulSoup

pip install -b ${USBPATH}pip-build upsidedown

touch ${MODULEPATH}installed

echo "done" > ${MODULEPATH}status.php

rm -rf ${USBPATH}pip-build