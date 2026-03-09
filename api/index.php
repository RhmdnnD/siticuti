<?php

// 1. PAKSA PHP MENAMPILKAN ERROR KE LAYAR (Agar tidak blank 500)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

// 2. PAKSA LARAVEL MEMINDAHKAN SEMUA CACHE KE /tmp (Sangat penting untuk Laravel 11/12)
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_EVENTS_CACHE=/tmp/events.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');
putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
$_ENV['APP_STORAGE'] = '/tmp/storage';

$app = require_once __DIR__.'/../bootstrap/app.php';

// 3. UBAH PATH STORAGE
$app->useStoragePath('/tmp/storage');

// 4. BUAT FOLDER SEMENTARA
$directories = [
    '/tmp/storage/app',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// 5. JALANKAN APLIKASI
$app->handleRequest(Request::capture());