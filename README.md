# laravel-installer-script
An easily configurable PHP script to install laravel with extra modules and post-install actions.


## What it does
If you do a lot of experimentation with Laravel, this script can save you some time. It allows you 
(in the top section of the script), to configure packages you want to install (both prod and dev) 
as well as some post-install actions and files to create. It also performs some additional actions 
such as publishing package config files, migrations, resources, etc. and patches some files so that 
everything is set up correctly. 

Out of the box, it installs the following packages for prod: 
- spatie/laravel-backup
- patie/laravel-tags
- spatie/laravel-ray
- spatie/laravel-responsecache
- filament/filament
- filament/forms
- filament/notifications
- filament/tables
- filament/spatie-laravel-settings-plugin
- filament/spatie-laravel-tags-plugin
- filipfonal/filament-log-manager
- bezhansalleh/filament-shield
- 3x1io/filament-user
- 3x1io/filament-menus
- ryangjchandler/filament-profile

For dev, it installs the following:
- nunomaduro/larastan,
- pestphp/pest-plugin-laravel
- laravel/pint
- rector/rector

It also creates the following files:
- rector.php
- phpstan.neon


## Who is it for
It's for Laravel developers who quickly want to install a new instance of Laravel and have some manual 
steps taken care of automatically. If you know PHP, the script should be self explanatory, if you don't 
know PHP, this is probably not for you. I wrote it for my own purposes during a weekend of 
experimentation (it's a bit hacky but does the job).

## How to use it
Assuming you've configured packages you want to install in the top section of the script, you can then call
```
php InstallLaravel.php <targetDirectory> [--without-filament]
```
and it will install the latest laravel with the configured packages (ie: with or without filament) and actions. 

### Requirements
- Written against a PHP8.1 installation, should also work on PHP8.0.
- Requires either the Laravel installer or composer to be installed.

