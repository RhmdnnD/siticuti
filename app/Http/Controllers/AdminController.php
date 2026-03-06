<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cuti;
use App\Models\LogAktivitas;

class AdminController extends Controller
{
    // 1. Menampilkan Halaman Dashboard Admin
    public function index()
    {
        $totalAsn = User::where('role', 'asn')->count();
        $menunggu = Cuti::where('status', 'Menunggu')->count();
        $disetujui = Cuti::where('status', 'Disetujui')->whereYear('created_at', date('Y'))->count();
        $ditolak = Cuti::where('status', 'Ditolak')->whereYear('created_at', date('Y'))->count();

        $pengajuanMenunggu = Cuti::with('user')
            ->where('status', 'Menunggu')
            ->orderBy('created_at', 'asc') 
            ->get();

        // TAMBAHAN: Ambil data cuti yang sudah disetujui (untuk ditampilkan dan bisa dibatalkan)
        $pengajuanDisetujui = Cuti::with('user')
            ->where('status', 'Disetujui')
            ->orderBy('updated_at', 'desc') // Paling baru disetujui di atas
            ->take(20) // Batasi 20 terbaru agar tabel tidak terlalu panjang
            ->get();

        $semuaCuti = Cuti::with('user')->orderBy('created_at', 'desc')->get();

        return view('admin', compact('totalAsn', 'menunggu', 'disetujui', 'ditolak', 'pengajuanMenunggu', 'pengajuanDisetujui', 'semuaCuti'));
    }

    // 2. Fungsi untuk Menyetujui atau Menolak Cuti
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak'
        ]);

        $cuti = Cuti::with('user')->findOrFail($id);
        $cuti->status = $request->status;
        $cuti->save();

        if ($request->status === 'Disetujui') {
            if ($cuti->jenis_cuti === 'Cuti Tahunan') {
                $user = User::find($cuti->user_id);
                $user->cuti_diambil += $cuti->durasi;
                $user->save();
            }
        }

        LogAktivitas::create([
            'user_name' => auth()->user()->name,
            'role' => auth()->user()->role,
            'aksi' => $request->status . ' pengajuan ' . $cuti->jenis_cuti . ' dari ' . $cuti->user->name
        ]);

        return back()->with('success', 'Pengajuan cuti berhasil ' . strtolower($request->status) . '!');
    }

    // 3. FITUR BARU: Fungsi untuk Membatalkan Cuti yang Sudah Disetujui (Refund Sisa Cuti)
    public function batalkanPersetujuan($id)
    {
        $cuti = Cuti::with('user')->findOrFail($id);

        if ($cuti->status === 'Disetujui') {
            // Jika jenisnya Cuti Tahunan, KEMBALIKAN jatahnya! (Kurangi cuti_diambil)
            if ($cuti->jenis_cuti === 'Cuti Tahunan') {
                $user = User::find($cuti->user_id);
                $user->cuti_diambil -= $cuti->durasi;
                
                // Mencegah nilai minus jika ada anomali data
                if ($user->cuti_diambil < 0) {
                    $user->cuti_diambil = 0;
                }
                $user->save();
            }

            // UBAH KE 'Ditolak' AGAR DATABASE MENERIMA DAN STATISTIK DASHBOARD AKURAT
            $cuti->status = 'Ditolak';
            $cuti->save();

            LogAktivitas::create([
                'user_name' => auth()->user()->name,
                'role' => auth()->user()->role,
                'aksi' => 'Membatalkan persetujuan ' . $cuti->jenis_cuti . ' dari ' . $cuti->user->name . ' (Sisa Cuti Dikembalikan)'
            ]);

            return back()->with('success', 'Persetujuan cuti dianulir/dibatalkan. Sisa cuti tahunan pegawai otomatis dikembalikan!');
        }

        return back()->with('error', 'Cuti tidak dapat dibatalkan karena statusnya bukan Disetujui.');
    }
}