<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuti;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data user yang sedang login
        $user = Auth::user();

        // 2. Hitung total sisa cuti (Tahun Ini + Tahun Lalu - Yang Diambil)
        $totalSisaCuti = ($user->sisa_cuti_tahun_ini + $user->sisa_cuti_tahun_lalu) - $user->cuti_diambil;

        // 3. Ambil 5 riwayat pengajuan cuti terbaru milik user ini dari database
        $riwayatCuti = Cuti::where('user_id', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();

        // 4. Kirim data tersebut ke file tampilan index.blade.php
        return view('index', compact('user', 'totalSisaCuti', 'riwayatCuti'));
    }
}