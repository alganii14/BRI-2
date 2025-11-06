@extends('layouts.app')

@section('title', 'Import CSV Penurunan Merchant Mikro')
@section('page-title', 'Import Data Penurunan Merchant Mikro dari CSV')

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
        cursor: pointer;
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-primary:hover, .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .info-box {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border-left: 4px solid #667eea;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 30px;
    }

    .info-box h4 {
        margin-top: 0;
        color: #667eea;
    }

    .info-box ul {
        margin: 10px 0;
        padding-left: 20px;
        color: #333;
    }

    .info-box li {
        margin: 5px 0;
    }

    .alert {
        padding: 12px 20px;
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
            <li>Kolom CSV: Regional Office, Kode Cabang Induk, Cabang Induk, Kode Uker, Unit Kerja, CIFNO, No Rekening, Brilink, YTD, Product Type, Nama Nasabah, Jenis Nasabah, Segmentasi BPR, Jenis Simpanan, Saldo Last EOM, Saldo Terupdate, Delta, PN Slot 1-8</li>
        </ul>
    </div>

    <form action="{{ route('penurunan-merchant-mikro.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
        @csrf
        
        <div class="upload-area" id="dropZone">
            <div class="upload-icon">📄</div>
            <h3>Upload File CSV</h3>
            <p>Drag & drop file di sini atau klik tombol di bawah</p>
            
            <div class="file-input-wrapper">
                <label class="file-input-label">
                    📂 Pilih File
                    <input type="file" name="file" accept=".csv,.txt" id="csvFile" required>
                </label>
            </div>
            
            <div class="file-name" id="fileName"></div>
        </div>

        <button type="submit" class="btn btn-primary">📤 Upload & Import CSV</button>
        <a href="{{ route('penurunan-merchant-mikro.index') }}" class="btn btn-secondary">❌ Batal</a>
    </form>
</div>

<script>
    const dropZone = document.getElementById('dropZone');
    const csvFile = document.getElementById('csvFile');
    const fileNameDisplay = document.getElementById('fileName');

    // File selection
    csvFile.addEventListener('change', function() {
        if (this.files.length > 0) {
            fileNameDisplay.textContent = '✓ File dipilih: ' + this.files[0].name;
            fileNameDisplay.classList.add('show');
        }
    });

    // Drag and drop
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.style.borderColor = '#764ba2';
        dropZone.style.background = 'linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%)';
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.style.borderColor = '#667eea';
        dropZone.style.background = 'linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%)';
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.style.borderColor = '#667eea';
        dropZone.style.background = 'linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%)';
        
        if (e.dataTransfer.files.length > 0) {
            csvFile.files = e.dataTransfer.files;
            const event = new Event('change', { bubbles: true });
            csvFile.dispatchEvent(event);
        }
    });
</script>

@endsection
