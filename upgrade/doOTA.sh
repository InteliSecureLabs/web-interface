#!/bin/bash
rm /tmp/upgrade*.bin
wget -O /tmp/upgrade.bin "http://cloud.wifipineapple.com/index.php?downloads&download&stable"
echo "done" > /pineapple/upgrade/otaStatus.php
