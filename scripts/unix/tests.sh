#!/bin/bash

cd ${0%/*}

php ../../tests/phpUnit.php --configuration tests/phpUnit/config.xml tests/
