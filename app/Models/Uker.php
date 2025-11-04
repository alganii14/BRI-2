<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uker extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_sub_kanca',
        'sub_kanca',
        'segment',
        'kode_kanca',
        'kanca',
        'kanwil',
        'kode_kanwil',
    ];
}
