off
copy
C:\xampp\php\php.ini
C:\xampp\php\php.ini.backup
powershell
-Command
(Get-Content C:\xampp\php\php.ini) -replace '^extension=mysqli$', ';commented-out-duplicate=mysqli' | Out-File -FilePath C:\xampp\php\php.ini.temp -Encoding utf8
powershell
-Command
(Get-Content C:\xampp\php\php.ini.temp) -replace '^extension=openssl$', ';commented-out-duplicate=openssl' | Out-File -FilePath C:\xampp\php\php.ini.new -Encoding utf8
del
C:\xampp\php\php.ini.temp
move
/Y
C:\xampp\php\php.ini.new
C:\xampp\php\php.ini
echo
PHP
configuration
file
has
been
fixed.
Please
try
running
php artisan serve
again.
