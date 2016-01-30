#!/bin/sh
nmap -sV -T4 -O -F --version-light 172.16.42.112 -oN /opt/pwnpad/web-interface/infusions/nmap/scans/tmp 2>&1 && mv /opt/pwnpad/web-interface/infusions/nmap/scans/tmp /opt/pwnpad/web-interface/infusions/nmap/scans/scan_1361551911
