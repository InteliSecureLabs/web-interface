#!/bin/sh
#Downloads and installs infusions
#Do not touch.

#Set up variables for more readability.
name=$1
version=$2
dest=$3
md5=$4

#Tell the installer that it is working.
sed -i 's/"done"/"working"/g' /pineapple/modules/installer.php
sed -i 's/"md5"/"working"/g' /pineapple/modules/installer.php


#Remove any left-overs.
rm -rf /usb/tmp/infusions
rm -rf /tmp/infusions

#Download infusion. Do the magic.
if [[ $dest == "usb" ]]
	then
		mkdir -p /usb/tmp/infusions
		wget -O /usb/tmp/infusions/mk4-module-$name-$version.tar.gz "http://cloud.wifipineapple.com/index.php?downloads&downloadModule=$name&moduleVersion=$version"
		if [[ $(md5sum /usb/tmp/infusions/mk4-module-$name-$version.tar.gz | head -c 33) == $md5 ]]
			then
				mkdir -p /usb/infusions/
				rm /pineapple/infusions/usbModules
				ln -s /usb/infusions /pineapple/infusions/usbInfusions
				tar -xzf /usb/tmp/infusions/mk4-module-$name-$version.tar.gz -C /usb/tmp/infusions/
					#get config stuff
					config=$(cat /usb/tmp/infusions/mk4-module-$name-$version/module.conf)
					confName=$(echo "$config" | grep -i name | awk '{split($0,array,"=")} END{print array[2]}')
					confVersion=$(echo "$config" | grep -i version | awk '{split($0,array,"=")} END{print array[2]}')
					confAuthor=$(echo "$config" | grep -i author | awk '{split($0,array,"=")} END{print array[2]}')
					confStartPage=$(echo "$config" | grep -i startPage | awk '{split($0,array,"=")} END{print array[2]}')
					confSupportLink=$(echo "$config" | grep -i supportLink | sed 's/supportLink=//g')
				mv /usb/tmp/infusions/mk4-module-$name-$version/$confName /usb/infusions/
				rm -rf /usb/tmp/infusions
				echo "$confName|$confVersion|$dest|$confStartPage|$confSupportLink" >> /pineapple/infusions/moduleList
			else
				sed -i 's/working/md5/g' /pineapple/modules/installer.php
				rm -rf /usb/tmp/infusions
				exit
		fi
	else
		mkdir -p /tmp/infusions
		wget -O /tmp/infusions/mk4-module-$name-$version.tar.gz "http://cloud.wifipineapple.com/index.php?downloads&downloadModule=$name&moduleVersion=$version"
		if [[ $(md5sum /tmp/infusions/mk4-module-$name-$version.tar.gz | head -c 33) == $md5 ]]
			then
				tar -xzf /tmp/infusions/mk4-module-$name-$version.tar.gz -C /tmp/infusions/
					#get config stuff
					config=$(cat /tmp/infusions/mk4-module-$name-$version/module.conf)
					confName=$(echo "$config" | grep -i name | awk '{split($0,array,"=")} END{print array[2]}')
					confVersion=$(echo "$config" | grep -i version | awk '{split($0,array,"=")} END{print array[2]}')
					confAuthor=$(echo "$config" | grep -i author | awk '{split($0,array,"=")} END{print array[2]}')
					confStartPage=$(echo "$config" | grep -i startPage | awk '{split($0,array,"=")} END{print array[2]}')
					confSupportLink=$(echo "$config" | grep -i supportLink | sed 's/supportLink=//g')
				mv /tmp/infusions/mk4-module-$name-$version/$confName /pineapple/infusions/
				rm -rf /tmp/infusions
				echo "$confName|$confVersion|$dest|$confStartPage|$confSupportLink" >> /pineapple/infusions/moduleList
			else
				sed -i 's/working/md5/g' /pineapple/modules/installer.php
				rm -rf /tmp/infusions
				exit
		fi
fi


#Tell the installer that it is done
sed -i 's/working/done/g' /pineapple/modules/installer.php