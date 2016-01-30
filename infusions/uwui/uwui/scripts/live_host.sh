while [ 1 ] ; do
	echo > ../data/host.log
	date
	strings $1 | grep "^Host:" | sort | uniq
	echo
	sleep 60
done

