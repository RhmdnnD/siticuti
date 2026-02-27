<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// --- ROUTE GUEST (HANYA UNTUK YANG BELUM LOGIN) ---
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// --- ROUTE ASN (WAJIB LOGIN) ---
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('index');
    });

    Route::get('/pengajuan', function () {
        return view('form_pengajuan');
    });

    Route::get('/profil', function () {
        return view('profil_asn');
    });
});

// --- ROUTE KHUSUS ADMIN (WAJIB LOGIN) ---
Route::middleware('auth')->prefix('admin')->group(function () {
    
    Route::get('/', function () {
        return view('admin');
    });

    Route::get('/asn', function () {
        return view('manajemen_asn');
    });

    Route::get('/atasan', function () {
        return view('manajemen_atasan');
    });

    Route::get('/cuti', function () {
        return view('manajemen_cuti');
    });

    Route::get('/libur', function () {
        return view('manajemen_libur');
    });

    Route::get('/log', function () {
        return view('log_aktivitas');
    });
});