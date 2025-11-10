@extends('layouts.app')

@section('title', 'Tambah Aktivitas')
@section('page-title', 'Tambah Aktivitas')

@section('content')
<style>
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #667eea;
    }

    .form-group input:disabled {
        background-color: #f5f5f5;
        cursor: not-allowed;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .btn {
        padding: 10px 24px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }

    .section-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px 20px;
        border-radius: 8px 8px 0 0;
        margin: 24px -24px 20px -24px;
        font-size: 16px;
        font-weight: 600;
    }

    .section-header:first-child {
        margin-top: -24px;
    }
</style>

<div class="card">
    <div class="section-header">
       Data RMFT 
    </div>

    @if ($errors->any())
    <div style="background-color: #fee; border: 1px solid #fcc; border-radius: 6px; padding: 12px; margin-bottom: 20px; color: #c33;">
        <strong>Error:</strong>
        <ul style="margin: 8px 0 0 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('aktivitas.store') }}" method="POST">
        @csrf

        @if(auth()->user()->isManager() || auth()->user()->isAdmin())
        <!-- Manager dan Admin memilih RMFT -->
        <div class="form-group">
            <label>PILIH RMFT <span style="color: red;">*</span></label>
            <select name="rmft_select" id="rmft_select" required onchange="fillRMFTData(this.value)">
                <option value="">-- Pilih RMFT --</option>
                @foreach($rmftList as $rmft)
                    @php
                        $rmftRecord = $rmft->rmftData;
                        $ukerRecord = $rmftRecord ? $rmftRecord->ukerRelation : null;
                    @endphp
                <option value="{{ $rmft->id }}" 
                        data-rmft-id="{{ $rmftRecord ? $rmftRecord->id : '' }}"
                        data-name="{{ $rmft->name }}"
                        data-pernr="{{ $rmft->pernr }}"
                        data-kode-kc="{{ $ukerRecord ? $ukerRecord->kode_kanca : '' }}"
                        data-kanca="{{ $rmftRecord ? $rmftRecord->kanca : '' }}"
                        data-kode-uker="{{ $ukerRecord ? $ukerRecord->kode_sub_kanca : '' }}"
                        data-uker="{{ $ukerRecord ? $ukerRecord->sub_kanca : '' }}"
                        data-kelompok="{{ $rmftRecord ? $rmftRecord->kelompok_jabatan : '' }}">
                    {{ $rmft->name }} ({{ $rmft->pernr }}) - {{ $rmftRecord ? $rmftRecord->kanca : 'N/A' }}
                </option>
                @endforeach
            </select>
        </div>

        <input type="hidden" name="rmft_id" id="rmft_id_input" required>
        @elseif(auth()->user()->isRMFT())
        <!-- RMFT otomatis terisi -->
        <input type="hidden" name="rmft_id" value="{{ optional($rmftData)->id }}">
        @endif

        <div class="form-row">
            <div class="form-group">
                <label>NO</label>
                <input type="text" value="Auto" disabled>
            </div>

            <div class="form-group">
                <label>TANGGAL <span style="color: red;">*</span></label>
                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
            </div>

            <div class="form-group">
                <label>NAMA RMFT</label>
                <input type="text" id="nama_rmft" name="nama_rmft" value="{{ old('nama_rmft', optional($rmftData)->completename ?? '') }}" readonly required>
            </div>

            <div class="form-group">
                <label>PN</label>
                <input type="text" id="pn" name="pn" value="{{ old('pn', optional($rmftData)->pernr ?? '') }}" readonly required>
            </div>

            <div class="form-group">
                <label>KODE KC</label>
                <input type="text" id="kode_kc" name="kode_kc" value="{{ old('kode_kc', optional($rmftData)->ukerRelation->kode_kanca ?? '') }}" readonly required>
            </div>

            <div class="form-group">
                <label>NAMA KC</label>
                <input type="text" id="nama_kc" name="nama_kc" value="{{ old('nama_kc', optional($rmftData)->kanca ?? '') }}" readonly required>
            </div>

            <div class="form-group">
                <label>KODE UKER</label>
                <textarea id="kode_uker_display" readonly style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; min-height: 60px; resize: vertical; background-color: #f5f5f5; font-family: inherit;" placeholder="Kode unit yang dipilih akan muncul di sini">{{ old('kode_uker', optional($rmftData)->ukerRelation->kode_sub_kanca ?? '') }}</textarea>
                <input type="hidden" id="kode_uker" name="kode_uker" value="{{ old('kode_uker', optional($rmftData)->ukerRelation->kode_sub_kanca ?? '') }}" required>
            </div>

            <div class="form-group" id="nama_uker_group">
                <label>NAMA UKER <span id="unit_selector_label" style="color: #667eea; display: none;">(Klik untuk pilih unit)</span></label>
                <div style="position: relative;">
                    <textarea id="nama_uker_display" readonly style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; min-height: 60px; resize: vertical; background-color: #f5f5f5; font-family: inherit;" placeholder="Unit yang dipilih akan muncul di sini">{{ old('nama_uker', optional($rmftData)->ukerRelation->sub_kanca ?? '') }}</textarea>
                    <input type="hidden" id="nama_uker" name="nama_uker" value="{{ old('nama_uker', optional($rmftData)->ukerRelation->sub_kanca ?? '') }}" required>
                    <input type="hidden" id="kode_uker_list" name="kode_uker_list" value="">
                    <input type="hidden" id="nama_uker_list" name="nama_uker_list" value="">
                </div>
                <input type="hidden" id="is_unit_rmft" value="0">
                <input type="hidden" id="rmft_kode_kc" value="">
            </div>

            <div class="form-group">
                <label>KELOMPOK</label>
                <input type="text" id="kelompok" name="kelompok" value="{{ old('kelompok', optional($rmftData)->kelompok_jabatan ?? '') }}" readonly required>
            </div>
        </div>

        <div class="section-header">
            Data Aktivitas
        </div>

        <div class="form-group">
            <label>RENCANA AKTIVITAS <span style="color: red;">*</span></label>
            <select name="rencana_aktivitas" required>
                <option value="">Pilih Rencana Aktivitas</option>
                <option value="DANA MASUK TABUNGAN (Rp)" {{ old('rencana_aktivitas') == 'DANA MASUK TABUNGAN (Rp)' ? 'selected' : '' }}>DANA MASUK TABUNGAN (Rp)</option>
                <option value="PICKUP SERVICE (RP)" {{ old('rencana_aktivitas') == 'PICKUP SERVICE (RP)' ? 'selected' : '' }}>PICKUP SERVICE (RP)</option>
                <option value="DANA MASUK GIRO (Rp)" {{ old('rencana_aktivitas') == 'DANA MASUK GIRO (Rp)' ? 'selected' : '' }}>DANA MASUK GIRO (Rp)</option>
                <option value="DANA MASUK DEPO (Rp)" {{ old('rencana_aktivitas') == 'DANA MASUK DEPO (Rp)' ? 'selected' : '' }}>DANA MASUK DEPO (Rp)</option>
                <option value="BRICUAN (Rp)" {{ old('rencana_aktivitas') == 'BRICUAN (Rp)' ? 'selected' : '' }}>BRICUAN (Rp)</option>
                <option value="EXTRA REWARD PRIO (Rp)" {{ old('rencana_aktivitas') == 'EXTRA REWARD PRIO (Rp)' ? 'selected' : '' }}>EXTRA REWARD PRIO (Rp)</option>
                <option value="BOOSTER DEPO (Rp)" {{ old('rencana_aktivitas') == 'BOOSTER DEPO (Rp)' ? 'selected' : '' }}>BOOSTER DEPO (Rp)</option>
                <option value="PANEN EXTRA TAB (Rp)" {{ old('rencana_aktivitas') == 'PANEN EXTRA TAB (Rp)' ? 'selected' : '' }}>PANEN EXTRA TAB (Rp)</option>
                <option value="BRING BACK FUND (Rp)" {{ old('rencana_aktivitas') == 'BRING BACK FUND (Rp)' ? 'selected' : '' }}>BRING BACK FUND (Rp)</option>
                <option value="CASA BRILINK (Rp)" {{ old('rencana_aktivitas') == 'CASA BRILINK (Rp)' ? 'selected' : '' }}>CASA BRILINK (Rp)</option>
                <option value="DISBURSEMENT KREDIT MIKRO (Rp)" {{ old('rencana_aktivitas') == 'DISBURSEMENT KREDIT MIKRO (Rp)' ? 'selected' : '' }}>DISBURSEMENT KREDIT MIKRO (Rp)</option>
                <option value="NASI KUNING (Rp)" {{ old('rencana_aktivitas') == 'NASI KUNING (Rp)' ? 'selected' : '' }}>NASI KUNING (Rp)</option>
                <option value="MERCY (Rp)" {{ old('rencana_aktivitas') == 'MERCY (Rp)' ? 'selected' : '' }}>MERCY (Rp)</option>
                <option value="SHL SMT II (Rp)" {{ old('rencana_aktivitas') == 'SHL SMT II (Rp)' ? 'selected' : '' }}>SHL SMT II (Rp)</option>
                <option value="AKURASI (Jumlah Rek)" {{ old('rencana_aktivitas') == 'AKURASI (Jumlah Rek)' ? 'selected' : '' }}>AKURASI (Jumlah Rek)</option>
                <option value="REK VQ (Jumlah Rek)" {{ old('rencana_aktivitas') == 'REK VQ (Jumlah Rek)' ? 'selected' : '' }}>REK VQ (Jumlah Rek)</option>
                <option value="REK H3 (Jumlah Rek)" {{ old('rencana_aktivitas') == 'REK H3 (Jumlah Rek)' ? 'selected' : '' }}>REK H3 (Jumlah Rek)</option>
                <option value="Akuisisi EDC/ QRIS (Jumlah)" {{ old('rencana_aktivitas') == 'Akuisisi EDC/ QRIS (Jumlah)' ? 'selected' : '' }}>Akuisisi EDC/ QRIS (Jumlah)</option>
                <option value="brifest spbu baraya (Jumlah)" {{ old('rencana_aktivitas') == 'brifest spbu baraya (Jumlah)' ? 'selected' : '' }}>brifest spbu baraya (Jumlah)</option>
                <option value="lucky ride (Jumlah)" {{ old('rencana_aktivitas') == 'lucky ride (Jumlah)' ? 'selected' : '' }}>lucky ride (Jumlah)</option>
                <option value="Menyala Agenku (Jumlah)" {{ old('rencana_aktivitas') == 'Menyala Agenku (Jumlah)' ? 'selected' : '' }}>Menyala Agenku (Jumlah)</option>
                <option value="Agen Ngebutz (Jumlah)" {{ old('rencana_aktivitas') == 'Agen Ngebutz (Jumlah)' ? 'selected' : '' }}>Agen Ngebutz (Jumlah)</option>
                <option value="Akuisisi Incoming (Jumlah)" {{ old('rencana_aktivitas') == 'Akuisisi Incoming (Jumlah)' ? 'selected' : '' }}>Akuisisi Incoming (Jumlah)</option>
                <option value="Akuisisi Hotspot (Jumlah)" {{ old('rencana_aktivitas') == 'Akuisisi Hotspot (Jumlah)' ? 'selected' : '' }}>Akuisisi Hotspot (Jumlah)</option>
                <option value="Giro Pareto Belum EDC (Jumlah)" {{ old('rencana_aktivitas') == 'Giro Pareto Belum EDC (Jumlah)' ? 'selected' : '' }}>Giro Pareto Belum EDC (Jumlah)</option>
                <option value="Giro Reward (Jumlah)" {{ old('rencana_aktivitas') == 'Giro Reward (Jumlah)' ? 'selected' : '' }}>Giro Reward (Jumlah)</option>
                <option value="Suku Bunga Nego Giro (Jumlah)" {{ old('rencana_aktivitas') == 'Suku Bunga Nego Giro (Jumlah)' ? 'selected' : '' }}>Suku Bunga Nego Giro (Jumlah)</option>
                <option value="Cross Sell Perusahaan Anak" {{ old('rencana_aktivitas') == 'Cross Sell Perusahaan Anak' ? 'selected' : '' }}>Cross Sell Perusahaan Anak</option>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>SEGMEN NASABAH <span style="color: red;">*</span></label>
                <select name="segmen_nasabah" required>
                    <option value="">Pilih Segmen</option>
                    <option value="Ritel Badan Usaha" {{ old('segmen_nasabah') == 'Ritel Badan Usaha' ? 'selected' : '' }}>Ritel Badan Usaha</option>
                    <option value="SME" {{ old('segmen_nasabah') == 'SME' ? 'selected' : '' }}>SME</option>
                    <option value="Konsumer" {{ old('segmen_nasabah') == 'Konsumer' ? 'selected' : '' }}>Konsumer</option>
                    <option value="Prioritas" {{ old('segmen_nasabah') == 'Prioritas' ? 'selected' : '' }}>Prioritas</option>
                    <option value="Merchant" {{ old('segmen_nasabah') == 'Merchant' ? 'selected' : '' }}>Merchant</option>
                    <option value="Agen Brilink" {{ old('segmen_nasabah') == 'Agen Brilink' ? 'selected' : '' }}>Agen Brilink</option>
                    <option value="Mikro" {{ old('segmen_nasabah') == 'Mikro' ? 'selected' : '' }}>Mikro</option>
                    <option value="Komersial" {{ old('segmen_nasabah') == 'Komersial' ? 'selected' : '' }}>Komersial</option>
                </select>
            </div>

            <div class="form-group">
                <label>CIFNO <span style="color: red;">*</span></label>
                <div style="position: relative;">
                    <input type="text" id="norek" name="norek" value="{{ old('norek') }}" required placeholder="CIFNO nasabah" autocomplete="off" style="padding-right: 45px;">
                    <button type="button" onclick="openNasabahModal()" style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-size: 12px;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label>NAMA NASABAH <span style="color: red;">*</span></label>
                <input type="text" id="nama_nasabah" name="nama_nasabah" value="{{ old('nama_nasabah') }}" required placeholder="Nama lengkap nasabah">
            </div>

            <div class="form-group">
                <label>RP / JUMLAH <span style="color: red;">*</span></label>
                <input type="text" name="rp_jumlah" value="{{ old('rp_jumlah') }}" required placeholder="Contoh: 10000000">
            </div>
        </div>

        <div class="form-group">
            <label>KETERANGAN</label>
            <textarea name="keterangan" rows="3" placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Aktivitas</button>
            <a href="{{ route('aktivitas.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<!-- Modal Nasabah -->
