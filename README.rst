Undefined Standard Edition
=======================

What's inside?
--------------

Undefined Standard Edition comes pre-configured with the following bundles:

* Bundles from Symfony Standard distribution
* SonataAdminBundle - The missing Symfony2 Admin Generator
* SonataMediaBundle
* SonataUserBundle
* SonataEasyExtendsBundle
* SonataIntlBundle
* SonataNewsBundle
* SonatajQueryBundle
* FOSUserBundle

* LadyBugBundle
* UndfAngularJsBundle


Installation
------------
 * Execute "cp app/config/parameters.yml.sample app/config/parameters.yml" from the console

 * Configure your DB connection by editing database parameters in app/config/parameters.yml

 * Install your vendors by running "composer"

 * Execute "php load_data.php" from the console


Web access
----------
 * Visit /app_dev.php/admin/login
    Username: admin
    Password: admin

 * Visit /app_dev.php/login
    Username: <Get a username form the list "Users" in the admin dashboard>
    Password: password


Unit Testing
------------

Automatic Unit Testing with ``watchr``::

    gem install watchr
    cd /path/to/symfony-project
    watchr phpunit.watchr


Enjoy!
