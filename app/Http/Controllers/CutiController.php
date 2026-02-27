<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuti;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // <-- WAJIB ditambahkan untuk fitur tanggal pintar

class CutiController extends Controller
{
    // Fungsi untuk memproses penyimpanan data cuti
    public function store(Request $request)
    {
        // 1. Validasi inputan dari form
        $request->validate([
            'jenis_cuti' => 'required',
            'alasan' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alamat_cuti' => 'required',
        ]);

        // 2. Hitung durasi (HANYA HARI KERJA: Senin - Jumat)
        $tanggal_mulai = Carbon::parse($request->tanggal_mulai);
        $tanggal_selesai = Carbon::parse($request->tanggal_selesai);
        
        $durasi = 0;
        
        // Looping dari tanggal mulai sampai tanggal selesai
        for ($date = $tanggal_mulai->copy(); $date->lte($tanggal_selesai); $date->addDay()) {
            // isWeekday() akan bernilai true jika hari Senin s/d Jumat
            if ($date->isWeekday()) {
                $durasi++;
            }
        }

        // Jika durasi 0 (misal dia ngajuin cuti pas sabtu-minggu doang), tolak pengajuan
        if ($durasi === 0) {
            return back()->withErrors(['tanggal_mulai' => 'Rentang tanggal yang dipilih hanya berisi hari libur akhir pekan.'])->withInput();
        }

        // 3. Simpan data ke tabel cutis di database
        Cuti::create([
            'user_id' => Auth::id(), // ID ASN yang sedang login
            'jenis_cuti' => $request->jenis_cuti,
            'alasan' => $request->alasan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'durasi' => $durasi,
            'alamat_cuti' => $request->alamat_cuti,
            'status' => 'Menunggu',
        ]);

        // 4. Arahkan kembali ke dashboard dengan pesan sukses
        return redirect('/dashboard')->with('success', 'Pengajuan cuti berhasil dikirim dan sedang menunggu persetujuan!');
    }
}