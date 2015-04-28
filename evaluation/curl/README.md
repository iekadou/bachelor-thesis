# curl testing

Usage:

./curl_tests.sh $URL $ITERATIONS $PJAXR_ENABLED $PJAXR_NAMESPACE

$URL = URL to curl
$ITERATIONS = Count of Iterations
$PJAXR_ENABLED = true / false
$PJAXR_NAMESPACE = Pjaxr Namespace of pervious page

e.g.:
./curl_tests.sh https://pjaxr.io 100 true Pjaxr.Home
