=
Get-Content
C:\xampp\php\php.ini
-Raw
=
-replace
extension=mysqli(\r\n|\n|\r)(.+\r\n|\n|\r){20,40}extension=mysqli
extension=mysqli$1$2;extension=mysqli
