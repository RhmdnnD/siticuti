<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat Akun Admin
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('admin123'), // Password admin
            'role' => 'admin',
        ]);

        // Membuat Akun ASN Contoh
        User::create([
            'name' => 'Budi Santoso',
            'username' => 'budi',
            'nip' => '199001012023011001',
            'password' => Hash::make('asn123'), // Password ASN
            'role' => 'asn',
            'jabatan' => 'Staf Teknis Lingkungan',
            'pangkat_gol' => 'Penata Muda / III.a',
            'sisa_cuti_tahun_ini' => 12,
        ]);
    }
}