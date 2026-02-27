<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HariLiburController;
use App\Http\Controllers\AsnController;
use App\Http\Controllers\JenisCutiController;
use App\Http\Controllers\AtasanController;
use App\Http\Controllers\LogAktivitasController;

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
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/pengajuan', function () {
        $jenisCuti = \App\Models\JenisCuti::all();
        return view('form_pengajuan', compact('jenisCuti'));
    });

    Route::post('/pengajuan', [CutiController::class, 'store']);

    Route::get('/profil', function () {
        return view('profil_asn');
    });
});

// --- ROUTE KHUSUS ADMIN (WAJIB LOGIN) ---
Route::middleware('auth')->prefix('admin')->group(function () {
    
    Route::get('/', [AdminController::class, 'index']);

    Route::post('/cuti/{id}/status', [AdminController::class, 'updateStatus']);

    Route::get('/asn', [AsnController::class, 'index']);
    Route::post('/asn', [AsnController::class, 'store']);
    Route::get('/asn/{id}/hapus', [AsnController::class, 'destroy']);

    Route::get('/atasan', [AtasanController::class, 'index']);
    Route::post('/atasan', [AtasanController::class, 'store']);
    Route::get('/atasan/{id}/hapus', [AtasanController::class, 'destroy']);

    Route::get('/cuti', [JenisCutiController::class, 'index']);

    Route::get('/libur', [HariLiburController::class, 'index']);
    Route::post('/libur', [HariLiburController::class, 'store']);
    Route::get('/libur/{id}/hapus', [HariLiburController::class, 'destroy']);

    Route::get('/log', [LogAktivitasController::class, 'index']);
    Route::get('/log/clear', [LogAktivitasController::class, 'clear']);
});