#!/bin/bash
cd "$(dirname "$0")"

sudo git clone  https://github.com/yamlcss/yaml.git ../../public/_tmp
pwd
cd ../../public/_tmp
git checkout tags/v4.1.2
cd ../..
rm -r public/css/yaml
mv public/_tmp/yaml/ public/css/yaml
rm -r public/_tmp/


sudo git clone https://github.com/HenningM/jstimezonedetect.git ../../public/_tmp
cd ../../public/_tmp
git checkout tags/v1.0.6
cd ../..
rm -r public/javascript/third-party
mv public/_tmp/jstz.min.js public/javascript/third-party/jstz.min.js
rm -r public/_tmp/

cd scripts/unix
composer --working-dir=../../ update

