rmdir ..\..\include\tmp /Q /S
for /f "delims=" %%i in ('dir /b /a-d "..\..\tests\_log"^|findstr /v /b ".gitignore"') do del "..\..\tests\_log\%%i"