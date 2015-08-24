#!/bin/bash
cd ${0%/*}

./clean.sh
php ../../tests/phpUnit.php --configuration tests/phpUnit/config.xml $1
