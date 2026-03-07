<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HariLibur;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class HariLiburController extends Controller
{
    public function index()
    {
        $libur = HariLibur::orderBy('tanggal', 'asc')->get();
        return view('manajemen_libur', compact('libur'));
    }

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

    public function destroy($id)
    {
        $libur = HariLibur::findOrFail($id);
        
        LogAktivitas::create([
            'user_name' => Auth::user()->name,
            'role' => Auth::user()->role,
            'aksi' => 'Menghapus Hari Libur: ' . $libur->keterangan
        ]);

        $libur->delete();

        return back()->with('success', 'Hari libur berhasil dihapus!');
    }
}