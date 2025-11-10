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
        'cifno',
        'regional_office',
        'kantor_cabang',
        'unit_kerja',
        'jenis_uker',
        'tanggal_pembukaan_rekening',
        'tanggal_jatuh_tempo',
        'status',
        'segmentasi_bisnis',
        'segmentasi_divisi',
        'tipe_produk',
        'jenis_simpanan',
        'jenis_kartu',
        'kelompok_produk',
        'deskripsi_produk',
        'jangka_waktu',
        'jenis_valuta',
        'saldo_original',
        'saldo_idr',
        'ratas_saldo_marginal',
        'ratas_saldo_historical',
        'beban_bunga',
        'rate_simpanan',
        'pn_customer_service',
        'pn_mantri_rm_dana',
        'pn_rm_pinjaman',
        'pn_rm_merchant',
        'pn_relationship_officer',
        'pn_sales_person',
        'pn_rab',
        'pn_rm_referral',
        'kepemilikan_brimo',
        'referral',
        'status_pekerja',
        'status_cif',
        'referral_code',
        'flag_pn_pengelola_slot_2',
    ];

    public function aktivitas()
    {
        return $this->hasMany(Aktivitas::class);
    }
}
