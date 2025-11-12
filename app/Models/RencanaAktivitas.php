<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaAktivitas extends Model
{
    use HasFactory;

    protected $table = 'rencana_aktivitas';

    protected $fillable = [
        'nama_rencana',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Aktivitas
     */
    public function aktivitas()
    {
        return $this->hasMany(Aktivitas::class, 'rencana_aktivitas_id');
    }
}
