<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    use HasFactory;

    // Tambahkan 'lampiran' dan 'alamat' ke dalam array fillable
    protected $fillable = [
        'user_id',
        'jenis_cuti',
        'tanggal_mulai',
        'tanggal_selesai',
        'durasi',
        'alasan',
        'alamat',
        'lampiran', 
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}