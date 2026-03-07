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
    public function create()
    {
        $user = auth()->user();
        $jenisCuti = JenisCuti::all();
        $totalSisaCuti = ($user->sisa_cuti_tahun_ini + $user->sisa_cuti_tahun_lalu) - $user->cuti_diambil;
        
        $daftarLibur = HariLibur::pluck('tanggal')->toArray();

        return view('form_pengajuan', compact('user', 'jenisCuti', 'totalSisaCuti', 'daftarLibur'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_cuti' => 'required',
            'alasan' => 'required',
            'tanggal_mulai' => 'required|date|after_or_equal:tomorrow',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alamat' => 'required', 
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $user = Auth::user();

        $cekBentrok = Cuti::where('user_id', $user->id)
            ->where('status', '!=', 'Ditolak')
            ->where('tanggal_mulai', '<=', $request->tanggal_selesai)
            ->where('tanggal_selesai', '>=', $request->tanggal_mulai)
            ->first();

        if ($cekBentrok) {
            return back()->withErrors(['tanggal_mulai' => 'Pengajuan ditolak! Anda sudah memiliki pengajuan cuti ('.$cekBentrok->jenis_cuti.') pada rentang tanggal tersebut.'])->withInput();
        }

        $tanggal_mulai = Carbon::parse($request->tanggal_mulai);
        $tanggal_selesai = Carbon::parse($request->tanggal_selesai);
        
        $daftarLibur = HariLibur::pluck('tanggal')->toArray();
        
        $durasi = 0;
        
        for ($date = $tanggal_mulai->copy(); $date->lte($tanggal_selesai); $date->addDay()) {
            $tanggalString = $date->format('Y-m-d');
            
            if ($date->isWeekday() && !in_array($tanggalString, $daftarLibur)) {
                $durasi++;
            }
        }

        if ($durasi === 0) {
            return back()->withErrors(['tanggal_mulai' => 'Rentang tanggal yang dipilih hanya berisi hari libur akhir pekan atau libur nasional.'])->withInput();
        }

        if ($request->jenis_cuti === 'Cuti Tahunan') {
            $sisaCuti = ($user->sisa_cuti_tahun_ini + $user->sisa_cuti_tahun_lalu) - $user->cuti_diambil;
            
            if ($durasi > $sisaCuti) {
                return back()->withErrors(['tanggal_mulai' => 'Pengajuan ditolak! Durasi cuti ('.$durasi.' hari) melebihi sisa cuti tahunan Anda ('.$sisaCuti.' hari).'])->withInput();
            }
        }

        $namaFileLampiran = null;
        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $namaFileLampiran = $file->storeAs('lampiran', time() . '_' . $file->getClientOriginalName(), 'public');
        }

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

        LogAktivitas::create([
            'user_name' => $user->name,
            'role' => $user->role,
            'aksi' => 'Mengajukan ' . $request->jenis_cuti . ' selama ' . $durasi . ' hari'
        ]);

        return redirect('/dashboard')->with('success', 'Pengajuan cuti berhasil dikirim dan sedang menunggu persetujuan Admin!');
    }

    public function batalkan($id)
    {
        $cuti = Cuti::findOrFail($id);

        if ($cuti->user_id == auth()->id() && $cuti->status == 'Menunggu') {
            $cuti->delete();

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