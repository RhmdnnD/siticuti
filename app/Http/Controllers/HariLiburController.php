<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HariLibur;

class HariLiburController extends Controller
{
    // Menampilkan halaman manajemen libur
    public function index()
    {
        $libur = HariLibur::orderBy('tanggal', 'asc')->get();
        return view('manajemen_libur', compact('libur'));
    }

    // Menyimpan hari libur baru
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date|unique:hari_liburs,tanggal',
            'keterangan' => 'required'
        ]);

        HariLibur::create([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan
        ]);

        LogAktivitas::create([
            'user_name' => Auth::user()->name,
            'role' => Auth::user()->role,
            'aksi' => 'Menambahkan Hari Libur: ' . $request->keterangan
        ]);

        return back()->with('success', 'Hari libur berhasil ditambahkan!');
    }

    // Menghapus hari libur
    public function destroy($id)
    {
        HariLibur::findOrFail($id)->delete();
        return back()->with('success', 'Hari libur berhasil dihapus!');
    }
}