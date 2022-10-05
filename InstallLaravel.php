<?php

//////////////////////////////////////////////////////////////////////////
////////////////////////////// Config Section ////////////////////////////
//////////////////////////////////////////////////////////////////////////

$color   = "\033[01;32m";
$noColor = "\033[0m";

$packages = [
    "spatie/laravel-backup",
    "spatie/laravel-settings",
    "spatie/laravel-tags",
    "spatie/laravel-ray",
    "spatie/laravel-responsecache",
];

$packagesFilament = [
    "filament/filament",
    "filament/forms",
    "filament/notifications",
    "filament/tables",
    "filament/spatie-laravel-settings-plugin",
    "filament/spatie-laravel-tags-plugin",
    "filipfonal/filament-log-manager",
    "bezhansalleh/filament-shield",
    "3x1io/filament-user",
    "3x1io/filament-menus",
    "ryangjchandler/filament-profile"
];

$packagesDev = [
    "nunomaduro/larastan",
    "pestphp/pest-plugin-laravel",
    "laravel/pint",
    "rector/rector",
];

$postProcessSteps = [
    'php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"',
    'php artisan vendor:publish --provider="Spatie\Tags\TagsServiceProvider" --tag="tags-migrations"',
    'php artisan vendor:publish --provider="Spatie\Tags\TagsServiceProvider" --tag="tags-config"',
    'php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider" --tag="migrations"',
    'php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider" --tag="settings"',
    'php artisan vendor:publish --provider="Spatie\ResponseCache\ResponseCacheServiceProvider"',
    'php artisan ray:publish-config',
];

$postProcessStepsFilament = [
    'php artisan forms:install',
    'php artisan tables:install',
    'php artisan notifications:install',
    'php artisan vendor:publish --tag=filament-config',
    'php artisan vendor:publish --tag=filament-translations',
    'php artisan vendor:publish --tag=filament-views',
    'php artisan vendor:publish --tag=filament-forms-translations',
    'php artisan vendor:publish --tag=filament-support-translations',
    'php artisan vendor:publish --tag=filament-support-views',
    'php artisan vendor:publish --tag=filament-tables-translations',
    'php artisan vendor:publish --tag=filament-log-manager-config',
    'php artisan vendor:publish --tag=filament-log-manager-translations',
    'php artisan vendor:publish --tag=filament-log-manager-views',
    'php artisan vendor:publish --tag=filament-shield-config',
    'php artisan vendor:publish --tag=filament-shield-translations',
    'php artisan vendor:publish --tag=filament-user-config',
    'php artisan vendor:publish --tag=filament-user-translations',
    'php artisan vendor:publish --tag=filament-profile-views',
    'php artisan livewire:discover',
    'php artisan optimize:clear',
];

$postProcessFiles = [
    'rector.php' => "<?php

use Rector\Laravel\Set\LaravelSetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig \$rectorConfig): void {
    \$rectorConfig->sets([
        LaravelSetList::LARAVEL_90
    ]);
};\n\n",

    'phpstan.neon' => "includes:
    - ./vendor/nunomaduro/larastan/extension.neon

parameters:
    paths:
        - app/

    # Level 9 is the highest level
    level: 5

#    ignoreErrors:
#        - '#PHPDoc tag @var#'
#
#    excludePaths:
#        - ./*/*/FileToBeExcluded.php
#
#    checkMissingIterableValueType: false\n\n",
];

$manualSteps = [
    'Create a database for your new Laravel instance',
    'Edit .env so that the database connection info is correct/complete',
    "run {$color}php artisan migrate{$noColor}",
    "run {$color}php artisan db:seed{$noColor}",
];

$manualStepsFilament = [
    "run {$color}php artisan filament:upgrade{$noColor}",
    "run {$color}php artisan make:filament-user{$noColor}",
    "run {$color}php artisan shield:install{$noColor}",
];

$instructions = [
    "You can run self-contained web server for your instance by typing {$color}php artisan serve{$noColor}",
    "Laravel docs are available from https://laravel.com/docs/9.x/",
];
$instructionsFilament = [
    "You can find the filament login screen under /admin",
    "Filament docs are available from https://filamentphp.com/docs/2.x/",
    "Information on how to use the installed filament plugins, go to https://filamentphp.com/plugins",
];

//////////////////////////////////////////////////////////////////////////
/////////////////////////////// Install Code /////////////////////////////
//////////////////////////////////////////////////////////////////////////


// Check for args
$targetDir = $argv[1] ?? null;
if (!$targetDir) {
    die("Usage: InstallLaravel <targetDirectory> [--without-filament]\n");
}
if (file_exists($targetDir)) {
    die("Error: {$color}$targetDir{$noColor} already exists\n");
}
if (strpos($targetDir, '--') === 0) {
    die("Usage: InstallLaravel <targetDirectory> [--without-filament]\n");
}

$withFilament = true;
$argv2        = $argv[2] ?? null;
if ($argv2) {
    if ($argv2 != '--without-filament') {
        die("Usage: InstallLaravel <targetDirectory> [--without-filament]\n");
    }
    $withFilament = false;
}


// Try to find a laravel installer
$laravelInstaller = findLaravelInstaller();
if (!$laravelInstaller) {
    die ("Unable to find a laravel installer\n");
}


