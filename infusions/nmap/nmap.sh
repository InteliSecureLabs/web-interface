#!/bin/sh
nmap -sV -T4 -O -F --version-light 172.16.42.112 -oN /pineapple/infusions/nmap/scans/tmp 2>&1 && mv /pineapple/infusions/nmap/scans/tmp /pineapple/infusions/nmap/scans/scan_1361551911
