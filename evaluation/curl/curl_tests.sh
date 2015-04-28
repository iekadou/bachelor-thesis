#!/bin/bash

URL="$1"
MAX_RUNS="$2"
PJAX_HEADER="$3"
PJAX_NAMESPACE="$4"
SUM_TIME="0"

i="0"
while [ $i -lt $MAX_RUNS ]; do
  TIME=`curl $URL -H "X-PJAX: $3" -H "X-PJAX-NAMESPACE: $4" -o /dev/null -s -w %{time_total}`  
  TIME="${TIME/,/.}"
  SUM_TIME=`echo "scale=5; $TIME + $SUM_TIME" | bc`
  i=$[$i+1]
done

TIME_AVERAGE=`echo "scale=5; $SUM_TIME / $MAX_RUNS" | bc`
echo "Sum: $SUM_TIME"
echo "Avg: $TIME_AVERAGE"