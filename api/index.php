<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel...
$app = require_once __DIR__.'/../bootstrap/app.php';

// -------------------------------------------------------------------
// TRIK VERCEL: Pindahkan semua Storage ke folder /tmp yang diizinkan
// -------------------------------------------------------------------
$app->useStoragePath('/tmp/storage');

$directories = [
    '/tmp/storage/app',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
];

// Buat foldernya secara otomatis jika belum ada
foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Tangani Request...
$app->handleRequest(Request::capture());