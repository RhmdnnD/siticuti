<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogAktivitas;

class LogAktivitasController extends Controller
{
    // Menampilkan halaman log
    public function index()
    {
        $logs = LogAktivitas::orderBy('created_at', 'desc')->get();
        return view('log_aktivitas', compact('logs'));
    }

    // Menghapus semua log aktivitas
    public function clear()
    {
        LogAktivitas::truncate(); // Perintah untuk mengosongkan tabel
        return back()->with('success', 'Semua riwayat log aktivitas berhasil dibersihkan!');
    }
}