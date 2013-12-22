echo "$1:$2" >> ../locations/files.lst
strings $1 | grep -v "  0.  0.\|BSSID, First time\|Station MAC, First" | tr -d " " | grep . >../locations/$2.stations
strings ../locations/*.stations | cut -d"," -f1 | sort | uniq > ../locations/stations.lst

echo ""
echo "Stations Marked in location $2:"
strings ../locations/$2.stations | wc -l

echo ""
echo "Total Stations Marked:"
strings ../locations/stations.lst | wc -l
