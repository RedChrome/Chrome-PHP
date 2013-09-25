@Echo Off
call tidyUp.bat
php Tests\phpUnit.php --configuration Tests\phpUnit\config.xml Tests\
pause