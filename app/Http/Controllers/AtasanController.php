<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atasan;
use App\Models\User;

class AtasanController extends Controller
{
    // Menampilkan halaman Manajemen Atasan
    public function index()
    {
        $atasan = Atasan::orderBy('nama', 'asc')->get();
        return view('manajemen_atasan', compact('atasan'));
    }

    // Menyimpan data Atasan baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nip' => 'required|unique:atasans,nip',
            'jabatan' => 'required'
        ]);

        Atasan::create([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan
        ]);

        return back()->with('success', 'Data atasan berhasil ditambahkan!');
    }

    // Menghapus data Atasan
    public function destroy($id)
    {
        $atasan = Atasan::findOrFail($id);

        // Proteksi: Cek apakah NIP atasan ini sedang dipakai oleh ASN
        $isUsed = User::where('atasan1', $atasan->nip)->orWhere('atasan2', $atasan->nip)->exists();
        
        if ($isUsed) {
            return back()->withErrors(['error' => 'Tidak dapat menghapus '.$atasan->nama.' karena datanya masih digunakan oleh ASN.']);
        }

        $atasan->delete();
        return back()->with('success', 'Data atasan berhasil dihapus!');
    }
}