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
        // Hitung statistik (Real-time dari database)
        $totalAsn = User::where('role', 'asn')->count();
        $menunggu = Cuti::where('status', 'Menunggu')->count();
        // Hitung cuti yang disetujui/ditolak pada tahun ini saja
        $disetujui = Cuti::where('status', 'Disetujui')->whereYear('created_at', date('Y'))->count();
        $ditolak = Cuti::where('status', 'Ditolak')->whereYear('created_at', date('Y'))->count();

        // Ambil data pengajuan yang masih "Menunggu" untuk ditampilkan di tabel
        $pengajuanMenunggu = Cuti::with('user')
            ->where('status', 'Menunggu')
            ->orderBy('created_at', 'asc') // Yang paling lama mengajukan di atas
            ->get();

        // Ambil semua data cuti untuk kebutuhan Export Laporan CSV
        $semuaCuti = Cuti::with('user')->orderBy('created_at', 'desc')->get();

        return view('admin', compact('totalAsn', 'menunggu', 'disetujui', 'ditolak', 'pengajuanMenunggu', 'semuaCuti'));
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

        // Jika disetujui, maka tambahkan jumlah 'cuti_diambil' pada user tersebut
        if ($request->status === 'Disetujui') {
            // Hanya potong jatah cuti jika jenisnya adalah "Cuti Tahunan"
            if ($cuti->jenis_cuti === 'Cuti Tahunan') {
                $user = User::find($cuti->user_id);
                $user->cuti_diambil += $cuti->durasi;
                $user->save();
            }
        }

        // Catat ke Log Aktivitas
        LogAktivitas::create([
            'user_name' => auth()->user()->name,
            'role' => auth()->user()->role,
            'aksi' => $request->status . ' pengajuan ' . $cuti->jenis_cuti . ' dari ' . $cuti->user->name
        ]);

        return back()->with('success', 'Pengajuan cuti berhasil ' . strtolower($request->status) . '!');
    }
}