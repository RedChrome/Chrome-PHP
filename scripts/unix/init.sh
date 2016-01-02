#!/bin/bash
cd "$(dirname "$0")"

sudo git clone git://github.com/yamlcss/yaml.git ../../public/_tmp_yaml

pwd

cd ../../public/_tmp_yaml

git checkout tags/v4.1.2

cd ../..

rm -r public/css/yaml
mv public/_tmp_yaml/yaml/ public/css/yaml
rm -r public/_tmp_yaml/

cd scripts/unix
composer --working-dir=../../ update

