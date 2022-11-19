<?php

//////////////////////////////////////////////////////////////////////////
////////////////////////////// Config Section ////////////////////////////
//////////////////////////////////////////////////////////////////////////

$color   = "\033[01;32m";
$noColor = "\033[0m";

$packages = [
    "spatie/laravel-backup",
    "spatie/laravel-model-flags",
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
    "ryangjchandler/filament-profile",
    "ryangjchandler/filament-feature-flags",
    "spatie/filament-markdown-editor",
];

$packagesJetstream = [
    "laravel/jetstream"
];

$packagesDev = [
    "nunomaduro/larastan",
    "pestphp/pest-plugin-laravel",
    "laravel/pint",
    "rector/rector",
];

$postProcessSteps = [
    'php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"',
    'php artisan vendor:publish --tag="model-flags-migrations"',
    'php artisan vendor:publish --tag="model-flags-config"',
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
    'php artisan vendor:publish --tag=forms-config',
    'php artisan vendor:publish --tag=forms-translations',
    'php artisan vendor:publish --tag=forms-views',
    'php artisan vendor:publish --tag=notifications-config',
    'php artisan vendor:publish --tag=notifications-translations',
    'php artisan vendor:publish --tag=notifications-views',
    'php artisan vendor:publish --tag=tables-config',
    'php artisan vendor:publish --tag=tables-translations',
    'php artisan vendor:publish --tag=tables-views',
    'php artisan vendor:publish --tag=filament-support-config',
    'php artisan vendor:publish --tag=filament-support-translations',
    'php artisan vendor:publish --tag=filament-support-views',
    'php artisan vendor:publish --tag=filament-log-manager-config',
    'php artisan vendor:publish --tag=filament-log-manager-translations',
    'php artisan vendor:publish --tag=filament-log-manager-views',
    'php artisan vendor:publish --tag=filament-shield-config',
    'php artisan vendor:publish --tag=filament-shield-translations',
    'php artisan vendor:publish --tag=filament-shield-views',
    'php artisan vendor:publish --tag=filament-user-config',
    'php artisan vendor:publish --tag=filament-user-translations',
    'php artisan vendor:publish --tag=filament-user-views',
    'php artisan vendor:publish --tag=filament-profile-config',
    'php artisan vendor:publish --tag=filament-profile-translations',
    'php artisan vendor:publish --tag=filament-profile-views',
    'php artisan vendor:publish --tag=feature-flags-migrations',
    'php artisan vendor:publish --tag=feature-flags-config',
    'php artisan livewire:discover',
    'php artisan optimize:clear',
];

$postProcessStepsJetstreamLivewire = [
    "php artisan jetstream:install livewire --teams --pest",
    "php artisan vendor:publish --tag=jetstream-views",
];

