@Echo Off
rename include\database.php database.old.php
copy Tests\database.php include
php Tests/setuptestdb.php
del include\database.php
rename include\database.old.php database.php