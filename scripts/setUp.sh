## CONFIG
# use "git tag -l" to list all available versions
YAML_VERSION=v4.1.2
# use "php tests/setuptestdb.php" to list all available connections
DATABASE_CONNECTION=default





## INSTALL YAML ##
## remove old yaml, if exists
rm -rf public/css/yaml
rmdir public/css/yaml

## download yaml from git and get the appropriate version
git clone git://github.com/yamlcss/yaml.git public/_tmp_yaml
cd public/_tmp_yaml
git checkout tags/$YAML_VERSION
cd ../..

## remove the unnecessary stuff and create the needed structure
cp public/_tmp_yaml/yaml/* public/css/yaml
rm -rf public/_tmp_yaml
rmdir public/_tmp_yaml

ls

## COMPOSER ##
composer self-update
composer update

## INIT DATABASE
mysql -e 'create database chrome_2_test;'
php tests/setuptestdb.php $DATABASE_CONNECTION

## clean tmp dir
rm -rf include/tmp
