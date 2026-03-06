<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogAktivitas;
use Carbon\Carbon;

class LogAktivitasController extends Controller
{
    // Menampilkan halaman Log Aktivitas
    public function index()
    {
        $logs = LogAktivitas::orderBy('created_at', 'desc')->get();
        return view('log_aktivitas', compact('logs'));
    }

    // Membersihkan semua log secara manual
    public function clear()
    {
        LogAktivitas::truncate();
        
        LogAktivitas::create([
            'user_name' => auth()->user()->name,
            'role' => auth()->user()->role,
            'aksi' => 'Membersihkan semua riwayat log aktivitas sistem'
        ]);

        return back()->with('success', 'Semua log aktivitas telah berhasil dibersihkan.');
    }

    // --- FUNGSI BARU: PEMBERSIHAN OTOMATIS (AUTO CLEAN) ---
    public function autoClean(Request $request)
    {
        $months = $request->months;

        // Hanya proses jika nilainya 1, 3, atau 6
        if (in_array($months, ['1', '3', '6'])) {
            // Hitung tanggal batas mundur dari hari ini
            $batasWaktu = Carbon::now()->subMonths((int)$months);
            
            // Hapus secara permanen log yang tanggalnya lebih lama dari batas waktu
            LogAktivitas::where('created_at', '<', $batasWaktu)->delete();
        }

        return response()->json(['message' => 'Pembersihan latar belakang selesai.']);
    }
}