<div id="nasabahModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 12px; width: 90%; max-width: 800px; max-height: 80vh; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
        <div style="padding: 20px; border-bottom: 2px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h3 style="margin: 0; color: white;">Pilih Nasabah</h3>
            <button onclick="closeNasabahModal()" style="background: none; border: none; color: white; font-size: 24px; cursor: pointer; padding: 0; width: 30px; height: 30px;">&times;</button>
        </div>
        
        <div style="padding: 20px;">
            <input type="text" id="searchNasabah" placeholder="Cari CIFNO atau nama nasabah..." style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; margin-bottom: 15px;" onkeyup="searchNasabahList()">
            
            <div id="nasabahList" style="max-height: 400px; overflow-y: auto;">
                <div style="text-align: center; padding: 40px; color: #666;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 48px; height: 48px; margin: 0 auto 16px; opacity: 0.3;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <p>Gunakan search untuk mencari nasabah</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Unit Selection -->
<div id="unitModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 12px; width: 90%; max-width: 600px; max-height: 80vh; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
        <div style="padding: 20px; border-bottom: 2px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h3 style="margin: 0; color: white;">Pilih Unit di <span id="modal_kc_name"></span></h3>
            <button onclick="closeUnitModal()" style="background: none; border: none; color: white; font-size: 24px; cursor: pointer; padding: 0; width: 30px; height: 30px;">&times;</button>
        </div>
        
        <div style="padding: 20px;">
            <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                <input type="text" id="searchUnit" placeholder="Cari nama unit..." style="flex: 1; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px;" onkeyup="filterUnitList()">
                <button onclick="selectAllUnits()" style="padding: 10px 16px; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer; white-space: nowrap;">Pilih Semua</button>
                <button onclick="clearAllUnits()" style="padding: 10px 16px; background: #dc3545; color: white; border: none; border-radius: 6px; cursor: pointer; white-space: nowrap;">Hapus Semua</button>
            </div>
            
            <div id="selected_count" style="margin-bottom: 10px; padding: 8px 12px; background: #e3f2fd; border-radius: 6px; color: #1976d2; font-size: 13px; font-weight: 600;">
                <span id="count_text">0 unit dipilih</span>
            </div>
            
            <div id="unitList" style="max-height: 350px; overflow-y: auto; border: 1px solid #ddd; border-radius: 6px; padding: 10px;">
                <div style="text-align: center; padding: 40px; color: #666;">
                    <p>Memuat daftar unit...</p>
                </div>
            </div>
            
            <div style="margin-top: 15px; display: flex; gap: 10px; justify-content: flex-end;">
                <button onclick="closeUnitModal()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer;">Batal</button>
                <button onclick="applySelectedUnits()" style="padding: 10px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Terapkan Pilihan</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to fill RMFT data when Manager selects RMFT
    function fillRMFTData(rmftUserId) {
        const select = document.getElementById('rmft_select');
        const option = select.options[select.selectedIndex];
        
        if (!option.value) {
            // Clear all fields
            document.getElementById('rmft_id_input').value = '';
            document.getElementById('nama_rmft').value = '';
            document.getElementById('pn').value = '';
            document.getElementById('kode_kc').value = '';
            document.getElementById('nama_kc').value = '';
            document.getElementById('kode_uker').value = '';
            document.getElementById('kode_uker_display').value = '';
            document.getElementById('nama_uker').value = '';
            document.getElementById('nama_uker_display').value = '';
            document.getElementById('kelompok').value = '';
            document.getElementById('is_unit_rmft').value = '0';
            document.getElementById('unit_selector_label').style.display = 'none';
            document.getElementById('nama_uker_display').style.cursor = 'text';
            document.getElementById('nama_uker_display').onclick = null;
            document.getElementById('kode_uker_display').style.cursor = 'text';
            document.getElementById('kode_uker_display').onclick = null;
            return;
        }
        
        // Fill form with selected RMFT data
        document.getElementById('rmft_id_input').value = option.dataset.rmftId;
        document.getElementById('nama_rmft').value = option.dataset.name;
        document.getElementById('pn').value = option.dataset.pernr;
        document.getElementById('kode_kc').value = option.dataset.kodeKc;
        document.getElementById('nama_kc').value = option.dataset.kanca;
        document.getElementById('kode_uker').value = option.dataset.kodeUker;
        document.getElementById('kode_uker_display').value = option.dataset.kodeUker;
        
        // Simpan kode KC untuk filter unit
        document.getElementById('rmft_kode_kc').value = option.dataset.kodeKc;
        
        // Cek apakah RMFT ini adalah Unit RMFT (ukernya mengandung kata "UNIT")
        const ukerName = option.dataset.uker.toUpperCase();
        if (ukerName.includes('UNIT')) {
            document.getElementById('is_unit_rmft').value = '1';
            document.getElementById('unit_selector_label').style.display = 'inline';
            document.getElementById('nama_uker_display').style.cursor = 'pointer';
            document.getElementById('nama_uker_display').style.backgroundColor = '#f0f8ff';
            document.getElementById('nama_uker_display').title = 'Klik untuk memilih unit di KC ini';
            document.getElementById('nama_uker_display').onclick = openUnitModal;
            document.getElementById('kode_uker_display').style.cursor = 'pointer';
            document.getElementById('kode_uker_display').style.backgroundColor = '#f0f8ff';
            document.getElementById('kode_uker_display').title = 'Klik untuk memilih unit di KC ini';
            document.getElementById('kode_uker_display').onclick = openUnitModal;
            
            // Set default value
            document.getElementById('nama_uker').value = option.dataset.uker;
            document.getElementById('nama_uker_display').value = option.dataset.uker;
            document.getElementById('kelompok').value = option.dataset.kelompok;
            
            // Reset selections
            selectedUnits = [{
                kode_sub_kanca: option.dataset.kodeUker,
                sub_kanca: option.dataset.uker
            }];
        } else {
            document.getElementById('is_unit_rmft').value = '0';
            document.getElementById('unit_selector_label').style.display = 'none';
            document.getElementById('nama_uker_display').style.cursor = 'text';
            document.getElementById('nama_uker_display').style.backgroundColor = '#f5f5f5';
            document.getElementById('nama_uker_display').title = '';
            document.getElementById('nama_uker_display').onclick = null;
            document.getElementById('kode_uker_display').style.cursor = 'text';
            document.getElementById('kode_uker_display').style.backgroundColor = '#f5f5f5';
            document.getElementById('kode_uker_display').title = '';
            document.getElementById('kode_uker_display').onclick = null;
            document.getElementById('nama_uker').value = option.dataset.uker;
            document.getElementById('nama_uker_display').value = option.dataset.uker;
            document.getElementById('kelompok').value = option.dataset.kelompok;
            selectedUnits = [];
        }
    }

    // Autocomplete for Norek
    const norekInput = document.getElementById('norek');
    const namaNasabahInput = document.getElementById('nama_nasabah');
    const segmenNasabahSelect = document.querySelector('select[name="segmen_nasabah"]');
    
    let debounceTimer;
    
    norekInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const norek = this.value;
        
        if (norek.length < 3) {
            return;
        }
        
        debounceTimer = setTimeout(() => {
            // Get KC and Unit from form
            const kodeKc = document.getElementById('kode_kc').value;
            const isUnitRmft = document.getElementById('is_unit_rmft').value;
            
            // Jika multiple units dipilih, gunakan semua unit
            let kodeUker = '';
            if (isUnitRmft === '1' && selectedUnits.length > 0) {
                // Gunakan semua unit yang dipilih
                kodeUker = selectedUnits.map(u => u.kode_sub_kanca).join(',');
            } else {
                // Gunakan single unit
                kodeUker = document.getElementById('kode_uker').value;
            }
            
            // Check if KC and Unit are filled
            if (!kodeKc || !kodeUker) {
                alert('Harap pilih RMFT terlebih dahulu untuk menentukan KC dan Unit');
                norekInput.value = '';
                return;
            }
            
            fetch(`{{ route('api.nasabah.get') }}?norek=${norek}&kode_kc=${kodeKc}&kode_uker=${kodeUker}`)
                .then(response => response.json())
                .then(data => {
                    if (data.found) {
                        // Fill form with nasabah data
                        namaNasabahInput.value = data.data.nama_nasabah;
                        segmenNasabahSelect.value = data.data.segmen_nasabah;
                        
                        // Visual feedback
                        norekInput.style.borderColor = '#4caf50';
                        setTimeout(() => {
                            norekInput.style.borderColor = '#ddd';
                        }, 1000);
                    } else {
                        // Show info that norek is new for this KC/Unit
                        norekInput.style.borderColor = '#ff9800';
                        setTimeout(() => {
                            norekInput.style.borderColor = '#ddd';
                        }, 1000);
                    }
                });
        }, 300);
    });
    
    // Modal Functions
    function openNasabahModal() {
        const modal = document.getElementById('nasabahModal');
        modal.style.display = 'flex';
        document.getElementById('searchNasabah').focus();
    }
    
    function closeNasabahModal() {
        const modal = document.getElementById('nasabahModal');
        modal.style.display = 'none';
        document.getElementById('searchNasabah').value = '';
        document.getElementById('nasabahList').innerHTML = `
            <div style="text-align: center; padding: 40px; color: #666;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 48px; height: 48px; margin: 0 auto 16px; opacity: 0.3;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <p>Gunakan search untuk mencari nasabah</p>
            </div>
        `;
    }
    
    // Unit Modal Functions
    let allUnits = [];
    let selectedUnits = [];
    
    function openUnitModal() {
        const isUnitRmft = document.getElementById('is_unit_rmft').value;
        if (isUnitRmft !== '1') {
            return;
        }
        
        const kodeKc = document.getElementById('rmft_kode_kc').value;
        const namaKc = document.getElementById('nama_kc').value;
        
        if (!kodeKc) {
            alert('Harap pilih RMFT terlebih dahulu');
            return;
        }
        
        document.getElementById('modal_kc_name').textContent = namaKc;
        const modal = document.getElementById('unitModal');
        modal.style.display = 'flex';
        
        // Load units dari KC ini
        loadUnitsForKC(kodeKc);
    }
    
    function closeUnitModal() {
        const modal = document.getElementById('unitModal');
        modal.style.display = 'none';
        document.getElementById('searchUnit').value = '';
    }
    
    function loadUnitsForKC(kodeKc) {
        document.getElementById('unitList').innerHTML = `
            <div style="text-align: center; padding: 40px; color: #666;">
                <p>Memuat daftar unit...</p>
            </div>
        `;
        
        fetch(`{{ route('api.uker.by-kc') }}?kode_kc=${kodeKc}`)
            .then(response => response.json())
            .then(units => {
                allUnits = units;
                displayUnits(units);
                updateSelectedCount();
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('unitList').innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #d32f2f;">
                        <p>Terjadi kesalahan saat memuat data unit</p>
                    </div>
                `;
            });
    }
    
    function displayUnits(units) {
        if (units.length === 0) {
            document.getElementById('unitList').innerHTML = `
                <div style="text-align: center; padding: 40px; color: #666;">
                    <p>Tidak ada unit ditemukan untuk KC ini</p>
                </div>
            `;
            return;
        }
        
        let html = '<div style="display: flex; flex-direction: column; gap: 4px;">';
        
        units.forEach(unit => {
            const isSelected = selectedUnits.some(u => u.kode_sub_kanca === unit.kode_sub_kanca);
            
            html += `
                <label style="padding: 12px; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: all 0.2s; background: ${isSelected ? '#e3f2fd' : 'white'};"
                       onmouseenter="this.style.backgroundColor='#f0f8ff';"
                       onmouseleave="this.style.backgroundColor='${isSelected ? '#e3f2fd' : 'white'}';">
                    <input type="checkbox" 
                           value="${unit.kode_sub_kanca}" 
                           data-name="${unit.sub_kanca}"
                           ${isSelected ? 'checked' : ''}
                           onchange="toggleUnitSelection(this, ${JSON.stringify(unit).replace(/"/g, '&quot;')})"
                           style="width: 18px; height: 18px; cursor: pointer;">
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: #333;">${unit.sub_kanca}</div>
                        <div style="font-size: 12px; color: #666; margin-top: 2px;">Kode: ${unit.kode_sub_kanca}</div>
                    </div>
                </label>
            `;
        });
        
        html += '</div>';
        document.getElementById('unitList').innerHTML = html;
    }
    
    function toggleUnitSelection(checkbox, unit) {
        if (checkbox.checked) {
            // Add to selection
            if (!selectedUnits.some(u => u.kode_sub_kanca === unit.kode_sub_kanca)) {
                selectedUnits.push(unit);
            }
        } else {
            // Remove from selection
            selectedUnits = selectedUnits.filter(u => u.kode_sub_kanca !== unit.kode_sub_kanca);
        }
        updateSelectedCount();
    }
    
    function selectAllUnits() {
        const checkboxes = document.querySelectorAll('#unitList input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            if (!checkbox.checked) {
                checkbox.checked = true;
                const unitData = JSON.parse(checkbox.getAttribute('onchange').match(/toggleUnitSelection\(this, (.+)\)/)[1].replace(/&quot;/g, '"'));
                if (!selectedUnits.some(u => u.kode_sub_kanca === unitData.kode_sub_kanca)) {
                    selectedUnits.push(unitData);
                }
            }
        });
        displayUnits(allUnits.filter(unit => {
            const searchValue = document.getElementById('searchUnit').value.toLowerCase();
            return unit.sub_kanca.toLowerCase().includes(searchValue) ||
                   unit.kode_sub_kanca.toLowerCase().includes(searchValue);
        }));
        updateSelectedCount();
    }
    
    function clearAllUnits() {
        selectedUnits = [];
        const checkboxes = document.querySelectorAll('#unitList input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        displayUnits(allUnits.filter(unit => {
            const searchValue = document.getElementById('searchUnit').value.toLowerCase();
            return unit.sub_kanca.toLowerCase().includes(searchValue) ||
                   unit.kode_sub_kanca.toLowerCase().includes(searchValue);
        }));
        updateSelectedCount();
    }
    
    function updateSelectedCount() {
        const count = selectedUnits.length;
        document.getElementById('count_text').textContent = `${count} unit dipilih`;
    }
    
    function filterUnitList() {
        const searchValue = document.getElementById('searchUnit').value.toLowerCase();
        const filteredUnits = allUnits.filter(unit => 
            unit.sub_kanca.toLowerCase().includes(searchValue) ||
            unit.kode_sub_kanca.toLowerCase().includes(searchValue)
        );
        displayUnits(filteredUnits);
    }
    
    function applySelectedUnits() {
        if (selectedUnits.length === 0) {
            alert('Harap pilih minimal 1 unit');
            return;
        }
        
        // Update display untuk NAMA UKER
        const unitNames = selectedUnits.map(u => u.sub_kanca).join(', ');
        const unitCodes = selectedUnits.map(u => u.kode_sub_kanca).join(',');
        
        // Update display untuk KODE UKER (dengan line breaks untuk readability)
        const unitCodesDisplay = selectedUnits.map(u => u.kode_sub_kanca).join(', ');
        
        document.getElementById('nama_uker_display').value = unitNames;
        document.getElementById('nama_uker').value = unitNames;
        document.getElementById('kode_uker').value = selectedUnits[0].kode_sub_kanca; // Use first unit code as primary
        document.getElementById('kode_uker_display').value = unitCodesDisplay;
        document.getElementById('kode_uker_list').value = unitCodes;
        document.getElementById('nama_uker_list').value = unitNames;
        
        closeUnitModal();
        
        // Visual feedback
        const displayField = document.getElementById('nama_uker_display');
        const kodeUkerField = document.getElementById('kode_uker_display');
        
        displayField.style.borderColor = '#28a745';
        kodeUkerField.style.borderColor = '#28a745';
        
        setTimeout(() => {
            displayField.style.borderColor = '#ddd';
            kodeUkerField.style.borderColor = '#ddd';
        }, 1500);
    }
    
    let searchTimer;
    function searchNasabahList() {
        clearTimeout(searchTimer);
        const searchValue = document.getElementById('searchNasabah').value;
        
        if (searchValue.length < 2) {
            document.getElementById('nasabahList').innerHTML = `
                <div style="text-align: center; padding: 40px; color: #666;">
                    <p>Ketik minimal 2 karakter untuk mencari</p>
                </div>
            `;
            return;
        }
        
        searchTimer = setTimeout(() => {
            // Get KC and Unit from form
            const kodeKc = document.getElementById('kode_kc').value;
            const isUnitRmft = document.getElementById('is_unit_rmft').value;
            
            // Jika multiple units dipilih, gunakan semua unit
            let kodeUkerParam = '';
            if (isUnitRmft === '1' && selectedUnits.length > 0) {
                // Gunakan semua unit yang dipilih
                kodeUkerParam = selectedUnits.map(u => u.kode_sub_kanca).join(',');
            } else {
                // Gunakan single unit
                kodeUkerParam = document.getElementById('kode_uker').value;
            }
            
            document.getElementById('nasabahList').innerHTML = `
                <div style="text-align: center; padding: 40px; color: #666;">
                    <p>Mencari...</p>
                </div>
            `;
            
            fetch(`{{ route('api.nasabah.search') }}?norek=${searchValue}&kode_kc=${kodeKc}&kode_uker=${kodeUkerParam}`)
                .then(response => response.json())
                .then(nasabahs => {
                    if (nasabahs.length === 0) {
                        document.getElementById('nasabahList').innerHTML = `
                            <div style="text-align: center; padding: 40px; color: #666;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 48px; height: 48px; margin: 0 auto 16px; opacity: 0.3;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p>Tidak ada nasabah ditemukan</p>
                                <small style="color: #999;">Coba kata kunci lain atau tambah nasabah baru</small>
                            </div>
                        `;
                        return;
                    }
                    
                    let html = '<table style="width: 100%; border-collapse: collapse;">';
                    html += '<thead><tr style="background: #f5f5f5; border-bottom: 2px solid #ddd;">';
                    html += '<th style="padding: 10px; text-align: left; font-size: 13px;">CIFNO</th>';
                    html += '<th style="padding: 10px; text-align: left; font-size: 13px;">Nama</th>';
                    html += '<th style="padding: 10px; text-align: left; font-size: 13px;">Segmen</th>';
                    html += '<th style="padding: 10px; text-align: left; font-size: 13px;">Unit</th>';
                    html += '<th style="padding: 10px; text-align: center; font-size: 13px;">Aksi</th>';
                    html += '</tr></thead><tbody>';
                    
                    nasabahs.forEach(nasabah => {
                        html += '<tr style="border-bottom: 1px solid #eee;">';
                        html += `<td style="padding: 10px; font-weight: 600;">${nasabah.cifno || '-'}</td>`;
                        html += `<td style="padding: 10px;">${nasabah.nama_nasabah}</td>`;
                        html += `<td style="padding: 10px;"><span style="background: #e3f2fd; color: #1976d2; padding: 3px 8px; border-radius: 3px; font-size: 11px;">${nasabah.segmen_nasabah}</span></td>`;
                        html += `<td style="padding: 10px; font-size: 12px; color: #666;">${nasabah.nama_uker || '-'}</td>`;
                        html += `<td style="padding: 10px; text-align: center;">
                            <button onclick='selectNasabah(${JSON.stringify(nasabah)})' style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 6px 16px; border-radius: 4px; cursor: pointer; font-size: 12px;">Pilih</button>
                        </td>`;
                        html += '</tr>';
                    });
                    
                    html += '</tbody></table>';
                    document.getElementById('nasabahList').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('nasabahList').innerHTML = `
                        <div style="text-align: center; padding: 40px; color: #d32f2f;">
                            <p>Terjadi kesalahan saat mencari data</p>
                        </div>
                    `;
                });
        }, 500);
    }
    
    function selectNasabah(nasabah) {
        document.getElementById('norek').value = nasabah.cifno || nasabah.norek;
        document.getElementById('nama_nasabah').value = nasabah.nama_nasabah;
        document.querySelector('select[name="segmen_nasabah"]').value = nasabah.segmen_nasabah;
        closeNasabahModal();
        
        // Visual feedback
        const norekInput = document.getElementById('norek');
        norekInput.style.borderColor = '#4caf50';
        setTimeout(() => {
            norekInput.style.borderColor = '#ddd';
        }, 1500);
    }
    
    // Close modal when clicking outside
    document.getElementById('nasabahModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeNasabahModal();
        }
    });
    
    document.getElementById('unitModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeUnitModal();
        }
    });
</script>
@endsection
