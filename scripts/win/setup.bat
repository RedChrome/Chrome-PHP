## CONFIG
# use "git tag -l" to list all available versions
SET YAML_VERSION=v4.1.2
SET JSTIMEZONEDETECT_VERSION=v1.0.6

## INSTALL YAML ##
## remove old yaml, if exists
del ..\..\public\css\yaml /F /S /Q
rd ..\..\public\css\yaml /S /Q

## download yaml from git and get the appropriate version
git clone https://github.com/yamlcss/yaml.git ..\..\public\_tmp
cd ..\..\public\_tmp
git checkout tags/%YAML_VERSION%
cd ../..

## remove the unnecessary stuff and create the needed structure
move public\_tmp\yaml public\css\yaml
del public\_tmp /F /S /Q
rd public\_tmp /S /Q

cd scripts\win\


## INSTALL JSTIMEZONEDETECT ##
del ..\..\public\javascript\thrid-party\jstimezonedetect /F /S /Q
rd ..\..\public\javascript\thrid-party\jstimezonedetect /S /Q
git clone https://github.com/HenningM/jstimezonedetect.git ..\..\public\_tmp
cd ..\..\public\_tmp
git checkout tags/%JSTIMEZONEDETECT_VERSION%
cd ../..
copy public\_tmp\jstz.min.js public\javascript\third-party\
del public\_tmp /F /S /Q
rd public\_tmp /S /Q
cd scripts\win\


## COMPOSER ##
#composer --working-dir=..\..\ update
