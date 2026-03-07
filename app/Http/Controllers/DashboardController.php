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
        
        $riwayatCuti = \App\Models\Cuti::where('user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->get();
                        
        $totalSisaCuti = ($user->sisa_cuti_tahun_ini + $user->sisa_cuti_tahun_lalu) - $user->cuti_diambil;

        $jenisCuti = \App\Models\JenisCuti::all();
        $dataAtasan = \App\Models\Atasan::all();

        return view('index', compact('user', 'riwayatCuti', 'totalSisaCuti', 'jenisCuti', 'dataAtasan'));
    }

    public function profil()
    {
        $user = auth()->user();
        return view('profil_asn', compact('user'));
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'telepon' => 'nullable|string|max:20',
            'password' => 'nullable|min:6|confirmed' 
        ]);

        $user = auth()->user();

        $user->telepon = $request->telepon;

        $pesanLog = 'Memperbarui nomor telepon profil';
        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
            $pesanLog = 'Memperbarui data profil beserta password login';
        }

        /** @var \App\Models\User $user */
        $user->save();

        \App\Models\LogAktivitas::create([
            'user_name' => $user->name,
            'role' => $user->role,
            'aksi' => $pesanLog
        ]);

        return back()->with('success', 'Data profil Anda berhasil diperbarui!');
    }
}