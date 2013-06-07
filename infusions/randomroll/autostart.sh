#!/bin/sh

randomrollDir="$( cd "$( dirname "$0" )" && pwd)"

"$randomrollDir"/install.sh

"$randomrollDir"/setup-log.sh

/pineapple/dnsspoof/dnsspoof.sh