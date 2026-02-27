<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuti;
use App\Models\HariLibur; // <-- Tambahkan model HariLibur
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CutiController extends Controller
{
    // Fungsi untuk memproses penyimpanan data cuti
    public function store(Request $request)
    {
        // 1. Validasi inputan form
        $request->validate([
            'jenis_cuti' => 'required',
            'alasan' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alamat_cuti' => 'required',
        ]);

        $tanggal_mulai = Carbon::parse($request->tanggal_mulai);
        $tanggal_selesai = Carbon::parse($request->tanggal_selesai);
        
        // 2. Ambil semua tanggal merah dari database dan jadikan array (Contoh: ['2026-08-17', '2026-12-25'])
        $daftarLibur = HariLibur::pluck('tanggal')->toArray();
        
        $durasi = 0;
        
        // 3. Looping untuk menghitung durasi
        for ($date = $tanggal_mulai->copy(); $date->lte($tanggal_selesai); $date->addDay()) {
            $tanggalString = $date->format('Y-m-d'); // Format tanggal ke Y-m-d untuk dicocokkan
            
            // CEK: Jika hari Senin-Jumat (Weekday) DAN BUKAN tanggal merah (Libur Nasional)
            if ($date->isWeekday() && !in_array($tanggalString, $daftarLibur)) {
                $durasi++;
            }
        }

        // 4. Perlindungan 1: Tolak jika durasi 0 (Hanya ngajuin pas sabtu-minggu / hari libur)
        if ($durasi === 0) {
            return back()->withErrors(['tanggal_mulai' => 'Rentang tanggal yang dipilih hanya berisi hari libur akhir pekan atau libur nasional.'])->withInput();
        }

        // 5. Perlindungan 2: Cek Sisa Cuti (Khusus Cuti Tahunan)
        $user = Auth::user();
        if ($request->jenis_cuti === 'Cuti Tahunan') {
            $sisaCuti = ($user->sisa_cuti_tahun_ini + $user->sisa_cuti_tahun_lalu) - $user->cuti_diambil;
            
            // Tolak jika durasi pengajuan lebih besar dari sisa cuti
            if ($durasi > $sisaCuti) {
                return back()->withErrors(['tanggal_mulai' => 'Pengajuan ditolak! Durasi cuti ('.$durasi.' hari) melebihi sisa cuti tahunan Anda ('.$sisaCuti.' hari).'])->withInput();
            }
        }

        // 6. Jika semua aman, simpan ke database
        Cuti::create([
            'user_id' => $user->id,
            'jenis_cuti' => $request->jenis_cuti,
            'alasan' => $request->alasan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'durasi' => $durasi,
            'alamat_cuti' => $request->alamat_cuti,
            'status' => 'Menunggu',
        ]);

        // 7. Arahkan kembali dengan pesan sukses
        return redirect('/dashboard')->with('success', 'Pengajuan cuti berhasil dikirim dan sedang menunggu persetujuan!');
    }
}