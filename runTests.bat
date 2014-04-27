@Echo Off
call tidyUp.bat
php tests\phpUnit.php --configuration tests\phpUnit\config.xml tests\
pause