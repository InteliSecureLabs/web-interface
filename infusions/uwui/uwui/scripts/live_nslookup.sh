while [ 1 ] ; do
	echo > ../data/nslookup.log
	date
	nslookup www.google.es
	echo 
	sleep 10
done

