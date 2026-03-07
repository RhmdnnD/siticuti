<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogAktivitas;
use Carbon\Carbon;

class LogAktivitasController extends Controller
{
    public function index()
    {
        $logs = LogAktivitas::orderBy('created_at', 'desc')->get();
        return view('log_aktivitas', compact('logs'));
    }

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

    public function autoClean(Request $request)
    {
        $months = $request->months;

        if (in_array($months, ['1', '3', '6'])) {
            $batasWaktu = Carbon::now()->subMonths((int)$months);
            
            LogAktivitas::where('created_at', '<', $batasWaktu)->delete();
        }

        return response()->json(['message' => 'Pembersihan latar belakang selesai.']);
    }
}