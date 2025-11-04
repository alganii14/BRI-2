<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RMFT extends Model
{
    use HasFactory;

    protected $table = 'rmfts';

    protected $fillable = [
        'pernr',
        'completename',
        'jg',
        'esgdesc',
        'kanca',
        'uker_id',
        'uker',
        'uker_tujuan',
        'keterangan',
        'kelompok_jabatan',
    ];

    /**
     * Relasi ke Uker
     */
    public function ukerRelation()
    {
        return $this->belongsTo(Uker::class, 'uker_id');
    }
}
