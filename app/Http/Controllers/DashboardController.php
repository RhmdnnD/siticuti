<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuti;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Ambil riwayat cuti khusus untuk ASN yang sedang login
        $riwayatCuti = \App\Models\Cuti::where('user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->get();
                        
        $totalSisaCuti = ($user->sisa_cuti_tahun_ini + $user->sisa_cuti_tahun_lalu) - $user->cuti_diambil;

        // --- TAMBAHAN UNTUK KEBUTUHAN CETAK PDF ---
        $jenisCuti = \App\Models\JenisCuti::all();
        $dataAtasan = \App\Models\Atasan::all();

        return view('index', compact('user', 'riwayatCuti', 'totalSisaCuti', 'jenisCuti', 'dataAtasan'));
    }

    // Menampilkan halaman Profil
    public function profil()
    {
        $user = auth()->user();
        return view('profil_asn', compact('user'));
    }

    // Memperbarui data Profil (Telepon & Password)
    public function updateProfil(Request $request)
    {
        // 1. Validasi input (Cukup 1 kali saja di bagian awal)
        $request->validate([
            'telepon' => 'nullable|string|max:20',
            'password' => 'nullable|min:6|confirmed' 
        ]);

        $user = auth()->user();

        // 2. Update telepon
        $user->telepon = $request->telepon;

        // 3. Jika form password diisi, maka update passwordnya
        $pesanLog = 'Memperbarui nomor telepon profil';
        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
            $pesanLog = 'Memperbarui data profil beserta password login';
        }

        /** @var \App\Models\User $user */
        $user->save();

        // 4. Catat ke Log Aktivitas
        \App\Models\LogAktivitas::create([
            'user_name' => $user->name,
            'role' => $user->role,
            'aksi' => $pesanLog
        ]);

        return back()->with('success', 'Data profil Anda berhasil diperbarui!');
    }
}