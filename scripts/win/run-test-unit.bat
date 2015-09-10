@Echo Off
call clean.bat
if %1!==! goto noDirGiven
php ..\..\tests\phpUnit.php --configuration tests\phpUnit\config.xml %1
pause
goto nothingToDo

:noDirGiven
echo Please give as first argument a directory in tests\ to test a particular module
pause

:nothingToDo
