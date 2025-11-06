@extends('layouts.app')

@section('title', 'Import CSV Penurunan Mantri')
@section('page-title', 'Import Data Penurunan Mantri dari CSV')

@section('content')
<style>
    .import-container {
        background: white;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        max-width: 800px;
        margin: 0 auto;
    }

    .upload-area {
        border: 2px dashed #667eea;
        border-radius: 12px;
        padding: 60px 40px;
        text-align: center;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        margin-bottom: 30px;
        transition: all 0.3s;
    }

    .upload-area:hover {
        border-color: #764ba2;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    }

    .upload-icon {
        font-size: 64px;
        margin-bottom: 20px;
    }

    .upload-area h3 {
        color: #667eea;
        margin-bottom: 10px;
        font-size: 20px;
    }

    .upload-area p {
        color: #666;
        margin-bottom: 20px;
    }

    .file-input-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
    }

    .file-input-wrapper input[type=file] {
        position: absolute;
        left: -9999px;
    }

    .file-input-label {
        display: inline-block;
        padding: 12px 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 600;
    }

    .file-input-label:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .file-name {
        margin-top: 15px;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 6px;
        color: #333;
        font-weight: 500;
        display: none;
    }

    .file-name.show {
        display: block;
    }

    .btn {
        padding: 14px 30px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 15px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
        font-weight: 600;
    }

    .btn-primary {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .info-box {
        background: #e7f3ff;
        border-left: 4px solid #2196F3;
        padding: 20px;
        border-radius: 6px;
        margin-bottom: 30px;
    }

    .info-box h4 {
        color: #2196F3;
        margin-bottom: 10px;
        font-size: 16px;
    }

    .info-box ul {
        margin: 10px 0 0 20px;
        color: #555;
    }

    .info-box li {
        margin-bottom: 5px;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .alert-success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .alert-error {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }
</style>

<div class="import-container">
    @if(session('success'))
        <div class="alert alert-success">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            ✗ {{ session('error') }}
        </div>
    @endif

    <div class="info-box">
        <h4>ℹ️ Petunjuk Import CSV</h4>
        <ul>
            <li>File harus dalam format CSV (.csv)</li>
            <li>Pastikan format CSV sesuai dengan template yang ada</li>
            <li>Kolom CSV: Regional Office, Kode Cabang Induk, Cabang Induk, Kode Uker, Unit Kerja, CIFNO, No Rekening, YTD, Product Type, Nama Nasabah, Jenis Nasabah, Segmentasi BPR, Jenis Simpanan, Saldo Last EOM, Saldo Terupdate, Delta, PN Slot 1-8</li>
        </ul>
    </div>

    <form action="{{ route('penurunan-mantri.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
        @csrf
        
        <div class="upload-area">
            <div class="upload-icon">📄</div>
            <h3>Upload File CSV</h3>
            <p>Pilih file CSV untuk diimport ke sistem</p>
            
            <div class="file-input-wrapper">
                <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
                <label for="csv_file" class="file-input-label">
                    📁 Pilih File CSV
                </label>
            </div>
            
            <div class="file-name" id="fileName"></div>
            
            @error('csv_file')
                <p style="color: #dc3545; margin-top: 10px;">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                ⬆️ Upload & Import
            </button>
            <a href="{{ route('penurunan-mantri.index') }}" class="btn btn-secondary">
                ↩️ Kembali
            </a>
        </div>
    </form>
</div>

<script>
    const fileInput = document.getElementById('csv_file');
    const fileName = document.getElementById('fileName');
    const submitBtn = document.getElementById('submitBtn');
    
    fileInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            fileName.textContent = '📎 File terpilih: ' + file.name + ' (' + formatFileSize(file.size) + ')';
            fileName.classList.add('show');
            submitBtn.disabled = false;
        } else {
            fileName.classList.remove('show');
            submitBtn.disabled = true;
        }
    });
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }
    
    document.getElementById('importForm').addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '⏳ Mengimport...';
    });
</script>
@endsection
