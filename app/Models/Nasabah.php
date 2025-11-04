<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nasabah extends Model
{
    use HasFactory;

    protected $fillable = [
        'norek',
        'nama_nasabah',
        'segmen_nasabah',
        'kode_kc',
        'nama_kc',
        'kode_uker',
        'nama_uker',
        'alamat',
        'telepon',
    ];

    public function aktivitas()
    {
        return $this->hasMany(Aktivitas::class);
    }
}