$postProcessStepsJetstreamInertia = [
    "php artisan jetstream:install livewire --teams --pest",
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

$manualStepsJetstreamInertia = [
    "npm install",
    "npm run build",
];

$instructions          = [
    "You can run self-contained web server for your instance by typing {$color}php artisan serve{$noColor}",
    "Laravel docs are available from https://laravel.com/docs/9.x/",
];
$instructionsFilament  = [
    "Filament docs are available from https://filamentphp.com/docs/2.x/",
    "You can find the filament login screen under /admin",
    "Filament docs are available from https://filamentphp.com/docs/2.x/",
    "Information on how to use the installed filament plugins, go to https://filamentphp.com/plugins",
];
$instructionsJetstream = [
    "Jetstream docs are available from https://jetstream.laravel.com/2.x/introduction.html",
];

//////////////////////////////////////////////////////////////////////////
/////////////////////////////// Install Code /////////////////////////////
//////////////////////////////////////////////////////////////////////////


// Check for args
$targetDir = $argv[1] ?? null;
if (!$targetDir) {
    die("Usage: InstallLaravel <targetDirectory> [--with-filament|--with-jetstream-livewire|--with-jetstream-inertia|--with-strict-mode]\n");
}
if (file_exists($targetDir)) {
    die("Error: {$color}$targetDir{$noColor} already exists\n");
}
if (strpos($targetDir, '--') === 0) {
    die("Usage: InstallLaravel <targetDirectory> [--with-filament|--with-jetstream-livewire|--with-jetstream-inertia|--with-strict-mode]\n");
}


/////////////////////////////// Parse Args /////////////////////////////
$validArgs = [
    '--with-filament',
    '--with-jetstream',
    '--with-jetstream-livewire',
    '--with-jetstream-inertia',
    '--with-strict-mode'
];
$withFilament         = false;
$withJetstramLivewire = false;
$withJetstramInertia  = false;
$withStrictMode       = false;
for($i=2; $i<$argc; $i++) {
    switch($argv[$i]) {
        case '--with-filament':
            $withFilament = true;
            break;
        case '--with-jetstream':
        case '--with-jetstream-livewire':
            $withJetstreamLivewire = true;
            break;
        case '--with-jetstream-inertia':
            $withJetstramInertia = true;
            break;
        case '--with-strict-mode':
            $withStrictMode = true;
            break;
        default:
            die("Usage: InstallLaravel <targetDirectory> [--with-filament|--with-jetstream-livewire|--with-jetstream-inertia|--with-strict-mode]\n");
    }
}

if ($withFilament && ($withJetstreamLivewire || $withJetstramInertia)) {
    die("Please choose either Filament or Jetstream support but not both\n");
}
if ($withJetstreamLivewire && $withJetstramInertia) {
    die("Please choose either Jetstream-Livewire or Jetstream-Inertia support but not both\n");
}


/////////////////////////////// Try to find Laravel Installer /////////////////////////////
$laravelInstaller = findLaravelInstaller();


/////////////////////////////// Install Laravel /////////////////////////////
$cmd = "$laravelInstaller --no-interaction new $targetDir";
if (!$laravelInstaller) { // No installer found, use composer 
    $cmd = "composer create-project --prefer-dist laravel/laravel --no-interaction $targetDir";
}
print "{$color}Installing Laravel into [$targetDir]{$noColor}\n";
$rc = system($cmd);
if (!$rc === false) {
    die("System command [$cmd] failed ... exiting\n");
}


/////////////////////////////// Build final package/steps/etc Lists /////////////////////////////
if ($withFilament) {
    $packages         = array_merge($packages, $packagesFilament);
    $postProcessSteps = array_merge($postProcessSteps, $postProcessStepsFilament);
    $manualSteps      = array_merge($manualSteps, $manualStepsFilament);
    $instructions     = array_merge($instructions, $instructionsFilament);
} elseif ($withJetstreamLivewire) {
    $packages         = array_merge($packages, $packagesJetstream);
    $postProcessSteps = array_merge($postProcessSteps, $postProcessStepsJetstreamInertia);
    $instructions     = array_merge($instructions, $instructionsJetstream);
} elseif ($withJetstramInertia) {
    $packages         = array_merge($packages, $packagesJetstream);
    $postProcessSteps = array_merge($postProcessSteps, $postProcessStepsJetstreamInertia);
    $instructions     = array_merge($instructions, $instructionsJetstream);
}


/////////////////////////////// Change into install dir /////////////////////////////
chdir($targetDir);


/////////////////////////////// Install main packages /////////////////////////////
$cmd = "composer --no-interaction require " . implode(' ', $packages);
execSteps($cmd, $color, $noColor);

/////////////////////////////// Install dev packages /////////////////////////////
$cmd = "composer --no-interaction --dev require " . implode(' ', $packagesDev);
execSteps($cmd, $color, $noColor);

/////////////////////////////// Execute PostProcess Steps /////////////////////////////
execSteps($postProcessSteps, $color, $noColor);

/////////////////////////////// Create PostProcess Files /////////////////////////////
foreach ($postProcessFiles as $k => $v) {
    print "Creating {$color}[$k]{$noColor}\n";
    file_put_contents($k, $v);
}

/////////////////////////////// Patch user model for filament-shield /////////////////////////////
if (in_array("bezhansalleh/filament-shield", $packages)) {
    patchFilamentConfig($color, $noColor);
    patchFilamentTablesConfigFile($color, $noColor);
    patchFilamentComposerJson($color, $noColor);
    patchUserModelForFilamentShield($color, $noColor);
}

/////////////////////////////// Patch AppServiceProvider for StrictMode /////////////////////////////
if ($withStrictMode) {
    patchAppServiceProviderForStrictMode($color, $noColor);
}


print "\n";
print "{$color}";
print "============================================\n";
print "All done ... now perform these manual steps:\n";
print "============================================\n";
print "{$noColor}";
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

    print "Reverting to composer\n";

    return false;

}


function getHomeDirectory(): string
{
    return posix_getpwuid(getmyuid())['dir'];
}


function patchFilamentConfig(string $color, string $noColor): void
{
    $configFile = "config/filament.php";
    print "Patching {$color}$configFile{$noColor} to enable darkMode & collapsibleOnDesktop\n";

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
        if (strpos($line, "'is_collapsible_on_desktop' => false")) {
            $line = str_replace('false', 'true', $line);
        }
        $output[] = $line;
    }

    $rc = file_put_contents($configFile, implode("\n", $output));
    if ($rc === false) {
        throw new \Exception("Unable to write [$configFile]");
    }
}


