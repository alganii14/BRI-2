@extends('layouts.app')

@section('title', 'Import CSV - Penurunan Ritel')
@section('page-title', 'Import CSV - Penurunan Ritel')

@section('content')
<style>
        .import-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .upload-area {
            border: 3px dashed #667eea;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            margin-bottom: 20px;
            transition: all 0.3s;
            cursor: pointer;
        }

        .upload-area:hover {
            background: #f8f9ff;
            border-color: #764ba2;
        }

        .upload-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .upload-text {
            color: #666;
            margin-bottom: 10px;
        }

        .file-input {
            display: none;
        }

        .file-info {
            background: #f8f9ff;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            display: none;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            width: 100%;
            justify-content: center;
            margin-top: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .info-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            color: #856404;
        }

        .info-box ul {
            margin-left: 20px;
            margin-top: 10px;
        }
</style>

<div class="import-container">

    @if(session('success'))
        <div class="alert alert-success">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            ❌ {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="info-box">
            <strong>ℹ️ Informasi:</strong>
            <ul>
                <li>Format file: CSV (.csv)</li>
                <li>CSV harus memiliki 23 kolom</li>
                <li>Data lama akan dihapus dan diganti dengan data baru</li>
            </ul>
        </div>

        <form action="{{ route('penurunan-ritel.import') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf
            
            <div class="upload-area" onclick="document.getElementById('csv_file').click()">
                <div class="upload-icon">📁</div>
                <div class="upload-text">
                    <strong>Klik untuk memilih file CSV</strong><br>
                    atau drag and drop file di sini
                </div>
            </div>

            <input type="file" name="csv_file" id="csv_file" class="file-input" accept=".csv" required>

            <div class="file-info" id="fileInfo"></div>

            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                📤 Upload dan Import
            </button>

            <a href="{{ route('penurunan-ritel.index') }}" class="btn btn-secondary">
                ← Kembali
            </a>
        </form>

        <!-- Zona Berbahaya -->
        @php
            $totalpenurunanRitel = \App\Models\penurunanRitel::count();
        @endphp

        @if($totalpenurunanRitel > 0)
        <div class="card" style="margin-top: 30px; border: 2px solid #dc3545;">
            <div class="card-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <h4 class="card-title mb-0">⚠️ Zona Berbahaya</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-danger">
                    <strong>Peringatan:</strong> Tindakan di bawah ini bersifat permanen dan tidak dapat dibatalkan!
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Hapus Semua Data Penurunan No Segment Ritel</h6>
                        <p class="text-muted mb-0">Total {{ number_format($totalpenurunanRitel, 0, ",", ".") }} data akan dihapus secara permanen</p>
                    </div>
                    <form action="{{ route('penurunan-ritel.delete-all') }}" method="POST" 
                          onsubmit="return confirm('PERINGATAN: Anda akan menghapus SEMUA data penurunan no segment ritel ({{ number_format($totalpenurunanRitel, 0, ",", ".") }} data).\n\nTindakan ini TIDAK DAPAT DIBATALKAN!\n\nApakah Anda yakin ingin melanjutkan?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            🗑️ Hapus Semua Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
</div>

<script>
        const fileInput = document.getElementById('csv_file');
        const fileInfo = document.getElementById('fileInfo');
        const submitBtn = document.getElementById('submitBtn');

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                fileInfo.style.display = 'block';
                fileInfo.innerHTML = `
                    <strong>File dipilih:</strong><br>
                    📄 ${file.name}<br>
                    📦 ${(file.size / 1024).toFixed(2)} KB
                `;
                submitBtn.disabled = false;
            }
        });

        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            document.body.addEventListener(eventName, preventDefaults, false);
            document.querySelector('.upload-area').addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Handle drop
        document.querySelector('.upload-area').addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                fileInput.files = files;
                const event = new Event('change');
                fileInput.dispatchEvent(event);
            }
        });
</script>

@endsection
