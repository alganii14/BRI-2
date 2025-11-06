<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenurunanMerchantRitel extends Model
{
    use HasFactory;

    protected $table = 'penurunan_merchant_ritels';

    protected $fillable = [
        'regional_office',
        'kode_cabang_induk',
        'cabang_induk',
        'kode_uker',
        'unit_kerja',
        'cifno',
        'no_rekening',
        'penurunan',
        'product_type',
        'nama_nasabah',
        'segmentasi_bpr',
        'jenis_simpanan',
        'saldo_last_eom',
        'saldo_terupdate',
        'delta',
        'pn_slot_1',
        'pn_slot_2',
        'pn_slot_3',
        'pn_slot_4',
        'pn_slot_5',
        'pn_slot_6',
        'pn_slot_7',
        'pn_slot_8',
    ];
}
