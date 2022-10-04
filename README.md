# laravel-installer-script
An easily configurable PHP script to install laravel with extra modules and post-install actions


## What it does
If you do a lot of experimentation with Laravel, this script can save you some time. It allows you 
(in the top section of the script), to configure packages you want to install (both prod and dev) 
as well as some post-install actions and files to create. 


## Who is it for
It's for Laravel developers. If you know PHP, the script should be self explanatory, if you don't 
know PHP, this is probably not for you. I wrote it for my own purposes during a weekend of 
experimentation. 

# How to use it
Assuming you've configured packages you want to install in the top section of the script, you can then call
```
php InstallLaravel.php <targetDirectory>
```
and it will install the latest laravel with the configured packages and actions. 

### Requirements
Written against a PHP8.1 installation, should also work on PHP8.0.
