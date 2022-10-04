<?php

//////////////////////////////////////////////////////////////////////////
////////////////////////////// Config Section ////////////////////////////
//////////////////////////////////////////////////////////////////////////

$packages = [
    "filament/filament",
    "spatie/laravel-backup",
    "spatie/laravel-tags",
    "spatie/laravel-ray",
    "spatie/laravel-responsecache",
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
    'php artisan vendor:publish --provider="Spatie\ResponseCache\ResponseCacheServiceProvider"',
    'php artisan ray:publish-config',
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



//////////////////////////////////////////////////////////////////////////
/////////////////////////////// Install Code /////////////////////////////
//////////////////////////////////////////////////////////////////////////


// Check for targetDir argument
$targetDir = $argv[1] ?? null;
if (!$targetDir) {
    die("Usage: InstallLaravel <targetDirectory>\n");
}


// Try to find a laravel installer
$laravelInstaller = findLaravelInstaller();
if (!$laravelInstaller) {
    die ("Unable to find a laravel installer\n");
}

// Define colors
$color   = "\033[01;32m";
$noColor = "\033[0m";

// Now install laravel
$cmd = "$laravelInstaller --no-interaction new $targetDir";
// No installer found, use composer without "new" option
if (strpos($laravelInstaller, 'composer') === 0) {
    $cmd = "$laravelInstaller --no-interaction $targetDir";
}
print "{$color}Installing Laravel into [$targetDir]{$noColor}\n";
system($cmd);

// Install main packages
chdir($targetDir);
$packageList = implode(' ', $packages);
$cmd = "composer --no-interaction require $packageList";
print "{$color}Executing [$cmd]{$noColor}\n";
system($cmd);

// Install dev packages
$packageList = implode(' ', $packagesDev);
$cmd = "composer --no-interaction require $packageList --dev";
print "{$color}Executing [$cmd]{$noColor}\n";
system($cmd);

// Execute post process steps
foreach ($postProcessSteps as $step) {
    print "{$color}Executing [$step]{$noColor}\n";
    system($step);
}

// Create post process files
foreach ($postProcessFiles as $k => $v) {
    print "{$color}Creating [$k]{$noColor}\n";
    file_put_contents($k, $v);
}

print "All done ...\n";


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


function findLaravelInstaller(): string|false
{
    $homeDir           = getHomeDirectory();
    $installerCommand  = null;
    $installerCommands = [
        'laravel',
        "$homeDir/bin/laravel",
        "$homeDir/.composer/vendor/bin/laravel",
        "$homeDir/.config/composer/vendor/bin/laravel",
        "composer create-project --prefer-dist laravel/laravel",
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

