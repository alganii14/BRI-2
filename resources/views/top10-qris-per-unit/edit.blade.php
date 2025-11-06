@extends('layouts.app')

@section('title', 'Edit Top 10 QRIS Per Unit')
@section('page-title', 'Edit Data Top 10 QRIS Per Unit')

@section('content')
<style>
    .form-container {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        max-width: 1200px;
        margin: 0 auto;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    input, select, textarea {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: #667eea;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }

    .error {
        color: #dc3545;
        font-size: 13px;
        margin-top: 5px;
    }
</style>

<div class="form-container">
    <form action="{{ route('top10-qris-per-unit.update', $top10QrisPerUnit->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-grid">
            <div class="form-group">
                <label for="rank">Rank</label>
                <input type="text" id="rank" name="rank" value="{{ old('rank', $top10QrisPerUnit->rank) }}">
                @error('rank')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="posisi">Posisi</label>
                <input type="text" id="posisi" name="posisi" value="{{ old('posisi', $top10QrisPerUnit->posisi) }}">
                @error('posisi')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="mainbr">Main Branch</label>
                <input type="text" id="mainbr" name="mainbr" value="{{ old('mainbr', $top10QrisPerUnit->mainbr) }}">
                @error('mainbr')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="mbdesc">MB Desc</label>
                <input type="text" id="mbdesc" name="mbdesc" value="{{ old('mbdesc', $top10QrisPerUnit->mbdesc) }}">
                @error('mbdesc')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="branch">Branch</label>
                <input type="text" id="branch" name="branch" value="{{ old('branch', $top10QrisPerUnit->branch) }}">
                @error('branch')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="brdesc">BR Desc</label>
                <input type="text" id="brdesc" name="brdesc" value="{{ old('brdesc', $top10QrisPerUnit->brdesc) }}">
                @error('brdesc')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="storeid">Store ID</label>
                <input type="text" id="storeid" name="storeid" value="{{ old('storeid', $top10QrisPerUnit->storeid) }}">
                @error('storeid')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="nama_merchant">Nama Merchant</label>
                <input type="text" id="nama_merchant" name="nama_merchant" value="{{ old('nama_merchant', $top10QrisPerUnit->nama_merchant) }}">
                @error('nama_merchant')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="no_rek">No Rekening</label>
                <input type="text" id="no_rek" name="no_rek" value="{{ old('no_rek', $top10QrisPerUnit->no_rek) }}">
                @error('no_rek')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="cif">CIF</label>
                <input type="text" id="cif" name="cif" value="{{ old('cif', $top10QrisPerUnit->cif) }}">
                @error('cif')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="pn">PN</label>
                <input type="text" id="pn" name="pn" value="{{ old('pn', $top10QrisPerUnit->pn) }}">
                @error('pn')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="pn_pemrakasa">PN Pemrakasa</label>
                <input type="text" id="pn_pemrakasa" name="pn_pemrakasa" value="{{ old('pn_pemrakasa', $top10QrisPerUnit->pn_pemrakasa) }}">
                @error('pn_pemrakasa')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="akumulasi_sv_total">Akumulasi SV Total</label>
                <input type="text" id="akumulasi_sv_total" name="akumulasi_sv_total" value="{{ old('akumulasi_sv_total', $top10QrisPerUnit->akumulasi_sv_total) }}">
                @error('akumulasi_sv_total')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="posisi_sv_total_september">Posisi SV Total September</label>
                <input type="text" id="posisi_sv_total_september" name="posisi_sv_total_september" value="{{ old('posisi_sv_total_september', $top10QrisPerUnit->posisi_sv_total_september) }}">
                @error('posisi_sv_total_september')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="akumulasi_trx_total">Akumulasi TRX Total</label>
                <input type="text" id="akumulasi_trx_total" name="akumulasi_trx_total" value="{{ old('akumulasi_trx_total', $top10QrisPerUnit->akumulasi_trx_total) }}">
                @error('akumulasi_trx_total')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="posisi_trx_total">Posisi TRX Total</label>
                <input type="text" id="posisi_trx_total" name="posisi_trx_total" value="{{ old('posisi_trx_total', $top10QrisPerUnit->posisi_trx_total) }}">
                @error('posisi_trx_total')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="saldo_posisi">Saldo Posisi</label>
                <input type="text" id="saldo_posisi" name="saldo_posisi" value="{{ old('saldo_posisi', $top10QrisPerUnit->saldo_posisi) }}">
                @error('saldo_posisi')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="ratas_saldo">Ratas Saldo</label>
                <input type="text" id="ratas_saldo" name="ratas_saldo" value="{{ old('ratas_saldo', $top10QrisPerUnit->ratas_saldo) }}">
                @error('ratas_saldo')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group full-width">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat" rows="3">{{ old('alamat', $top10QrisPerUnit->alamat) }}</textarea>
                @error('alamat')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Update</button>
            <a href="{{ route('top10-qris-per-unit.index') }}" class="btn btn-secondary">↩️ Kembali</a>
        </div>
    </form>
</div>
@endsection
