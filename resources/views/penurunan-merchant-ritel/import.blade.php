@extends('layouts.app')

@section('content')
<style>
    .gradient-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 24px;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .container-section {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    .upload-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .upload-area {
        border: 2px dashed #667eea;
        border-radius: 12px;
        padding: 40px;
        text-align: center;
        background: #f8f9ff;
        cursor: pointer;
        transition: all 0.3s;
    }

    .upload-area:hover {
        border-color: #764ba2;
        background: #f3f1ff;
    }

    .upload-area.dragover {
        border-color: #764ba2;
        background: #f3f1ff;
    }

    .upload-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }

    .upload-text {
        font-size: 16px;
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
    }

    .upload-subtext {
        font-size: 14px;
        color: #666;
    }

    .file-input {
        display: none;
    }

    .form-group {
        margin-top: 24px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }

    .form-group input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .button-group {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }

    .btn {
        flex: 1;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-submit:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-cancel {
        background: #e0e0e0;
        color: #333;
    }

    .btn-cancel:hover {
        background: #d0d0d0;
    }

    .info-box {
        background: #e3f2fd;
        border-left: 4px solid #1976d2;
        padding: 16px;
        border-radius: 6px;
        margin-bottom: 16px;
    }

    .info-box h4 {
        margin: 0 0 12px;
        color: #1976d2;
        font-size: 14px;
    }

    .info-box ul {
        margin: 0;
        padding-left: 20px;
        font-size: 13px;
        color: #555;
    }

    .info-box li {
        margin-bottom: 6px;
    }

    .file-name {
        margin-top: 16px;
        padding: 12px;
        background: #f5f5f5;
        border-radius: 6px;
        display: none;
        font-size: 14px;
        color: #333;
    }

    .file-name.show {
        display: block;
    }

    .alert {
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .alert-success {
        background: #e8f5e9;
        color: #2e7d32;
        border-left: 4px solid #2e7d32;
    }

    .alert-error {
        background: #ffebee;
        color: #d32f2f;
        border-left: 4px solid #d32f2f;
    }

    .progress-bar {
        width: 100%;
        height: 6px;
        background: #e0e0e0;
        border-radius: 3px;
        overflow: hidden;
        margin-top: 16px;
        display: none;
    }

    .progress-bar.show {
        display: block;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        width: 0%;
        transition: width 0.3s;
    }

    @media (max-width: 768px) {
        .container-section {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="gradient-header">
    <h1>Import Data Penurunan Merchant Ritel</h1>
    <p>Unggah file CSV untuk mengimport data penurunan merchant ritel dalam jumlah besar</p>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">{{ $message }}</div>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-error">{{ $message }}</div>
@endif

<div class="container-section">
    <div class="upload-card">
        <form id="importForm" method="POST" action="{{ route('penurunan-merchant-ritel.import') }}" enctype="multipart/form-data">
            @csrf

            <div class="upload-area" id="uploadArea">
                <div class="upload-icon">📁</div>
                <div class="upload-text">Drag & drop file CSV di sini</div>
                <div class="upload-subtext">atau klik untuk memilih file</div>
                <input type="file" id="fileInput" name="file" class="file-input" accept=".csv,.txt">
            </div>

            <div class="file-name" id="fileName"></div>

            <div class="progress-bar" id="progressBar">
                <div class="progress-fill" id="progressFill"></div>
            </div>

            <div class="button-group">
                <a href="{{ route('penurunan-merchant-ritel.index') }}" class="btn btn-cancel">Batal</a>
                <button type="submit" class="btn btn-submit" id="submitBtn" disabled>Upload & Import</button>
            </div>
        </form>
    </div>

    <div class="info-card">
        <div class="info-box">
            <h4>📋 Format File CSV</h4>
            <ul>
                <li>Format: .csv atau .txt</li>
                <li>Encoding: UTF-8</li>
                <li>Delimiter: Koma (,)</li>
                <li>Jumlah kolom: 23</li>
                <li>Dengan header di baris pertama</li>
            </ul>
        </div>

        <div class="info-box">
            <h4>📊 Urutan Kolom CSV</h4>
            <ul>
                <li>1. Regional Office</li>
                <li>2. Kode Cabang Induk</li>
                <li>3. Cabang Induk</li>
                <li>4. Kode UKER</li>
                <li>5. Unit Kerja</li>
                <li>6. CIFNO</li>
                <li>7. No. Rekening</li>
                <li>8. Penurunan</li>
                <li>9. Product Type</li>
                <li>10. Nama Nasabah</li>
                <li>11. Segmentasi BPR</li>
                <li>12. Jenis Simpanan</li>
                <li>13. Saldo Last EOM</li>
                <li>14. Saldo Terupdate</li>
                <li>15. Delta</li>
                <li>16-23. PN Slot 1-8</li>
            </ul>
        </div>

        <div class="info-box">
            <h4>⚡ Tips</h4>
            <ul>
                <li>Proses import berjalan cepat</li>
                <li>Data duplikat akan ditambahkan</li>
                <li>Maksimal ukuran file: unlimited</li>
            </ul>
        </div>
    </div>
</div>

<script>
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const fileNameDisplay = document.getElementById('fileName');
    const submitBtn = document.getElementById('submitBtn');
    const progressBar = document.getElementById('progressBar');
    const progressFill = document.getElementById('progressFill');

    // Drag and drop handlers
    uploadArea.addEventListener('click', () => fileInput.click());

    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect();
        }
    });

    // File input change handler
    fileInput.addEventListener('change', handleFileSelect);

    function handleFileSelect() {
        const file = fileInput.files[0];
        if (file) {
            const validTypes = ['text/csv', 'text/plain'];
            const validExtensions = ['.csv', '.txt'];
            const fileName = file.name;
            const fileExtension = fileName.substring(fileName.lastIndexOf('.')).toLowerCase();

            if (validExtensions.includes(fileExtension)) {
                fileNameDisplay.textContent = `✓ File dipilih: ${fileName} (${(file.size / 1024).toFixed(2)} KB)`;
                fileNameDisplay.classList.add('show');
                submitBtn.disabled = false;
            } else {
                fileNameDisplay.textContent = `✗ File tidak valid. Gunakan .csv atau .txt`;
                fileNameDisplay.classList.add('show');
                submitBtn.disabled = true;
                fileInput.value = '';
            }
        }
    }

    // Form submission with progress
    document.getElementById('importForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const file = fileInput.files[0];

        if (!file) {
            alert('Silakan pilih file terlebih dahulu');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.textContent = 'Sedang diupload...';
        progressBar.classList.add('show');

        const xhr = new XMLHttpRequest();

        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                progressFill.style.width = percentComplete + '%';
            }
        });

        xhr.addEventListener('load', () => {
            if (xhr.status === 302 || xhr.status === 200) {
                window.location.href = xhr.responseURL || '{{ route("penurunan-merchant-ritel.index") }}';
            } else {
                alert('Terjadi kesalahan saat mengupload file');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Upload & Import';
                progressBar.classList.remove('show');
                progressFill.style.width = '0%';
            }
        });

        xhr.addEventListener('error', () => {
            alert('Terjadi kesalahan saat mengupload file');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Upload & Import';
            progressBar.classList.remove('show');
            progressFill.style.width = '0%';
        });

        xhr.open('POST', '{{ route("penurunan-merchant-ritel.import") }}');
        xhr.send(formData);
    });
</script>
@endsection
