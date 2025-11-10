<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nasabahs', function (Blueprint $table) {
            $table->string('cifno')->nullable()->after('norek');
            $table->string('regional_office')->nullable()->after('cifno');
            $table->string('kantor_cabang')->nullable()->after('regional_office');
            $table->string('unit_kerja')->nullable()->after('kantor_cabang');
            $table->string('jenis_uker')->nullable()->after('unit_kerja');
            $table->date('tanggal_pembukaan_rekening')->nullable()->after('jenis_uker');
            $table->date('tanggal_jatuh_tempo')->nullable()->after('tanggal_pembukaan_rekening');
            $table->string('status')->nullable()->after('tanggal_jatuh_tempo');
            $table->string('segmentasi_bisnis')->nullable()->after('status');
            $table->string('segmentasi_divisi')->nullable()->after('segmentasi_bisnis');
            $table->string('tipe_produk')->nullable()->after('segmentasi_divisi');
            $table->string('jenis_simpanan')->nullable()->after('tipe_produk');
            $table->string('jenis_kartu')->nullable()->after('jenis_simpanan');
            $table->string('kelompok_produk')->nullable()->after('jenis_kartu');
            $table->string('deskripsi_produk')->nullable()->after('kelompok_produk');
            $table->string('jangka_waktu')->nullable()->after('deskripsi_produk');
            $table->string('jenis_valuta')->nullable()->after('jangka_waktu');
            $table->decimal('saldo_original', 20, 2)->nullable()->after('jenis_valuta');
            $table->decimal('saldo_idr', 20, 2)->nullable()->after('saldo_original');
            $table->decimal('ratas_saldo_marginal', 20, 6)->nullable()->after('saldo_idr');
            $table->decimal('ratas_saldo_historical', 20, 6)->nullable()->after('ratas_saldo_marginal');
            $table->decimal('beban_bunga', 20, 6)->nullable()->after('ratas_saldo_historical');
            $table->decimal('rate_simpanan', 20, 6)->nullable()->after('beban_bunga');
            $table->string('pn_customer_service')->nullable()->after('rate_simpanan');
            $table->string('pn_mantri_rm_dana')->nullable()->after('pn_customer_service');
            $table->string('pn_rm_pinjaman')->nullable()->after('pn_mantri_rm_dana');
            $table->string('pn_rm_merchant')->nullable()->after('pn_rm_pinjaman');
            $table->string('pn_relationship_officer')->nullable()->after('pn_rm_merchant');
            $table->string('pn_sales_person')->nullable()->after('pn_relationship_officer');
            $table->string('pn_rab')->nullable()->after('pn_sales_person');
            $table->string('pn_rm_referral')->nullable()->after('pn_rab');
            $table->string('kepemilikan_brimo')->nullable()->after('pn_rm_referral');
            $table->string('referral')->nullable()->after('kepemilikan_brimo');
            $table->string('status_pekerja')->nullable()->after('referral');
            $table->string('status_cif')->nullable()->after('status_pekerja');
            $table->string('referral_code')->nullable()->after('status_cif');
            $table->string('flag_pn_pengelola_slot_2')->nullable()->after('referral_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nasabahs', function (Blueprint $table) {
            $table->dropColumn([
                'cifno', 'regional_office', 'kantor_cabang', 'unit_kerja', 'jenis_uker',
                'tanggal_pembukaan_rekening', 'tanggal_jatuh_tempo', 'status',
                'segmentasi_bisnis', 'segmentasi_divisi', 'tipe_produk', 'jenis_simpanan',
                'jenis_kartu', 'kelompok_produk', 'deskripsi_produk', 'jangka_waktu',
                'jenis_valuta', 'saldo_original', 'saldo_idr', 'ratas_saldo_marginal',
                'ratas_saldo_historical', 'beban_bunga', 'rate_simpanan',
                'pn_customer_service', 'pn_mantri_rm_dana', 'pn_rm_pinjaman',
                'pn_rm_merchant', 'pn_relationship_officer', 'pn_sales_person',
                'pn_rab', 'pn_rm_referral', 'kepemilikan_brimo', 'referral',
                'status_pekerja', 'status_cif', 'referral_code', 'flag_pn_pengelola_slot_2'
            ]);
        });
    }
};
