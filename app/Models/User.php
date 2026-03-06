<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username', // <-- Tambahkan ini
        'nip',      // <-- Tambahkan ini
        'password',
        'role',
        'jabatan',
        'pangkat_gol',
        'telepon',
        'tmt',
        'masa_kerja_tahun',
        'masa_kerja_bulan',
        'sisa_cuti_tahun_ini',
        'sisa_cuti_tahun_lalu',
        'cuti_diambil',
        'atasan1',
        'atasan2',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Membuat atribut virtual bernama 'masa_kerja_total'
    public function getMasaKerjaTotalAttribute()
    {
        // Jika TMT kosong, langsung kembalikan masa kerja awal
        if (!$this->tmt) {
            return "{$this->masa_kerja_tahun} thn, {$this->masa_kerja_bulan} bln";
        }

        $tmt = \Carbon\Carbon::parse($this->tmt);
        $targetDate = \Carbon\Carbon::parse('2025-08-01');

        if ($tmt->gt($targetDate)) {
            return "{$this->masa_kerja_tahun} thn, {$this->masa_kerja_bulan} bln";
        }

        // 1. Hitung selisih bulan dari TMT ke Target Date LALU DITAMBAH 1 (Sesuai Excel)
        $diffTotalMonths = ($targetDate->year - $tmt->year) * 12 + ($targetDate->month - $tmt->month);
        $tmtMonths = $diffTotalMonths + 1;

        // 2. Hitung total masa kerja awal (dalam bulan)
        $initialMonths = ($this->masa_kerja_tahun * 12) + $this->masa_kerja_bulan;

        // 3. Gabungkan semuanya
        $finalTotalMonths = $tmtMonths + $initialMonths;

        // 4. Konversi kembali ke Tahun dan Bulan
        $finalYears = floor($finalTotalMonths / 12);
        $finalMonths = $finalTotalMonths % 12;

        return "{$finalYears} thn, {$finalMonths} bln";
    }
}
