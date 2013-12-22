#!/bin/sh

logread -f >> "$(dirname $0)/events" &
