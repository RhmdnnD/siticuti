<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom ini diisi
    protected $fillable = [
        'user_id',
        'jenis_cuti',
        'alasan',
        'tanggal_mulai',
        'tanggal_selesai',
        'durasi',
        'alamat_cuti',
        'status',
    ];

    // Relasi: Cuti ini milik seorang User (ASN)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}