#!/bin/sh
# vnstati image generation script.
# Source: http://code.google.com/p/x-wrt/source/browse/trunk/package/webif/files/www/cgi-bin/webif/graphs-vnstat.sh

WWW_D="$(dirname $0)/vnstat" # output images to here
LIB_D=/var/lib/vnstat # db location
BIN=/usr/bin/vnstati  # which vnstati
 
outputs="s h d m t"   # what images to generate
 
# Sanity checks
[ -d "$WWW_D" ] || mkdir -p "$WWW_D" # make the folder if it dont exist.

if [ ! -d "/var/lib/" ]; then
mkdir -p /var/lib/
fi


if [ -d "/usb/var/lib/vnstat/" -a ! -d "/var/lib/vnstat/" ]; then
ln -s /usb/var/lib/vnstat /var/lib/vnstat
fi

# End of Sanity checks 

# You might want to setup a link if it dont exist.
# [ -L /www/vnstat ] || ln -sf /www/vnstat /tmp/www/
 
# End of config changes

vnstat -u

interfaces="$(ls -1 $LIB_D)"
 
if [ -z "$interfaces" ]; then
    echo "No database found, nothing to do."
    echo "A new database can be created with the following command: "
    echo "    vnstat -u -i eth0"
    exit 0
else
    for interface in $interfaces; do
        for output in $outputs; do
            $BIN -${output} -i $interface -o $WWW_D/vnstat_${interface}_${output}.png
        done
    done
fi
 
exit 1