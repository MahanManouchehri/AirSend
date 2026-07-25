param(
    [Parameter(Mandatory = $true)][string]$Project,
    [string]$Task = ''
)

$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$root = Split-Path -Parent $projectRoot

function Require-Command([string]$command, [string]$hint) {
    if (-not (Get-Command $command -ErrorAction SilentlyContinue)) {
        throw "'$command' is required. $hint"
    }
}

function Start-MySql {
    if (Test-NetConnection -ComputerName '127.0.0.1' -Port 3306 -InformationLevel Quiet -WarningAction SilentlyContinue) { return }
    $mysql = Get-Service -ErrorAction SilentlyContinue | Where-Object { $_.Name -match '^(MySQL|MariaDB)' -or $_.DisplayName -match 'MySQL|MariaDB' } | Select-Object -First 1
    if ($mysql -and $mysql.Status -ne 'Running') {
        Write-Host "Starting database service: $($mysql.Name)"
        Start-Service -Name $mysql.Name
    }
    elseif (-not $mysql -and (Test-Path 'C:\xampp\mysql_start.bat')) {
        Write-Host 'Starting MySQL through XAMPP...'
        Start-Process cmd.exe -ArgumentList '/c', 'C:\xampp\mysql_start.bat' -WindowStyle Hidden | Out-Null
    }
    for ($i = 0; $i -lt 20; $i++) {
        if (Test-NetConnection -ComputerName '127.0.0.1' -Port 3306 -InformationLevel Quiet -WarningAction SilentlyContinue) { return }
        Start-Sleep -Seconds 1
    }
    throw 'MySQL/MariaDB did not become available on 127.0.0.1:3306. Start it in XAMPP or install it as a Windows service.'
}

function Bootstrap-Laravel([string]$dir, [bool]$needsMySql) {
    Require-Command php 'Install PHP and add it to PATH.'
    Require-Command composer 'Install Composer and add it to PATH.'
    Set-Location $dir
    if (-not (Test-Path '.env') -and (Test-Path '.env.example')) { Copy-Item '.env.example' '.env'; php artisan key:generate --force }
    if (-not (Test-Path 'vendor\autoload.php')) { composer install }
    if (Test-Path 'package.json') { Require-Command npm 'Install Node.js LTS.'; if (-not (Test-Path 'node_modules')) { npm install } }
    if ($needsMySql) { Start-MySql }
}

function Bootstrap-Node([string]$dir, [string]$manager = 'npm') {
    Set-Location $dir
    if ($manager -eq 'pnpm') { Require-Command pnpm 'Enable Corepack (corepack enable) or install pnpm.' } else { Require-Command npm 'Install Node.js LTS.' }
    if (-not (Test-Path 'node_modules')) { if ($manager -eq 'pnpm') { pnpm install } else { npm install } }
}

function Start-Task([string]$title, [string]$dir, [string]$command, [string]$bootstrap) {
    $childCommand = "& '$PSCommandPath' -Project '$Project' -Task '$title'"
    Start-Process powershell -ArgumentList @('-NoExit', '-ExecutionPolicy', 'Bypass', '-Command', $childCommand) -WorkingDirectory $dir | Out-Null
    Write-Host "Started $title"
}

