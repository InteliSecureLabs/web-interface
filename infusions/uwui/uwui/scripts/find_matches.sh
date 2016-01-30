
strings $1 | grep -v "  0.  0.\|BSSID, First time\|Station MAC, First" | cut -d"," -f1 | grep . >/tmp/candidates.stations
strings ../locations/stations.lst | grep -f /tmp/candidates.stations >/tmp/matches.stations 2>/dev/null

grep -f /tmp/matches.stations ../locations/*.stations 2>/dev/null | cut -d"," -f1
