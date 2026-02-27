<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuti;
use App\Models\User;

class AdminController extends Controller
{
    // Menampilkan Dashboard Admin
    public function index()
    {
        // Menghitung statistik untuk kartu di atas
        $totalPengajuan = Cuti::count();
        $totalMenunggu = Cuti::where('status', 'Menunggu')->count();
        $totalDisetujui = Cuti::where('status', 'Disetujui')->count();
        $totalDitolak = Cuti::where('status', 'Ditolak')->count();

        // Mengambil daftar pengajuan yang statusnya masih 'Menunggu'
        // Kita gunakan 'with('user')' untuk mengambil relasi data pegawai yang mengajukannya
        $pengajuanMenunggu = Cuti::with('user')
                                 ->where('status', 'Menunggu')
                                 ->orderBy('created_at', 'desc')
                                 ->get();

        return view('admin', compact(
            'totalPengajuan', 'totalMenunggu', 'totalDisetujui', 'totalDitolak', 'pengajuanMenunggu'
        ));
    }

    // Fungsi untuk mengubah status cuti (Setujui / Tolak)
    public function updateStatus(Request $request, $id)
    {
        $cuti = Cuti::findOrFail($id);
        $user = User::findOrFail($cuti->user_id);

        if ($request->action === 'setujui') {
            $cuti->status = 'Disetujui';
            
            // Kurangi sisa cuti jika jenisnya Cuti Tahunan
            if ($cuti->jenis_cuti === 'Cuti Tahunan') {
                $user->cuti_diambil += $cuti->durasi;
                $user->save();
            }
        } elseif ($request->action === 'tolak') {
            $cuti->status = 'Ditolak';
        }

        $cuti->save();

        return back()->with('success', 'Status pengajuan cuti berhasil diperbarui.');
    }
}