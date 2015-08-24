FdaDsb
======

Freiburger-Darts-Association Darts-Score-Board

A Symfony project created on August 24, 2015, 7:44 pm.

Install
=======

1)
composer install

2)
setfacl -Rm u:www-data:rwX app/cache app/logs
setfacl -dRm u:www-data:rwX app/cache app/logs

3)
app/console mopa:bootstrap:symlink:less
app/console mopa:bootstrap:install:font