// Now install laravel
$cmd = "$laravelInstaller --no-interaction new $targetDir";
if (!$cmd) { // No installer found, use composer 
    $cmd = "composer create-project --prefer-dist laravel/laravel --no-interaction $targetDir";
}
print "{$color}Installing Laravel into [$targetDir]{$noColor}\n";
$rc = system($cmd);
if (!$rc === false) {
    die("System command [$cmd] failed ... exiting\n");
}


// Determine final packagelist
if ($withFilament) {
    $packages = array_merge($packages, $packagesFilament);
    $postProcessSteps = array_merge($postProcessSteps, $postProcessStepsFilament);
    $manualSteps = array_merge($manualSteps, $manualStepsFilament);
    $instructions = array_merge($instructions, $instructionsFilament);
}


// Change into install dir
chdir($targetDir);


// Install main packages
$cmd = "composer --no-interaction require " . implode(' ', $packages);
execSteps($cmd, $color, $noColor);

// Install dev packages
$cmd = "composer --no-interaction --dev require " . implode(' ', $packagesDev);
execSteps($cmd, $color, $noColor);

// Execute post process steps
execSteps($postProcessSteps, $color, $noColor);

// Create post process files
foreach ($postProcessFiles as $k => $v) {
    print "Creating {$color}[$k]{$noColor}\n";
    file_put_contents($k, $v);
}

// Patch user model for filament shield
if (in_array("bezhansalleh/filament-shield", $packages)) {
    patchFilamentConfigForDarkMode($color, $noColor);
    patchUserModelForFilamentShield($color, $noColor);
}


print "\n";
print "---------------------------------------------\n";
print "All done ... {$color}now perform these manual steps:{$noColor}\n";
print "---------------------------------------------\n\n";
foreach ($manualSteps as $k => $v) {
    $kk = $k + 1;
    print "$kk) $v\n";
}
print "\nSome hints to get you started:\n\n";
foreach ($instructions as $k => $v) {
    $kk = $k + 1;
    print "$kk) $v\n";
}

print "\nNow go build something {$color}awesome!{$noColor}\n\n";


//////////////////////////////////////////////////////////////////////////
////////////////////////////// Util Functions ////////////////////////////
//////////////////////////////////////////////////////////////////////////


function commandExists($command): bool
{
    $whereIsCommand = (PHP_OS == 'WINNT') ? 'where' : 'which';

    $process = proc_open(
        "$whereIsCommand $command",
        array(
            0 => array("pipe", "r"), //STDIN
            1 => array("pipe", "w"), //STDOUT
            2 => array("pipe", "w"), //STDERR
        ),
        $pipes
    );
    if ($process !== false) {
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);

        return $stdout != '';
    }

    return false;
}


function execSteps(mixed $steps, string $color, string $noColor): void
{
    $steps = (array)$steps;
    foreach ($steps as $step) {
        print "Executing {$color}$step{$noColor}\n";
        $rc = system($step);
        if ($rc === false) {
            throw new \Exception("Command [$step] returned an error");
        }
    }
}


function findLaravelInstaller(): string|false
{
    $homeDir           = getHomeDirectory();
    $installerCommand  = null;
    $installerCommands = [
        'laravel',
        "$homeDir/bin/laravel",
        "$homeDir/.composer/vendor/bin/laravel",
        "$homeDir/.config/composer/vendor/bin/laravel",
    ];

    foreach ($installerCommands as $installerCommand) {
        print "Checking for laravel installer: [$installerCommand] -> ";
        $haveInstaller = commandExists($installerCommand);
        if ($haveInstaller) {
            print "OK\n";
            return $installerCommand;
        }
        print "not found\n";
    }

    return false;

}


function getHomeDirectory(): string
{
    return posix_getpwuid(getmyuid())['dir'];
}


function patchFilamentConfigForDarkMode(string $color, string $noColor): void
{
    $configFile = "config/filament.php";
    print "Patching {$color}$configFile{$noColor} to enable darkMode\n";

    $fileData = file_get_contents($configFile);
    if (!$fileData) {
        throw new \Exception("Unable to read [$configFile]");
    }
    $fileLines = explode("\n", $fileData);
    $output    = [];

    foreach ($fileLines as $line) {
        if (strpos($line, "'dark_mode' => false")) {
            $line = str_replace('false', 'true', $line);
        }
        $output[] = $line;
    }

    $rc = file_put_contents($configFile, implode("\n", $output));
    if ($rc === false) {
        throw new \Exception("Unable to write [$configFile]");
    }
}


function patchUserModelForFilamentShield(string $color, string $noColor): void
{
    $userModel = "app/Models/User.php";
    print "Patching {$color}$userModel{$noColor} for use with FilamentShield\n";

    $fileData = file_get_contents($userModel);
    if (!$fileData) {
        throw new \Exception("Unable to read [$userModel]");
    }
    $fileLines = explode("\n", $fileData);
    $output    = [];

    $firstUse   = false;
    $firstBrace = false;
    foreach ($fileLines as $line) {
        $output[] = $line;
        if (!$firstUse && strpos($line, 'use ') === 0) {
            $output[] = 'use Spatie\\Permission\\Traits\\HasRoles;';
            $firstUse = true;
        }
        if (!$firstBrace && strpos($line, '{') === 0) {
            $output[]   = "    use HasRoles;";
            $firstBrace = true;
        }
    }

    $rc = file_put_contents($userModel, implode("\n", $output));
    if ($rc === false) {
        throw new \Exception("Unable to write [$userModel]");
    }
}
