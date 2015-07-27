#!/bin/bash

URL="$1"
MAX_RUNS="$2"
CACERT="$3"
declare -a PAGES=('/' '/imprint/' '/tags/p/1/' '/tags/p/2/' '/imprint/' '/' '/tags/p/1/' '/tags/p/1/' '/' '/' '/imprint/' '/imprint/' '/tags/p/2/' '/tags/p/1/')
declare -a FROM_PAGES=('/' '/imprint/' '/tags/p/1/' '/tags/p/2/' '/' '/imprint/' '/' '/imprint/' '/tags/p/1/' '/tags/p/2/' '/tags/p/1/' '/tags/p/2/' '/tags/p/1/' '/tags/p/2/' )
declare -a NAMESPACES=('Lare.Home' 'Lare.Imprint' 'Lare.Tags.p1' 'Lare.Tags.p2' 'Lare.Home' 'Lare.Imprint' 'Lare.Home' 'Lare.Imprint' 'Lare.Tags.p1' 'Lare.Tags.p2' 'Lare.Tags.p1' 'Lare.Tags.p2' 'Lare.Tags.p1' 'Lare.Tags.p2')
declare -a CACHING=("?db_caching=false&tpl_caching=false" "?db_caching=true&tpl_caching=false" "?db_caching=false&tpl_caching=true" "?db_caching=true&tpl_caching=true")
declare -a CACHING_SHORTS=('- & -' '+ & -' '- & +' '+ & +')


START=0
END=${#PAGES[@]}

if [ -z "$CACERT" ]
then CACERT=""
else CACERT="--cacert $CACERT"
fi

for (( c=$START; c<$END; c++ ))
do
    echo "\hline"
    for (( x=0; x<4; x++ ))
     do
        SUM_TIME="0"
        i="0"
        while [ $i -lt $MAX_RUNS ]; do
          TIME=`curl "$URL${PAGES[$c]}${CACHING[$x]}" -s -o /dev/null -w %{time_total} $CACERT`
          TIME="${TIME/,/.}"
          SUM_TIME=`echo "scale=5; $TIME + $SUM_TIME" | bc`
          i=$[$i+1]
        done
        INITIAL_AVERAGE=`echo "scale=1; ($SUM_TIME*1000) / $MAX_RUNS" | bc`
        SUM_TIME="0"
        i="0"
        while [ $i -lt $MAX_RUNS ]; do
          TIME=`curl "$URL${PAGES[$c]}${CACHING[$x]}" -H "X-LARE: ${NAMESPACES[$c]}" -s -o /dev/null -w %{time_total} $CACERT`  
          TIME="${TIME/,/.}"
          SUM_TIME=`echo "scale=5; $TIME + $SUM_TIME" | bc`
          i=$[$i+1]
        done
        LARE_AVERAGE=`echo "scale=1; ($SUM_TIME*1000) / $MAX_RUNS" | bc`
        echo "${FROM_PAGES[$c]} & ${PAGES[$c]} & $INITIAL_AVERAGE & $LARE_AVERAGE & ${CACHING_SHORTS[$x]} \\\\"
    done
done