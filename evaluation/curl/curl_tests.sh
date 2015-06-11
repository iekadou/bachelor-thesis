#!/bin/bash

URL="$1"
MAX_RUNS="$2"
LARE_NAMESPACE="$3"
CACERT="$4"
PAGES=(/ /imprint/ /tags/p/ /tags/p/2/)

if [ -z "$CACERT" ]
then CACERT=""
else CACERT="--cacert $CACERT"
fi

for PAGE in "${PAGES[@]}"
do
    echo "-------------------------------------------------------------------"
    echo "$PAGE:"
    echo "normal request:"
    SUM_TIME="0"
    i="0"
    while [ $i -lt $MAX_RUNS ]; do
      TIME=`curl $URL$PAGE -s -o /dev/null -w %{time_total} $CACERT`  
      TIME="${TIME/,/.}"
      SUM_TIME=`echo "scale=5; $TIME + $SUM_TIME" | bc`
      i=$[$i+1]
    done
    TIME_AVERAGE=`echo "scale=5; $SUM_TIME / $MAX_RUNS" | bc`
    echo "Avg: $TIME_AVERAGE"
    echo "lare request:"
    SUM_TIME="0"
    i="0"
    while [ $i -lt $MAX_RUNS ]; do
      TIME=`curl $URL$PAGE -H "X-LARE: $3" -s -o /dev/null -w %{time_total} $CACERT`  
      TIME="${TIME/,/.}"
      SUM_TIME=`echo "scale=5; $TIME + $SUM_TIME" | bc`
      i=$[$i+1]
    done
    TIME_AVERAGE=`echo "scale=5; $SUM_TIME / $MAX_RUNS" | bc`
    echo "Avg: $TIME_AVERAGE"
done