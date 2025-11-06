<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Top10QrisPerUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'rank',
        'posisi',
        'mainbr',
        'mbdesc',
        'branch',
        'brdesc',
        'storeid',
        'nama_merchant',
        'no_rek',
        'cif',
        'pn',
        'pn_pemrakasa',
        'akumulasi_sv_total',
        'posisi_sv_total_september',
        'akumulasi_trx_total',
        'posisi_trx_total',
        'saldo_posisi',
        'ratas_saldo',
        'alamat',
    ];
}
