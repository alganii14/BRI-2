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

    <form action="{{ route('aktivitas.store') }}" method="POST" onsubmit="return validateForm()">
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
            <label>STRATEGY PULL OF PIPELINE <span style="color: red;">*</span></label>
            <select name="strategy_pipeline" id="strategy_pipeline" required onchange="handleStrategyChange()" disabled>
                <option value="">Pilih RMFT terlebih dahulu</option>
                <option value="Penurunan Brilink" {{ old('strategy_pipeline') == 'Penurunan Brilink' ? 'selected' : '' }}>Penurunan Brilink</option>
                <option value="Penurunan Mantri" {{ old('strategy_pipeline') == 'Penurunan Mantri' ? 'selected' : '' }}>Penurunan Mantri</option>
                <option value="Penurunan Merchant Mikro" {{ old('strategy_pipeline') == 'Penurunan Merchant Mikro' ? 'selected' : '' }}>Penurunan Merchant Mikro</option>
                <option value="Penurunan Merchant Ritel" {{ old('strategy_pipeline') == 'Penurunan Merchant Ritel' ? 'selected' : '' }}>Penurunan Merchant Ritel</option>
                <option value="Penurunan No-Segment Mikro" {{ old('strategy_pipeline') == 'Penurunan No-Segment Mikro' ? 'selected' : '' }}>Penurunan No-Segment Mikro</option>
                <option value="Penurunan No-Segment Ritel" {{ old('strategy_pipeline') == 'Penurunan No-Segment Ritel' ? 'selected' : '' }}>Penurunan No-Segment Ritel</option>
                <option value="Penurunan SME Ritel" {{ old('strategy_pipeline') == 'Penurunan SME Ritel' ? 'selected' : '' }}>Penurunan SME Ritel</option>
                <option value="Top 10 QRIS Per Unit" {{ old('strategy_pipeline') == 'Top 10 QRIS Per Unit' ? 'selected' : '' }}>Top 10 QRIS Per Unit</option>
            </select>
        </div>

        <div class="form-group">
            <label>RENCANA AKTIVITAS <span style="color: red;">*</span></label>
            <select name="rencana_aktivitas_id" id="rencana_aktivitas" required disabled>
                <option value="">Pilih Rencana Aktivitas</option>
                @foreach($rencanaAktivitas as $item)
                    <option value="{{ $item->id }}" 
                            data-nama="{{ $item->nama_rencana }}"
                            {{ old('rencana_aktivitas_id') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama_rencana }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="rencana_aktivitas" id="rencana_aktivitas_text" value="{{ old('rencana_aktivitas') }}">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>SEGMEN NASABAH <span style="color: red;">*</span></label>
                <select name="segmen_nasabah" id="segmen_nasabah" required disabled>
                    <option value="">Pilih RMFT terlebih dahulu</option>
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
                <label>TIPE NASABAH <span style="color: red;">*</span></label>
                <select name="tipe_nasabah" id="tipe_nasabah" required disabled onchange="toggleNasabahForm()">
                    <option value="">Pilih RMFT terlebih dahulu</option>
                    <option value="lama" {{ old('tipe_nasabah') == 'lama' ? 'selected' : '' }}>Nasabah Lama</option>
                    <option value="baru" {{ old('tipe_nasabah') == 'baru' ? 'selected' : '' }}>Nasabah Baru</option>
                </select>
            </div>
        </div>

        <!-- Form untuk Nasabah Lama -->
        <div id="form_nasabah_lama" style="display: none;">
            <div class="form-row">
                <div class="form-group">
                    <label>CIFNO <span style="color: red;">*</span></label>
                    <div style="position: relative;">
                        <input type="text" id="norek" name="norek" value="{{ old('norek') }}" placeholder="Pilih RMFT terlebih dahulu" autocomplete="off" style="padding-right: 45px;" disabled>
                        <button type="button" id="btn_search_nasabah" onclick="openNasabahModal()" style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-size: 12px;" disabled>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label>NAMA NASABAH <span style="color: red;">*</span></label>
                    <input type="text" id="nama_nasabah" name="nama_nasabah" value="{{ old('nama_nasabah') }}" placeholder="Pilih RMFT terlebih dahulu" disabled>
                </div>

                <div class="form-group">
                    <label>RP / JUMLAH <span style="color: red;">*</span></label>
                    <input type="text" id="rp_jumlah" name="rp_jumlah" value="{{ old('rp_jumlah') }}" placeholder="Pilih RMFT terlebih dahulu" disabled>
                </div>
            </div>
        </div>

        <!-- Form untuk Nasabah Baru -->
        <div id="form_nasabah_baru" style="display: none;">
            <div class="form-row">
                <div class="form-group">
                    <label>NO. REKENING <span style="color: red;">*</span></label>
                    <input type="text" id="norek_baru" name="norek_baru" value="{{ old('norek_baru') }}" placeholder="Masukkan nomor rekening" disabled>
                </div>

                <div class="form-group">
                    <label>NAMA NASABAH <span style="color: red;">*</span></label>
                    <input type="text" id="nama_nasabah_baru" name="nama_nasabah_baru" value="{{ old('nama_nasabah_baru') }}" placeholder="Masukkan nama nasabah" disabled>
                </div>

                <div class="form-group">
                    <label>RP / JUMLAH <span style="color: red;">*</span></label>
                    <input type="text" id="rp_jumlah_baru" name="rp_jumlah_baru" value="{{ old('rp_jumlah_baru') }}" placeholder="Masukkan jumlah" disabled>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>KETERANGAN</label>
            <textarea name="keterangan" id="keterangan" rows="3" placeholder="Pilih RMFT terlebih dahulu" disabled>{{ old('keterangan') }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Aktivitas</button>
            <a href="{{ route('aktivitas.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<!-- Modal Nasabah -->
<div id="nasabahModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 12px; width: 90%; max-width: 900px; max-height: 85vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
        <div style="padding: 20px; border-bottom: 2px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); flex-shrink: 0;">
            <h3 style="margin: 0; color: white;">Pilih Nasabah dari Pull of Pipeline</h3>
            <button onclick="closeNasabahModal()" style="background: none; border: none; color: white; font-size: 24px; cursor: pointer; padding: 0; width: 30px; height: 30px;">&times;</button>
        </div>
        
        <div style="padding: 20px; display: flex; flex-direction: column; overflow: hidden; flex: 1;">
            <input type="text" id="searchNasabah" placeholder="Cari CIFNO atau nama nasabah (opsional)..." style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; margin-bottom: 15px; flex-shrink: 0;" onkeyup="searchNasabahList()">
            
            <div id="nasabahList" style="flex: 1; overflow-y: auto; overflow-x: hidden;">
                <div style="text-align: center; padding: 40px; color: #667eea;">
                    <div style="display: inline-block; width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #667eea; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                    <p style="margin-top: 16px;">Memuat data...</p>
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
            <div style="margin-bottom: 15px;">
                <input type="text" id="searchUnit" placeholder="Cari nama unit..." style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px;" onkeyup="filterUnitList()">
            </div>
            
            <div id="selected_count" style="margin-bottom: 10px; padding: 8px 12px; background: #e3f2fd; border-radius: 6px; color: #1976d2; font-size: 13px; font-weight: 600;">
                <span id="count_text">Belum ada unit dipilih</span>
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
    // Handle strategy pipeline change
    function handleStrategyChange() {
        const strategy = document.getElementById('strategy_pipeline').value;
        
        if (strategy) {
            // Visual feedback
            const strategySelect = document.getElementById('strategy_pipeline');
            strategySelect.style.borderColor = '#28a745';
            setTimeout(() => {
                strategySelect.style.borderColor = '#ddd';
            }, 1000);
        }
    }

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
            
            // Clear list fields
            document.getElementById('kode_uker_list').value = '';
            document.getElementById('nama_uker_list').value = '';
            selectedUnits = [];
            
            // Disable Data Aktivitas fields
            disableAktivitasFields();
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
            
            // Clear list fields - user harus pilih unit secara manual
            document.getElementById('kode_uker_list').value = '';
            document.getElementById('nama_uker_list').value = '';
            
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
            
            // Clear list fields untuk RMFT biasa
            document.getElementById('kode_uker_list').value = '';
            document.getElementById('nama_uker_list').value = '';
            selectedUnits = [];
        }
        
        // Enable Data Aktivitas fields
        enableAktivitasFields();
    }
    
    // Function to disable Data Aktivitas fields
    function disableAktivitasFields() {
        document.getElementById('strategy_pipeline').disabled = true;
        document.getElementById('strategy_pipeline').innerHTML = '<option value="">Pilih RMFT terlebih dahulu</option>';
        document.getElementById('rencana_aktivitas').disabled = true;
        document.getElementById('rencana_aktivitas').innerHTML = '<option value="">Pilih RMFT terlebih dahulu</option>';
        document.getElementById('segmen_nasabah').disabled = true;
        document.getElementById('segmen_nasabah').innerHTML = '<option value="">Pilih RMFT terlebih dahulu</option>';
        document.getElementById('tipe_nasabah').disabled = true;
        document.getElementById('tipe_nasabah').innerHTML = '<option value="">Pilih RMFT terlebih dahulu</option>';
        document.getElementById('norek').disabled = true;
        document.getElementById('norek').placeholder = 'Pilih RMFT terlebih dahulu';
        document.getElementById('btn_search_nasabah').disabled = true;
        document.getElementById('nama_nasabah').disabled = true;
        document.getElementById('nama_nasabah').placeholder = 'Pilih RMFT terlebih dahulu';
        document.getElementById('rp_jumlah').disabled = true;
        document.getElementById('rp_jumlah').placeholder = 'Pilih RMFT terlebih dahulu';
        document.getElementById('norek_baru').disabled = true;
        document.getElementById('norek_baru').placeholder = 'Pilih RMFT terlebih dahulu';
        document.getElementById('nama_nasabah_baru').disabled = true;
        document.getElementById('nama_nasabah_baru').placeholder = 'Pilih RMFT terlebih dahulu';
        document.getElementById('rp_jumlah_baru').disabled = true;
        document.getElementById('rp_jumlah_baru').placeholder = 'Pilih RMFT terlebih dahulu';
        document.getElementById('keterangan').disabled = true;
        document.getElementById('keterangan').placeholder = 'Pilih RMFT terlebih dahulu';
    }
    
    // Function to enable Data Aktivitas fields
    function enableAktivitasFields() {
        // Enable Strategy Pipeline
        document.getElementById('strategy_pipeline').disabled = false;
        document.getElementById('strategy_pipeline').innerHTML = `
            <option value="">Pilih Strategy Pull of Pipeline</option>
            <option value="Penurunan Brilink">Penurunan Brilink</option>
            <option value="Penurunan Mantri">Penurunan Mantri</option>
            <option value="Penurunan Merchant Mikro">Penurunan Merchant Mikro</option>
            <option value="Penurunan Merchant Ritel">Penurunan Merchant Ritel</option>
            <option value="Penurunan No-Segment Mikro">Penurunan No-Segment Mikro</option>
            <option value="Penurunan No-Segment Ritel">Penurunan No-Segment Ritel</option>
            <option value="Penurunan SME Ritel">Penurunan SME Ritel</option>
            <option value="Top 10 QRIS Per Unit">Top 10 QRIS Per Unit</option>
        `;
        
        // Enable Rencana Aktivitas
        document.getElementById('rencana_aktivitas').disabled = false;
        document.getElementById('rencana_aktivitas').innerHTML = `
            <option value="">Pilih Rencana Aktivitas</option>
            @foreach($rencanaAktivitas as $item)
            <option value="{{ $item->id }}" data-nama="{{ $item->nama_rencana }}">{{ $item->nama_rencana }}</option>
            @endforeach
        `;
        
        // Enable Segmen Nasabah
        document.getElementById('segmen_nasabah').disabled = false;
        document.getElementById('segmen_nasabah').innerHTML = `
            <option value="">Pilih Segmen</option>
            <option value="Ritel Badan Usaha">Ritel Badan Usaha</option>
            <option value="SME">SME</option>
            <option value="Konsumer">Konsumer</option>
            <option value="Prioritas">Prioritas</option>
            <option value="Merchant">Merchant</option>
            <option value="Agen Brilink">Agen Brilink</option>
            <option value="Mikro">Mikro</option>
            <option value="Komersial">Komersial</option>
        `;
        
        // Enable Tipe Nasabah
        document.getElementById('tipe_nasabah').disabled = false;
        document.getElementById('tipe_nasabah').innerHTML = `
            <option value="">Pilih Tipe</option>
            <option value="lama">Nasabah Lama</option>
            <option value="baru">Nasabah Baru</option>
        `;
        
        // Enable Nasabah Lama fields (default disabled)
        document.getElementById('norek').disabled = false;
        document.getElementById('norek').placeholder = 'CIFNO nasabah';
        document.getElementById('btn_search_nasabah').disabled = false;
        document.getElementById('nama_nasabah').disabled = false;
        document.getElementById('nama_nasabah').placeholder = 'Nama lengkap nasabah';
        document.getElementById('rp_jumlah').disabled = false;
        document.getElementById('rp_jumlah').placeholder = 'Contoh: 10000000';
        
        // Enable Nasabah Baru fields (default disabled)
        document.getElementById('norek_baru').disabled = false;
        document.getElementById('norek_baru').placeholder = 'Masukkan nomor rekening';
        document.getElementById('nama_nasabah_baru').disabled = false;
        document.getElementById('nama_nasabah_baru').placeholder = 'Masukkan nama nasabah';
        document.getElementById('rp_jumlah_baru').disabled = false;
        document.getElementById('rp_jumlah_baru').placeholder = 'Masukkan jumlah';
        
        document.getElementById('keterangan').disabled = false;
        document.getElementById('keterangan').placeholder = 'Keterangan tambahan (opsional)';
    }
    
    // Function to toggle between Nasabah Baru and Nasabah Lama forms
    function toggleNasabahForm() {
        const tipeNasabah = document.getElementById('tipe_nasabah').value;
        const formLama = document.getElementById('form_nasabah_lama');
        const formBaru = document.getElementById('form_nasabah_baru');
        
        if (tipeNasabah === 'lama') {
            formLama.style.display = 'block';
            formBaru.style.display = 'none';
            // Clear Nasabah Baru fields
            document.getElementById('norek_baru').value = '';
            document.getElementById('nama_nasabah_baru').value = '';
            document.getElementById('rp_jumlah_baru').value = '';
        } else if (tipeNasabah === 'baru') {
            formLama.style.display = 'none';
            formBaru.style.display = 'block';
            // Clear Nasabah Lama fields
            document.getElementById('norek').value = '';
            document.getElementById('nama_nasabah').value = '';
            document.getElementById('rp_jumlah').value = '';
        } else {
            formLama.style.display = 'none';
            formBaru.style.display = 'none';
        }
    }

    // Autocomplete for Norek - DISABLED, now using Pipeline Search
    const norekInput = document.getElementById('norek');
    const namaNasabahInput = document.getElementById('nama_nasabah');
    const segmenNasababSelect = document.querySelector('select[name="segmen_nasabah"]');
    
    // Disable autocomplete on direct typing
    /*
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
                    `;
                });
        }, 300);
    });
    */
    
    // Modal Functions
    function openNasabahModal() {
        // Validasi strategy harus dipilih terlebih dahulu
        const strategy = document.getElementById('strategy_pipeline').value;
        if (!strategy) {
            alert('Harap pilih Strategy Pull of Pipeline terlebih dahulu');
            document.getElementById('strategy_pipeline').focus();
            return;
        }
        
        const modal = document.getElementById('nasabahModal');
        modal.style.display = 'flex';
        
        // Langsung load semua data tanpa perlu ketik
        loadAllNasabahFromPipeline();
    }
    
    // Variable to track pagination state
    let currentPage = 1;
    let totalPages = 1;
    let currentStrategy = '';
    let currentKodeKc = '';
    let currentKodeUker = '';
    
    // Function to load all nasabah from pipeline based on KC and Unit
    function loadAllNasabahFromPipeline(page = 1) {
        const kodeKc = document.getElementById('kode_kc').value;
        const isUnitRmft = document.getElementById('is_unit_rmft').value;
        const strategy = document.getElementById('strategy_pipeline').value;
        
        // Save current state
        currentPage = page;
        currentStrategy = strategy;
        currentKodeKc = kodeKc;
        
        // Jika multiple units dipilih, gunakan semua unit
        let kodeUkerParam = '';
        if (isUnitRmft === '1' && selectedUnits.length > 0) {
            kodeUkerParam = selectedUnits.map(u => u.kode_sub_kanca).join(',');
        } else {
            kodeUkerParam = document.getElementById('kode_uker').value;
        }
        
        currentKodeUker = kodeUkerParam;
        
        document.getElementById('nasabahList').innerHTML = `
            <div style="text-align: center; padding: 40px; color: #667eea;">
                <div style="display: inline-block; width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #667eea; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                <p style="margin-top: 16px;">Memuat data nasabah dari ${strategy}...</p>
            </div>
            <style>
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
            </style>
        `;
        
        fetch(`{{ route('api.pipeline.search') }}?search=&kode_kc=${kodeKc}&kode_uker=${kodeUkerParam}&strategy=${encodeURIComponent(strategy)}&load_all=1&page=${page}`)
            .then(response => response.json())
            .then(response => {
                // Handle paginated response
                const nasabahs = response.data || [];
                totalPages = response.last_page || 1;
                
                if (nasabahs.length === 0) {
                    document.getElementById('nasabahList').innerHTML = `
                        <div style="text-align: center; padding: 40px; color: #666;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 48px; height: 48px; margin: 0 auto 16px; opacity: 0.3;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>Tidak ada nasabah ditemukan di ${strategy}</p>
                            <small style="color: #999;">Untuk KC: ${document.getElementById('nama_kc').value}</small>
                        </div>
                    `;
                    return;
                }
                
                displayNasabahList(nasabahs, response);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('nasabahList').innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #d32f2f;">
                        <p>Terjadi kesalahan saat memuat data</p>
                    </div>
                `;
            });
    }
    
    // Function to display nasabah list
    function displayNasabahList(nasabahs, paginationData) {
        // Create wrapper with table container and fixed pagination at bottom
        let html = '<div style="display: flex; flex-direction: column; height: 100%;">';
        
        // Info header
        html += '<div style="margin-bottom: 10px; padding: 10px; background: #e3f2fd; border-radius: 6px; color: #1976d2; font-size: 13px; font-weight: 600; flex-shrink: 0;">';
        html += `<span>Ditemukan ${paginationData.total || nasabahs.length} nasabah`;
        if (paginationData && paginationData.last_page > 1) {
            html += ` - Halaman ${paginationData.current_page} dari ${paginationData.last_page}`;
        }
        html += '</span>';
        html += '</div>';
        
        // Scrollable table container
        html += '<div style="flex: 1; overflow-y: auto; overflow-x: hidden; margin-bottom: 10px;">';
        html += '<table style="width: 100%; border-collapse: collapse;">';
        html += '<thead><tr style="background: #f5f5f5; border-bottom: 2px solid #ddd; position: sticky; top: 0; z-index: 10;">';
        html += '<th style="padding: 10px; text-align: left; font-size: 13px; width: 15%;">CIFNO</th>';
        html += '<th style="padding: 10px; text-align: left; font-size: 13px; width: 25%;">Nama</th>';
        html += '<th style="padding: 10px; text-align: left; font-size: 13px; width: 25%;">Unit</th>';
        html += '<th style="padding: 10px; text-align: right; font-size: 13px; width: 20%;">Saldo</th>';
        html += '<th style="padding: 10px; text-align: center; font-size: 13px; width: 15%;">Aksi</th>';
        html += '</tr></thead><tbody>';
        
        nasabahs.forEach(nasabah => {
            // Pastikan saldo adalah number yang benar
            let saldoValue = 0;
            
            // Ambil nilai saldo - pastikan sebagai number
            if (typeof nasabah.saldo_terupdate === 'number') {
                saldoValue = nasabah.saldo_terupdate;
            } else if (typeof nasabah.saldo_terupdate === 'string') {
                saldoValue = parseFloat(nasabah.saldo_terupdate.replace(/,/g, ''));
            } else if (nasabah.saldo_last_eom) {
                if (typeof nasabah.saldo_last_eom === 'number') {
                    saldoValue = nasabah.saldo_last_eom;
                } else {
                    saldoValue = parseFloat(nasabah.saldo_last_eom.replace(/,/g, ''));
                }
            }
            
            // Pastikan saldoValue adalah number yang valid
            if (isNaN(saldoValue)) {
                saldoValue = 0;
            }
            
            // Format dengan pemisah ribuan dan desimal
            const saldoFormatted = new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(saldoValue);
            
            html += '<tr style="border-bottom: 1px solid #eee; transition: background 0.2s;" onmouseenter="this.style.background=\'#f8f9fa\'" onmouseleave="this.style.background=\'white\'">';
            html += `<td style="padding: 10px; font-weight: 600; font-family: monospace;">${nasabah.cifno || '-'}</td>`;
            html += `<td style="padding: 10px;">${nasabah.nama_nasabah}</td>`;
            html += `<td style="padding: 10px; font-size: 12px; color: #666;">${nasabah.unit_kerja || '-'}</td>`;
            html += `<td style="padding: 10px; font-size: 13px; text-align: right; color: ${saldoValue > 0 ? '#2e7d32' : '#d32f2f'}; font-weight: 600; font-family: monospace;">Rp ${saldoFormatted}</td>`;
            html += `<td style="padding: 10px; text-align: center;">
                <button onclick='selectNasabah(${JSON.stringify(nasabah)})' style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 6px 16px; border-radius: 4px; cursor: pointer; font-size: 12px; transition: transform 0.2s;" onmouseenter="this.style.transform='scale(1.05)'" onmouseleave="this.style.transform='scale(1)'">Pilih</button>
            </td>`;
            html += '</tr>';
        });
        
        html += '</tbody></table>';
        html += '</div>'; // Close scrollable container
        
        // Add pagination controls if needed - FIXED at bottom
        if (paginationData && paginationData.last_page > 1) {
            html += '<div style="padding: 12px; background: #f9fafb; border-radius: 6px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0; border-top: 2px solid #e0e0e0;">';
            
            // Previous button
            const prevDisabled = paginationData.current_page <= 1;
            html += `<button onclick="loadAllNasabahFromPipeline(${paginationData.current_page - 1})" 
                     style="padding: 8px 16px; background: ${prevDisabled ? '#e0e0e0' : '#667eea'}; color: ${prevDisabled ? '#999' : 'white'}; border: none; border-radius: 4px; cursor: ${prevDisabled ? 'not-allowed' : 'pointer'}; font-size: 13px; font-weight: 600;" 
                     ${prevDisabled ? 'disabled' : ''}>
                     ‹ Sebelumnya
                  </button>`;
            
            // Page info
            html += `<span style="font-size: 13px; color: #666; font-weight: 600;">
                     Halaman ${paginationData.current_page} dari ${paginationData.last_page}
                     <span style="color: #999; font-weight: normal;">(${paginationData.total} total)</span>
                  </span>`;
            
            // Next button
            const nextDisabled = paginationData.current_page >= paginationData.last_page;
            html += `<button onclick="loadAllNasabahFromPipeline(${paginationData.current_page + 1})" 
                     style="padding: 8px 16px; background: ${nextDisabled ? '#e0e0e0' : '#667eea'}; color: ${nextDisabled ? '#999' : 'white'}; border: none; border-radius: 4px; cursor: ${nextDisabled ? 'not-allowed' : 'pointer'}; font-size: 13px; font-weight: 600;" 
                     ${nextDisabled ? 'disabled' : ''}>
                     Selanjutnya ›
                  </button>`;
            
            html += '</div>';
        }
        
        html += '</div>'; // Close wrapper
        
        document.getElementById('nasabahList').innerHTML = html;
    }
    
    function closeNasabahModal() {
        const modal = document.getElementById('nasabahModal');
        modal.style.display = 'none';
        document.getElementById('searchNasabah').value = '';
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
            const isSelected = selectedUnits.length > 0 && selectedUnits[0].kode_sub_kanca === unit.kode_sub_kanca;
            
            html += `
                <label style="padding: 12px; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: all 0.2s; background: ${isSelected ? '#e3f2fd' : 'white'};"
                       onmouseenter="this.style.backgroundColor='#f0f8ff';"
                       onmouseleave="this.style.backgroundColor='${isSelected ? '#e3f2fd' : 'white'}';">
                    <input type="radio" 
                           name="unit_selection"
                           value="${unit.kode_sub_kanca}" 
                           data-name="${unit.sub_kanca}"
                           ${isSelected ? 'checked' : ''}
                           onchange="selectSingleUnit(${JSON.stringify(unit).replace(/"/g, '&quot;')})"
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
    
    function selectSingleUnit(unit) {
        // Clear previous selection and set new one
        selectedUnits = [unit];
        updateSelectedCount();
    }
    
    function updateSelectedCount() {
        const count = selectedUnits.length;
        if (count === 0) {
            document.getElementById('count_text').textContent = 'Belum ada unit dipilih';
        } else {
            document.getElementById('count_text').textContent = `Unit dipilih: ${selectedUnits[0].sub_kanca}`;
        }
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
            alert('Harap pilih 1 unit');
            return;
        }
        
        // Single selection - gunakan unit yang dipilih
        const selectedUnit = selectedUnits[0];
        
        document.getElementById('nama_uker_display').value = selectedUnit.sub_kanca;
        document.getElementById('nama_uker').value = selectedUnit.sub_kanca;
        document.getElementById('kode_uker').value = selectedUnit.kode_sub_kanca;
        document.getElementById('kode_uker_display').value = selectedUnit.kode_sub_kanca;
        
        // Clear list fields untuk single selection
        document.getElementById('kode_uker_list').value = '';
        document.getElementById('nama_uker_list').value = '';
        
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
            // Jika kurang dari 2 karakter, load semua data
            loadAllNasabahFromPipeline();
            return;
        }
        
        searchTimer = setTimeout(() => {
            // Get KC and Unit from form
            const kodeKc = document.getElementById('kode_kc').value;
            const isUnitRmft = document.getElementById('is_unit_rmft').value;
            const strategy = document.getElementById('strategy_pipeline').value;
            
            // Validasi strategy harus dipilih terlebih dahulu
            if (!strategy) {
                alert('Harap pilih Strategy Pull of Pipeline terlebih dahulu');
                closeNasabahModal();
                document.getElementById('strategy_pipeline').focus();
                return;
            }
            
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
                <div style="text-align: center; padding: 40px; color: #667eea;">
                    <div style="display: inline-block; width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #667eea; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                    <p style="margin-top: 16px;">Mencari...</p>
                </div>
            `;
            
            fetch(`{{ route('api.pipeline.search') }}?search=${searchValue}&kode_kc=${kodeKc}&kode_uker=${kodeUkerParam}&strategy=${encodeURIComponent(strategy)}`)
                .then(response => response.json())
                .then(nasabahs => {
                    if (nasabahs.length === 0) {
                        document.getElementById('nasabahList').innerHTML = `
                            <div style="text-align: center; padding: 40px; color: #666;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 48px; height: 48px; margin: 0 auto 16px; opacity: 0.3;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p>Tidak ada nasabah ditemukan di ${strategy}</p>
                                <small style="color: #999;">Coba kata kunci lain atau hapus pencarian untuk melihat semua</small>
                            </div>
                        `;
                        return;
                    }
                    
                    displayNasabahList(nasabahs);
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
        
        // Set segmen berdasarkan strategy yang dipilih
        const strategy = document.getElementById('strategy_pipeline').value;
        let segmen = '';
        
        if (strategy.includes('Brilink')) {
            segmen = 'Agen Brilink';
        } else if (strategy.includes('Mantri')) {
            segmen = 'Mikro';
        } else if (strategy.includes('Merchant Mikro')) {
            segmen = 'Merchant';
        } else if (strategy.includes('Merchant Ritel')) {
            segmen = 'Merchant';
        } else if (strategy.includes('No-Segment Mikro')) {
            segmen = 'Mikro';
        } else if (strategy.includes('No-Segment Ritel')) {
            segmen = 'Ritel Badan Usaha';
        } else if (strategy.includes('SME Ritel')) {
            segmen = 'SME';
        } else if (strategy.includes('QRIS')) {
            segmen = 'Merchant';
        } else {
            segmen = nasabah.segmen_nasabah || 'Konsumer';
        }
        
        document.querySelector('select[name="segmen_nasabah"]').value = segmen;
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
    
    // Form validation
    function validateForm() {
        const tipeNasabah = document.getElementById('tipe_nasabah').value;
        
        if (!tipeNasabah) {
            alert('Harap pilih Tipe Nasabah terlebih dahulu');
            return false;
        }
        
        if (tipeNasabah === 'lama') {
            // Validate Nasabah Lama fields
            const norek = document.getElementById('norek').value.trim();
            const namaNasabah = document.getElementById('nama_nasabah').value.trim();
            const rpJumlah = document.getElementById('rp_jumlah').value.trim();
            
            if (!norek) {
                alert('Harap isi CIFNO untuk Nasabah Lama');
                return false;
            }
            if (!namaNasabah) {
                alert('Harap isi Nama Nasabah untuk Nasabah Lama');
                return false;
            }
            if (!rpJumlah) {
                alert('Harap isi RP / Jumlah untuk Nasabah Lama');
                return false;
            }
            
            // Remove Nasabah Baru fields from submission
            document.getElementById('norek_baru').removeAttribute('name');
            document.getElementById('nama_nasabah_baru').removeAttribute('name');
            document.getElementById('rp_jumlah_baru').removeAttribute('name');
            
        } else if (tipeNasabah === 'baru') {
            // Validate Nasabah Baru fields
            const norekBaru = document.getElementById('norek_baru').value.trim();
            const namaNasabahBaru = document.getElementById('nama_nasabah_baru').value.trim();
            const rpJumlahBaru = document.getElementById('rp_jumlah_baru').value.trim();
            
            if (!norekBaru) {
                alert('Harap isi No. Rekening untuk Nasabah Baru');
                return false;
            }
            if (!namaNasabahBaru) {
                alert('Harap isi Nama Nasabah untuk Nasabah Baru');
                return false;
            }
            if (!rpJumlahBaru) {
                alert('Harap isi RP / Jumlah untuk Nasabah Baru');
                return false;
            }
            
            // Copy Nasabah Baru values to Nasabah Lama fields for submission
            document.getElementById('norek').value = norekBaru;
            document.getElementById('nama_nasabah').value = namaNasabahBaru;
            document.getElementById('rp_jumlah').value = rpJumlahBaru;
            
            // Remove Nasabah Baru fields from submission
            document.getElementById('norek_baru').removeAttribute('name');
            document.getElementById('nama_nasabah_baru').removeAttribute('name');
            document.getElementById('rp_jumlah_baru').removeAttribute('name');
        }
        
        return true;
    }
    
    // Initialize untuk RMFT - cek apakah Unit RMFT
    @if(auth()->user()->isRMFT())
    (function() {
        const rmftUkerName = "{{ optional($rmftData)->ukerRelation->sub_kanca ?? '' }}";
        const kodeKc = "{{ optional($rmftData)->ukerRelation->kode_kanca ?? '' }}";
        
        if (rmftUkerName.toUpperCase().includes('UNIT')) {
            // Set sebagai Unit RMFT
            document.getElementById('is_unit_rmft').value = '1';
            document.getElementById('unit_selector_label').style.display = 'inline';
            document.getElementById('rmft_kode_kc').value = kodeKc;
            
            // Clear list fields - user harus pilih unit secara manual
            document.getElementById('kode_uker_list').value = '';
            document.getElementById('nama_uker_list').value = '';
            
            // Enable klik untuk membuka modal unit
            const namaUkerDisplay = document.getElementById('nama_uker_display');
            const kodeUkerDisplay = document.getElementById('kode_uker_display');
            
            namaUkerDisplay.style.cursor = 'pointer';
            namaUkerDisplay.onclick = function() {
                openUnitModal();
            };
            
            kodeUkerDisplay.style.cursor = 'pointer';
            kodeUkerDisplay.onclick = function() {
                openUnitModal();
            };
            
            // Enable aktivitas fields
            enableAktivitasFields();
        } else {
            // RMFT biasa (bukan Unit RMFT)
            document.getElementById('is_unit_rmft').value = '0';
            
            // Pastikan list fields kosong untuk RMFT biasa
            document.getElementById('kode_uker_list').value = '';
            document.getElementById('nama_uker_list').value = '';
            
            // Enable aktivitas fields
            enableAktivitasFields();
        }
    })();
    @endif
    
    // Event listener untuk update hidden field rencana_aktivitas
    document.getElementById('rencana_aktivitas').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const namaRencana = selectedOption.getAttribute('data-nama') || selectedOption.text;
        document.getElementById('rencana_aktivitas_text').value = namaRencana;
    });
</script>
@endsection
