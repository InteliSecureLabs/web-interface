while [ 1 ] ; do
	echo > ../data/user-agent.log
	date
	strings $1 | grep "^User-Agent" | sort | uniq
	echo
	sleep 60
done

