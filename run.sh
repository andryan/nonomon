#!/bin/bash

php ./test.php
sort -t'|' -k3,3 -k7rn,7 test.csv > test-sorted.csv
php ./grab.php