$apps = @()
switch ($Project) {
    'Moven' { $apps = @(
        @{t='Moven API'; d="$root\Moven\laravel"; b='Bootstrap-Laravel (Get-Location).Path $false'; c='php artisan serve --host=127.0.0.1 --port=8010'},
        @{t='Moven web'; d="$root\Moven\react"; b='Bootstrap-Node (Get-Location).Path'; c='npm run dev -- --host 127.0.0.1 --port 3010'},
        @{t='Moven Reverb'; d="$root\Moven\laravel"; b='Bootstrap-Laravel (Get-Location).Path $false'; c='php artisan reverb:start --host=127.0.0.1 --port=8080'} ) }
    'Peykad' { $apps = @(
        @{t='Peykad API'; d="$root\Peykad\peykad-laravel"; b='Bootstrap-Laravel (Get-Location).Path $false'; c='php artisan serve --host=127.0.0.1 --port=8020'},
        @{t='Peykad web'; d="$root\Peykad\peykad-react"; b='Bootstrap-Node (Get-Location).Path'; c='npm run dev -- --host 127.0.0.1 --port 3020'},
        @{t='Peykad Reverb'; d="$root\Peykad\peykad-laravel"; b='Bootstrap-Laravel (Get-Location).Path $false'; c='php artisan reverb:start --host=127.0.0.1 --port=8081'} ) }
    'Revaal' { $apps = @(@{t='Revaal API'; d="$root\Revaal\backend"; b='Bootstrap-Laravel (Get-Location).Path $false'; c='php artisan serve --host=127.0.0.1 --port=8030'}, @{t='Revaal web'; d="$root\Revaal\frontend"; b='Bootstrap-Node (Get-Location).Path'; c='npm run dev -- --hostname 127.0.0.1 --port 3030'}) }
    'CMS-shopping' { $apps = @(@{t='CMS API'; d="$root\CMS-shopping\cms_laravel"; b='Bootstrap-Laravel (Get-Location).Path $false'; c='php artisan serve --host=127.0.0.1 --port=8040'}, @{t='CMS web'; d="$root\CMS-shopping\cms_next"; b='Bootstrap-Node (Get-Location).Path'; c='npm run dev -- --hostname 127.0.0.1 --port 3040'}) }
    'Classino' { $apps = @(@{t='Classino API'; d="$root\Classino\backend"; b='Bootstrap-Laravel (Get-Location).Path $false'; c='php artisan serve --host=127.0.0.1 --port=8050'}, @{t='Classino web'; d="$root\Classino\frontend"; b='Bootstrap-Node (Get-Location).Path'; c='npm run dev -- --host 127.0.0.1 --port 3060'}) }
    'NFT' { $apps = @(@{t='NFT API'; d="$root\NFT\backend"; b='Bootstrap-Laravel (Get-Location).Path $false'; c='php artisan serve --host=127.0.0.1 --port=8060'}, @{t='NFT web'; d="$root\NFT\frontend"; b='Bootstrap-Node (Get-Location).Path'; c='npm run dev -- --hostname 127.0.0.1 --port 3070'}) }
    'AirSend' { $apps = @(@{t='AirSend Laravel'; d="$root\AirSend\Laravel"; b='Bootstrap-Laravel (Get-Location).Path $false'; c='php artisan serve --host=127.0.0.1 --port=8005'}, @{t='AirSend Django'; d="$root\AirSend\Django"; b="Require-Command python 'Install Python 3.'"; c='python manage.py migrate; python manage.py runserver 127.0.0.1:3005'}) }
    'Fe26' { $apps = @(@{t='Fe26'; d="$root\Fe26"; b='Bootstrap-Laravel (Get-Location).Path $true'; c='php artisan serve --host=127.0.0.1 --port=8090'}, @{t='Fe26 Vite'; d="$root\Fe26"; b='Bootstrap-Laravel (Get-Location).Path $true'; c='npm run dev -- --host 127.0.0.1 --port 3090'}) }
    'FakeAPI' { $apps = @(@{t='FakeAPI'; d="$root\FakeAPI"; b='Bootstrap-Laravel (Get-Location).Path $false'; c='php artisan serve --host=127.0.0.1 --port=8100'}, @{t='FakeAPI Vite'; d="$root\FakeAPI"; b='Bootstrap-Laravel (Get-Location).Path $false'; c='npm run dev -- --host 127.0.0.1 --port 3100'}) }
    'Fe26-next' { $apps = @(@{t='Fe26 Next'; d="$root\Fe26-next"; b='Bootstrap-Node (Get-Location).Path'; c='npm run dev -- --hostname 127.0.0.1 --port 3000'}) }
    'NorthLore' { $apps = @(@{t='NorthLore'; d="$root\NorthLore"; b='Bootstrap-Node (Get-Location).Path'; c='npm run dev -- --host 127.0.0.1 --port 3001'}) }
    'Zaryan-goldshop' { $apps = @(@{t='Zaryan Goldshop'; d="$root\Zaryan-goldshop"; b='Bootstrap-Node (Get-Location).Path'; c='npm run dev -- --hostname 127.0.0.1 --port 3002'}) }
    'Bedrock' { $apps = @(@{t='Bedrock'; d="$root\Bedrock\bedrock"; b='Bootstrap-Node (Get-Location).Path'; c='npm run dev'}) }
    'ZipLink' { $apps = @(@{t='ZipLink'; d="$root\ZipLink"; b="Require-Command python 'Install Python 3.'"; c='python manage.py migrate; python manage.py runserver 127.0.0.1:8110'}) }
    'n9n' { $apps = @(@{t='n9n infrastructure'; d="$root\n9n"; b='Require-Command docker ''Install Docker Desktop.'''; c='docker compose up -d'}, @{t='n9n development'; d="$root\n9n"; b='Bootstrap-Node (Get-Location).Path ''pnpm'''; c='pnpm dev'}) }
    default { throw "Unknown project '$Project'." }
}

if ($Task) {
    $app = $apps | Where-Object { $_.t -eq $Task } | Select-Object -First 1
    if (-not $app) { throw "Unknown task '$Task' for $Project." }
    Set-Location $app.d
    Invoke-Expression $app.b
    Invoke-Expression $app.c
    exit $LASTEXITCODE
}

foreach ($app in $apps) { Start-Task $app.t $app.d $app.c $app.b }
Write-Host "All $Project processes have been opened in separate windows."
