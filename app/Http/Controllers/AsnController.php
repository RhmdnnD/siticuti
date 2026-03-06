<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AsnController extends Controller
{
    public function index()
    {
        $asn = User::where('role', 'asn')->orderBy('name', 'asc')->get();
        
        $dataAtasan = \App\Models\Atasan::orderBy('nama', 'asc')->get();
        
        return view('manajemen_asn', compact('asn', 'dataAtasan'));
    }

    public function store(Request $request)
    {
        // Validasi inputan utama
        $request->validate([
            'name' => 'required',
            'nip' => 'required|unique:users,nip', 
            'username' => 'required|unique:users,username', 
            'password' => 'nullable|min:6',
            'jabatan' => 'required'
        ]);

        // Masukkan semua data dari form ke database
        User::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'username' => $request->username,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password ?? 'password123'), 
            'role' => 'asn',
            'jabatan' => $request->jabatan,
            'pangkat_gol' => $request->pangkat_gol,
            'telepon' => $request->telepon,
            'masa_kerja_tahun' => $request->masa_kerja_tahun ?? 0,
            'masa_kerja_bulan' => $request->masa_kerja_bulan ?? 0,
            'sisa_cuti_tahun_ini' => $request->sisa_cuti_tahun_ini ?? 12,
            'sisa_cuti_tahun_lalu' => $request->sisa_cuti_tahun_lalu ?? 0,
            'atasan1' => $request->atasan1,
            'atasan2' => $request->atasan2,
            'tmt' => $request->tmt,
            'cuti_diambil' => 0 // Awal mula belum mengambil cuti
        ]);

        return back()->with('success', 'Data ASN berhasil ditambahkan! Jika password dikosongkan, otomatis menggunakan "password123".');
    }
    
    // Menghapus data ASN
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'Data ASN berhasil dihapus!');
    }

    // Memperbarui data ASN & Reset Password
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi inputan (Kecuali NIP dan Username miliknya sendiri)
        $request->validate([
            'name' => 'required',
            'nip' => 'required|unique:users,nip,' . $id,
            'username' => 'required|unique:users,username,' . $id,
            'jabatan' => 'required'
        ]);

        // Update data utama
        $user->update([
            'name' => $request->name,
            'nip' => $request->nip,
            'username' => $request->username,
            'jabatan' => $request->jabatan,
            'pangkat_gol' => $request->pangkat_gol,
            'telepon' => $request->telepon,
            'masa_kerja_tahun' => $request->masa_kerja_tahun ?? 0,
            'masa_kerja_bulan' => $request->masa_kerja_bulan ?? 0,
            'sisa_cuti_tahun_ini' => $request->sisa_cuti_tahun_ini ?? 12,
            'sisa_cuti_tahun_lalu' => $request->sisa_cuti_tahun_lalu ?? 0,
            'atasan1' => $request->atasan1,
            'atasan2' => $request->atasan2,
            'tmt' => $request->tmt,
        ]);

        // Jika kolom password diisi oleh Admin, berarti Admin mereset passwordnya
        if ($request->filled('password')) {
            $user->update([
                'password' => \Illuminate\Support\Facades\Hash::make($request->password)
            ]);
        }

        return back()->with('success', 'Data ASN berhasil diperbarui!');
    }
}