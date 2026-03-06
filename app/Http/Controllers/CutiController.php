<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuti;
use App\Models\HariLibur; 
use App\Models\JenisCuti;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CutiController extends Controller
{
    // 1. Fungsi untuk menampilkan Form Pengajuan Cuti
    public function create()
    {
        $user = auth()->user();
        $jenisCuti = JenisCuti::all();
        $totalSisaCuti = ($user->sisa_cuti_tahun_ini + $user->sisa_cuti_tahun_lalu) - $user->cuti_diambil;
        
        // AMBIL DATA HARI LIBUR UNTUK DIKIRIM KE JAVASCRIPT
        $daftarLibur = HariLibur::pluck('tanggal')->toArray();

        return view('form_pengajuan', compact('user', 'jenisCuti', 'totalSisaCuti', 'daftarLibur'));
    }

    // 2. Fungsi untuk memproses penyimpanan data cuti
    public function store(Request $request)
    {
        // Validasi inputan form (termasuk upload file lampiran)
        $request->validate([
            'jenis_cuti' => 'required',
            'alasan' => 'required',
            'tanggal_mulai' => 'required|date|after_or_equal:tomorrow',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alamat' => 'required', 
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048' // Maksimal 2MB
        ]);

        $user = Auth::user();

        // --- PERLINDUNGAN 1: CEK TANGGAL TUMPANG TINDIH (OVERLAPPING) ---
        // Mencari apakah user punya cuti yang tanggalnya berpotongan dengan pengajuan baru
        $cekBentrok = Cuti::where('user_id', $user->id)
            ->where('status', '!=', 'Ditolak') // Abaikan cuti yang sudah ditolak admin
            ->where('tanggal_mulai', '<=', $request->tanggal_selesai)
            ->where('tanggal_selesai', '>=', $request->tanggal_mulai)
            ->first();

        // Jika ditemukan tanggal yang bentrok, tolak form!
        if ($cekBentrok) {
            return back()->withErrors(['tanggal_mulai' => 'Pengajuan ditolak! Anda sudah memiliki pengajuan cuti ('.$cekBentrok->jenis_cuti.') pada rentang tanggal tersebut.'])->withInput();
        }

        $tanggal_mulai = Carbon::parse($request->tanggal_mulai);
        $tanggal_selesai = Carbon::parse($request->tanggal_selesai);
        
        // Ambil semua tanggal merah dari database dan jadikan array
        $daftarLibur = HariLibur::pluck('tanggal')->toArray();
        
        $durasi = 0;
        
        // Looping cerdas untuk menghitung durasi (Mengabaikan Sabtu-Minggu & Libur Nasional)
        for ($date = $tanggal_mulai->copy(); $date->lte($tanggal_selesai); $date->addDay()) {
            $tanggalString = $date->format('Y-m-d');
            
            // CEK: Jika hari Senin-Jumat (Weekday) DAN BUKAN tanggal merah
            if ($date->isWeekday() && !in_array($tanggalString, $daftarLibur)) {
                $durasi++;
            }
        }

        // --- PERLINDUNGAN 2: Tolak jika durasi 0 (Hanya ngajuin pas sabtu-minggu / libur) ---
        if ($durasi === 0) {
            return back()->withErrors(['tanggal_mulai' => 'Rentang tanggal yang dipilih hanya berisi hari libur akhir pekan atau libur nasional.'])->withInput();
        }

        // --- PERLINDUNGAN 3: Cek Sisa Cuti (Khusus Cuti Tahunan) ---
        if ($request->jenis_cuti === 'Cuti Tahunan') {
            $sisaCuti = ($user->sisa_cuti_tahun_ini + $user->sisa_cuti_tahun_lalu) - $user->cuti_diambil;
            
            if ($durasi > $sisaCuti) {
                return back()->withErrors(['tanggal_mulai' => 'Pengajuan ditolak! Durasi cuti ('.$durasi.' hari) melebihi sisa cuti tahunan Anda ('.$sisaCuti.' hari).'])->withInput();
            }
        }

        // Proses Upload Lampiran (Jika user mengunggah file)
        $namaFileLampiran = null;
        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            // Simpan ke folder: storage/app/public/lampiran
            $namaFileLampiran = $file->storeAs('lampiran', time() . '_' . $file->getClientOriginalName(), 'public');
        }

        // Jika semua aman, simpan ke database
        Cuti::create([
            'user_id' => $user->id,
            'jenis_cuti' => $request->jenis_cuti,
            'alasan' => $request->alasan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'durasi' => $durasi,
            'alamat' => $request->alamat,
            'lampiran' => $namaFileLampiran, 
            'status' => 'Menunggu',
        ]);

        // Catat ke log aktivitas
        LogAktivitas::create([
            'user_name' => $user->name,
            'role' => $user->role,
            'aksi' => 'Mengajukan ' . $request->jenis_cuti . ' selama ' . $durasi . ' hari'
        ]);

        // Arahkan kembali dengan pesan sukses
        return redirect('/dashboard')->with('success', 'Pengajuan cuti berhasil dikirim dan sedang menunggu persetujuan Admin!');
    }

    // 3. Fungsi untuk membatalkan pengajuan cuti ASN
    public function batalkan($id)
    {
        $cuti = Cuti::findOrFail($id);

        // Keamanan: Pastikan cuti milik user yang sedang login & status masih Menunggu
        if ($cuti->user_id == auth()->id() && $cuti->status == 'Menunggu') {
            $cuti->delete();

            // Catat ke log aktivitas
            LogAktivitas::create([
                'user_name' => auth()->user()->name,
                'role' => auth()->user()->role,
                'aksi' => 'Membatalkan pengajuan ' . $cuti->jenis_cuti
            ]);

            return back()->with('success', 'Pengajuan cuti Anda berhasil dibatalkan dan dihapus.');
        }

        return back()->withErrors(['error' => 'Cuti tidak dapat dibatalkan karena sudah diproses oleh Admin.']);
    }
}