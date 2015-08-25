@Echo Off
call clean.bat
php ..\..\tests\phpUnit.php --configuration tests\phpUnit\config.xml tests\
pause