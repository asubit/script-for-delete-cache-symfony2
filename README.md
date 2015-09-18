# Script for delete Symfony2 cache

Useful script for clear Symfony2 cache in production environnement where you can't use SSH access.

Security
--------

The script can't be execute by anybody.

For this there is two security controls in the script :

1. **Control IP address**
   In the script there is a PHP array named "*$ips*" where you have to add your IP address for access the script.

2. **Control secret token**
   When your IP address is enter in the "*$ips*" array your have to define a secret token "*$okToken*"
   Then when you access the script you have to enter your token

Installation
------------

Simple! Just put this script in your "*./web*" directory of your Symfony2 project.

Execute
-------

Access your script : "*www.your-domain.com/web/delete-cache.php*".

**Don't forget the token** : "*www.your-domain.com/web/delete-cache.php?token=123*".

What's doing this script exactly ?
----------------------------------

1. Control client IP address
2. Control token
3. Delete Symfony environnement cache directory
4. Purge APC system cache
5. Return result with logs
