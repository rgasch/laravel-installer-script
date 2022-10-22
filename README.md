# laravel-installer-script
An easily configurable PHP script to install laravel with extra modules and post-install actions.


## What it does
If you do a lot of experimentation with Laravel, this script can save you some time. It allows you 
(in the top section of the script), to configure packages you want to install (both prod and dev) 
as well as some post-install actions and files to create. It also performs some additional actions 
such as publishing package config files, migrations, resources, etc. and patches some files so that 
everything is set up correctly. 


## Who is it for
It's for Laravel developers who quickly want to install a new instance of Laravel, have some manual 
steps taken care of automatically and have a baseline for further experimentation and/or development. 
If you know PHP, the install script should be self explanatory, if you don't know PHP, this is probably 
not for you. I wrote it for my own purposes during a weekend of experimentation (it's a bit hacky but 
does the job).


## Usage

InstallLaravel.php <targetDirectory> [--with-filament|--with-jetstream-livewire|--with-jetstream-inertia] [--with-strict-mode]


### Default Install

Out of the box, it installs the following packages: 

- spatie/laravel-backup
- spatie/laravel-ray,
- spatie/laravel-responsecache
- spatie/laravel-settings
- spatie/laravel-tags


### Strict Option Install

Regardless of which extra distribution you choose to install (see below), you can supply
the '--with-strict-mode' option which will patch AppServiceProvider to activate the following
safeguards: 

- Model::preventAccessingMissingAttributes()
- Model::preventSilentlyDiscardingAttributes()
- Model::preventLazyLoading() - in production mode this will only log violations
- Logging of queries running longer than 1 second
- Logging of requests running longer than 1 second
- Logging of commands running longer than 5 seconds

For a more in-detail discussion of these options see 
[this](https://planetscale.com/blog/laravels-safety-mechanisms) excellent blog post 
by [Aaron Francis](https://twitter.com/aarondfrancis).


### Filament Install (--with-filament)

If you select to install Filament, the following packages will be installed

- filament/filament
- filament/forms
- filament/notifications
- filament/tables
- filament/spatie-laravel-settings-plugin
- filament/spatie-laravel-tags-plugin
- 3x1io/filament-user
- 3x1io/filament-menus
- bezhansalleh/filament-shield
- filipfonal/filament-log-manager
- ryangjchandler/filament-profile
- ryangjchandler/filament-feature-flags
- spatie/laravel-backup
- spatie/laravel-model-flags
- spatie/laravel-ray
- spatie/laravel-responsecache
- spatie/laravel-settings
- spatie/laravel-tags

In addition to this it will apply patches to enable the following filament features: 

- Patch the User model to enable usage of filament-shield
- Enable Dark Mode
- Enable Collapsible menu on desktop
- Increase default pagination size to 25


### Jetstream Livewire Install (--with-jetstream|--with-jetstream-livewire)

If you select to install Jetstream Livewire, the following package will be installed

- laravel/jetstream

and the livewire config will be published. 


### Jetstream Inertia Install (--with-jetstream-inertia)

If you select to install Jetstream Livewire, the following package will be installed

- laravel/jetstream

and the inertia config will be published. 


### Dev Packages

Regardless of which install option you choose, the following dev packages will be installed 

- laravel/pint
- nunomaduro/larastan,
- pestphp/pest-plugin-laravel
- rector/rector

Along with these dev packages, the following files will be created: 

- rector.php
- phpstan.neon


## Config files and assets

If any of the installed packages provide publishable 

## Requirements
- Written against a PHP8.1 installation, should also work on PHP8.0.
- Requires either the Laravel installer or composer to be installed.

