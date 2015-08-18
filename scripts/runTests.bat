@Echo Off
call tidyUp.bat
cd ..\
php tests\phpUnit.php --configuration tests\phpUnit\config.xml tests\
cd scripts
pause