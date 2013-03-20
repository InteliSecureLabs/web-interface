#!/bin/sh
logger "CLEANUP: Clean-up Script Executed"

# ----------------------------------------------------------------
# The below snippet truncates the karma log if over set threshold.
# TMPFS is about 12M. For larger Karma logs consider USB storage.
# ----------------------------------------------------------------

# q = threshold in bytes
q=5242880
w=`ls -la /tmp/karma.log | awk '{print $5}'`
if [ $w -ge $q ]; then
	logger "CLEANUP: Karma log over threshold, truncating"
	echo "KARMA: Log truncated to prevent memory loss by cleanup.sh" > /tmp/karma.log

	# ------------------------------------------------
	# Consider moving log to mass storage if available
	# ------------------------------------------------
else
	logger "CLEANUP: Karma log looking good"
fi


# ------------------------------------------------------------------------
# The below snippet will drop caches if memory is critical. 
# Under normal circumstances this shouldn't be an issue but if it ever is,
# this should free up enough memory to keep the device from cycling.
# ------------------------------------------------------------------------

# t = threshold in bytes
t=2048
g=`free | grep Mem | awk '{print $4}'`
if [ $g -ge $t ]; then
	logger "CLEANUP: memory looking good"
else
	logger "CLEANUP: memory below threshold, dropping pagecache, dentries and inodes"
	sync
	echo 3 > /proc/sys/vm/drop_caches
fi


