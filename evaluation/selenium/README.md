# selenium testing

Reqiurements:
- Lare sample web application should be running
- AngularJS sample web application should be running
- Requirements needs to be installed inside virtual environment or globally
  (Create virtual environment: python virtualenv.py venv --distribute)
  (Install requirements: pip install -r requirements)

Usage:

python test_performance.py $LARE_URL $ANGULAR_URL $ITERATIONS

$LARE_URL = URL to Lare web application
$ANGULAR_URL = URL to AngularJS web application
$ITERATIONS = Count of Iterations

e.g.:
./curl_tests.sh https://lare.io https://angular.io 10
