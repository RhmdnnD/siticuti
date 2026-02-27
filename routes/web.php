<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// --- ROUTE UMUM / ASN ---
Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/dashboard', function () {
    return view('index');
});

Route::get('/pengajuan', function () {
    return view('form_pengajuan');
});

Route::get('/profil', function () {
    return view('profil_asn');
});


// --- ROUTE KHUSUS ADMIN ---
Route::prefix('admin')->group(function () {
    
    // Dashboard Admin (Akses: /admin)
    Route::get('/', function () {
        return view('admin');
    });

    // Manajemen ASN (Akses: /admin/asn)
    Route::get('/asn', function () {
        return view('manajemen_asn');
    });

    // Manajemen Atasan (Akses: /admin/atasan)
    Route::get('/atasan', function () {
        return view('manajemen_atasan');
    });

    // Manajemen Cuti (Akses: /admin/cuti)
    Route::get('/cuti', function () {
        return view('manajemen_cuti');
    });

    // Manajemen Hari Libur (Akses: /admin/libur)
    Route::get('/libur', function () {
        return view('manajemen_libur');
    });

    // Log Aktivitas (Akses: /admin/log)
    Route::get('/log', function () {
        return view('log_aktivitas');
    });
});