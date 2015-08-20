## CONFIG
# use "git tag -l" to list all available versions
SET YAML_VERSION=v4.1.2






## INSTALL YAML ##
## remove old yaml, if exists
del ..\public\css\yaml /F /S /Q
rd ..\public\css\yaml /S /Q

## download yaml from git and get the appropriate version
git clone git://github.com/yamlcss/yaml.git ..\public\_tmp_yaml
cd ..\public\_tmp_yaml
git checkout tags/%YAML_VERSION%
cd ../..

## remove the unnecessary stuff and create the needed structure
move public\_tmp_yaml\yaml public\css\yaml
del public\_tmp_yaml /F /S /Q
rd public\_tmp_yaml /S /Q

cd scripts\

## COMPOSER ##
composer --working-dir=..\ update
