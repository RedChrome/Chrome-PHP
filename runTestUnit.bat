@Echo Off
call tidyUp.bat
if %1!==! goto noDirGiven
php Tests\phpUnit.php --configuration Tests\phpUnit\config.xml %1
pause
:noDirGiven
echo Please give as first argument a directory in Tests\ to test a particular module
pause