function patchFilamentComposerJson(string $color, string $noColor): void
{
    $composerFile = "composer.json";
    print "Patching {$color}$composerFile{$noColor} to enable automatic upgrade\n";

    $fileData = file_get_contents($composerFile);
    if (!$fileData) {
        throw new \Exception("Unable to read [$composerFile]");
    }
    $fileLines        = explode("\n", $fileData);
    $output           = [];
    $insertNewLineNow = false;
    foreach ($fileLines as $line) {
        if (strpos($line, "@php artisan vendor:publish --tag=laravel-assets --ansi --force")) {
            $insertNewLineNow = true;
            $line .= ",";
        }
        $output[] = $line;
        if ($insertNewLineNow) {
            $output[]         = '"@php artisan filament:upgrade"';
            $insertNewLineNow = false;
        }
    }

    $rc = file_put_contents($composerFile, implode("\n", $output));
    if ($rc === false) {
        throw new \Exception("Unable to write [$composerFile]");
    }
}


function patchFilamentTablesConfigFile(string $color, string $noColor): void
{
    $configFile = "config/tables.php";
    print "Patching {$color}$configFile{$noColor} to increase table pagination size\n";

    $fileData = file_get_contents($configFile);
    if (!$fileData) {
        throw new \Exception("Unable to read [$configFile]");
    }
    $fileLines = explode("\n", $fileData);
    $output    = [];

    foreach ($fileLines as $line) {
        if (strpos($line, "'default_records_per_page' => 10")) {
            $line = str_replace('10', '25', $line);
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
    $fileLines  = explode("\n", $fileData);
    $output     = [];
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


function patchAppServiceProviderForStrictMode(string $color, string $noColor): void
{
    $serviceProvider = "app/Providers/AppServiceProvider.php";
    print "Patching {$color}$serviceProvider{$noColor} to enable StrictMode protections & logging\n";

    $fileData = file_get_contents($serviceProvider);
    if (!$fileData) {
        throw new \Exception("Unable to read [$serviceProvider]");
    }
    $fileLines = explode("\n", $fileData);
    $output    = [];
    $firstUse  = false;

    for ($i=0; $i<count($fileLines); $i++) {
        $line     = $fileLines[$i];
        $output[] = $line;
        if (!$firstUse && strpos($line, 'use ') === 0) {
            $output[] = 'use Illuminate\Database\Connection';
            $output[] = 'use Illuminate\Database\Eloquent\Model;';
            $output[] = 'use Illuminate\Contracts\Http\Kernel as HttpKernel;';
            $output[] = 'use Illuminate\Contracts\Console\Kernel as ConsoleKernel;';
            $output[] = 'use Illuminate\Support\Facades\DB;';
            $output[] = 'use Illuminate\Support\Facades\Log;';
            $firstUse = true;
        }
        if (strpos($line, 'public function boot()') !== false) {
            $output[] = $fileLines[++$i];
            $output[] = "
        // Enable partially hydrated model protection
        Model::preventAccessingMissingAttributes();

        // Enable unfillable property setting protection
        Model::preventSilentlyDiscardingAttributes();

        // Prevent lazy loading always.
        Model::preventLazyLoading();

        // But in production, log the violation instead of throwing an exception.
        if (\$this->app->isProduction()) {
            Model::handleLazyLoadingViolationUsing(function (\$model, \$relation) {
                \$class = get_class(\$model);
                Log::warning(\"Attempted to lazy load [{\$relation}] on model [{\$class}].\");
            });
        }

        // Log queries which run for more than 1 second
        DB::whenQueryingForLongerThan(1000, function (Connection \$connection) {
            Log::warning(\"Database queries exceeded 1 second on {\$connection->getName()}\");
        });

        // Log slow Commands (5 seconds) or Requests (1 second)
        if (\$this->app->runningInConsole()) {
            \$this->app[ConsoleKernel::class]->whenCommandLifecycleIsLongerThan(
                5000,
                function (\$startedAt, \$input, \$status) {
                    Log::warning(\"A command took longer than 5 seconds.\", [
                        'command' => (string)\$input,
                        'duration' => \$startedAt->floatDiffInSeconds(),
                    ]);
                }
            );
        } else {
            \$this->app[HttpKernel::class]->whenRequestLifecycleIsLongerThan(
                1000,
                function (\$startedAt, \$request, \$response) {
                    Log::warning(\"A request took longer than 1 seconds.\", [
                        'user' => \$request->user()?->id,
                        'url' => \$request->fullUrl(),
                        'duration' => \$startedAt->floatDiffInSeconds(),
                    ]);
                }
            );
        }\n";
        }
    }

    $rc = file_put_contents($serviceProvider, implode("\n", $output));
    if ($rc === false) {
        throw new \Exception("Unable to write [$serviceProvider]");
    }
}
