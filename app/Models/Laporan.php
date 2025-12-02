<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'kategori',
        'lokasi',
        'foto',
        'status',
        'pelapor_id'
    ];

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }
}
