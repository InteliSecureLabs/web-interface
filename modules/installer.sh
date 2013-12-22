#!/bin/sh
#Downloads and installs infusions
#Do not touch.

#Set up variables for more readability.
name=$1
version=$2
dest=$3
md5=$4

#Tell the installer that it is working.
sed -i 's/"done"/"working"/g' installer.php
sed -i 's/"md5"/"working"/g' installer.php


#Remove any left-overs.
rm -rf /opt/pwnpad/web-interface/usb/tmp/infusions
rm -rf /opt/pwnpad/web-interface/tmp/infusions


mkdir -p /opt/pwnpad/web-interface/tmp/infusions
wget -O /opt/pwnpad/web-interface/tmp/infusions/mk4-module-$name-$version.tar.gz "http://cloud.wifipineapple.com/index.php?downloads&downloadModule=$name&moduleVersion=$version"
if [[ $(md5sum /opt/pwnpad/web-interface/tmp/infusions/mk4-module-$name-$version.tar.gz | head -c 33) == $md5 ]]
	then
			tar -xzf /opt/pwnpad/web-interface/tmp/infusions/mk4-module-$name-$version.tar.gz -C /opt/pwnpad/web-interface/tmp/infusions/
				#get config stuff
				config=$(cat /opt/pwnpad/web-interface/tmp/infusions/mk4-module-$name-$version/module.conf)
				confName=$(echo "$config" | grep -i name | awk '{split($0,array,"=")} END{print array[2]}')
				confVersion=$(echo "$config" | grep -i version | awk '{split($0,array,"=")} END{print array[2]}')
				confAuthor=$(echo "$config" | grep -i author | awk '{split($0,array,"=")} END{print array[2]}')
				confStartPage=$(echo "$config" | grep -i startPage | awk '{split($0,array,"=")} END{print array[2]}')
				confSupportLink=$(echo "$config" | grep -i supportLink | sed 's/supportLink=//g')
			mv /opt/pwnpad/web-interface/tmp/infusions/mk4-module-$name-$version/$confName /opt/pwnpad/web-interface/infusions/
			rm -rf /opt/pwnpad/web-interface/tmp/infusions
			echo "$confName|$confVersion|$dest|$confStartPage|$confSupportLink" >> /opt/pwnpad/web-interface/infusions/moduleList
	else
			sed -i 's/working/md5/g' installer.php
			rm -rf /opt/pwnpad/web-interface/tmp/infusions
			exit
fi

#Tell the installer that it is done
sed -i 's/working/done/g' installer.php