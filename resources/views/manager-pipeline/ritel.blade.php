@extends('layouts.app')

@section('title', 'Penurunan Ritel')

@section('page-title')
Data Penurunan Ritel - KC {{ auth()->user()->nama_kanca }}
@endsection

@section('content')
<style>.table-container{overflow-x:auto}table{width:100%;border-collapse:collapse;background:white}th,td{padding:12px;text-align:left;border-bottom:1px solid #ddd;white-space:nowrap}th{background:linear-gradient(135deg,#ffecd2 0%,#fcb69f 100%);color:#333;font-weight:600;font-size:14px}tr:hover{background-color:#f5f5f5}.search-box{margin-bottom:20px;display:flex;gap:10px}.search-box input{flex:1;padding:10px 16px;border:1px solid #ddd;border-radius:6px;font-size:14px}.btn-search{padding:10px 24px;background:linear-gradient(135deg,#ffecd2 0%,#fcb69f 100%);color:#333;border:none;border-radius:6px;cursor:pointer;font-size:14px}</style>
<div class="card">
    <div style="padding:20px;background:linear-gradient(135deg,#ffecd2 0%,#fcb69f 100%);border-radius:12px 12px 0 0;">
        <h3 style="color:#333;margin:0;">📊 Data Penurunan Ritel</h3>
        <p style="color:#555;margin:8px 0 0 0;font-size:14px;">Menampilkan data untuk KC {{ auth()->user()->nama_kanca }} ({{ auth()->user()->kode_kanca }})</p>
    </div>
    <div style="padding:20px;"><form method="GET" action="{{ route('manager.pipeline.ritel') }}" class="search-box"><select name="year" style="padding:10px 16px;border:1px solid #ddd;border-radius:6px;font-size:14px;background:white;"><option value="">Semua Tahun</option>@foreach($availableYears as $availableYear)<option value="{{ $availableYear }}" {{ request('year') == $availableYear ? 'selected' : '' }}>{{ $availableYear }}</option>@endforeach</select><select name="month" style="padding:10px 16px;border:1px solid #ddd;border-radius:6px;font-size:14px;background:white;"><option value="">Semua Bulan</option>@for($i=1;$i<=12;$i++)<option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>{{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$i] }}</option>@endfor</select><input type="text" name="search" placeholder="Cari..." value="{{ $search ?? '' }}"><button type="submit" class="btn-search">🔍 Cari</button>@if($search || request('month') || request('year'))<a href="{{ route('manager.pipeline.ritel') }}" class="btn-search" style="background:#6c757d;">Reset</a>@endif</form></div>
    <div class="table-container"><table><thead><tr><th>#</th><th>Regional Office</th><th>Cabang Induk</th><th>Unit Kerja</th><th>CIFNO</th><th>No. Rekening</th><th>Nama Nasabah</th><th>Jenis Simpanan</th><th>Saldo Terupdate</th><th>Saldo Last EOM</th><th>Delta</th></tr></thead><tbody>@forelse($data as $index => $item)<tr><td>{{ $data->firstItem() + $index }}</td><td>{{ $item->regional_office }}</td><td>{{ $item->cabang_induk }}</td><td>{{ $item->unit_kerja }}</td><td style="font-family:monospace;font-weight:600;">{{ $item->cifno }}</td><td style="font-family:monospace;">{{ $item->no_rekening }}</td><td>{{ $item->nama_nasabah }}</td><td><span style="display: inline-block; background: #ffebee; color: #c62828; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">{{ $item->jenis_simpanan ?? 'N/A' }}</span></td><td style="text-align:right;color:#2e7d32;font-weight:600;font-family:monospace;">Rp {{ ($item->saldo_terupdate ?? 'Rp 0,00') }}</td><td style="text-align:right;color:#1565c0;font-weight:600;font-family:monospace;">Rp {{ ($item->saldo_last_eom ?? 'Rp 0,00') }}</td><td style="text-align: right; font-weight: 600; font-family: monospace; color: {{ strpos($item->delta, '-') === 0 ? '#d32f2f' : '#2e7d32' }};">{{ $item->delta ?? 'Rp 0,00' }}</td></tr>@empty<tr><td colspan="11" style="text-align:center;padding:40px;color:#666;"><p>{{ $search ? "Tidak ada data yang cocok" : 'Belum ada data' }}</p></td></tr>@endforelse</tbody></table></div>
    @if($data->hasPages())
        <div style="margin-top: 20px; display: flex; justify-content: center; gap: 8px; padding: 20px;">
            @if($data->onFirstPage())
                <span class="btn" style="background: #f5f5f5; color: #999; cursor: not-allowed; padding: 8px 16px; border-radius: 6px;">← Sebelumnya</span>
            @else
                <a href="{{ $data->previousPageUrl() }}" class="btn" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #333; padding: 8px 16px; border-radius: 6px; text-decoration: none;">← Sebelumnya</a>
            @endif
            @php $lastPage = $data->lastPage(); @endphp
            @foreach (range(1, min(5, $lastPage)) as $page)
                @if ($page == $data->currentPage())
                    <span class="btn" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #333; padding: 8px 16px; border-radius: 6px;">{{ $page }}</span>
                @else
                    <a href="{{ $data->url($page) }}" class="btn" style="background: #f5f5f5; color: #ffecd2; padding: 8px 16px; border-radius: 6px; text-decoration: none;">{{ $page }}</a>
                @endif
            @endforeach
            @if($data->hasMorePages())
                <a href="{{ $data->nextPageUrl() }}" class="btn" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #333; padding: 8px 16px; border-radius: 6px; text-decoration: none;">Selanjutnya →</a>
            @else
                <span class="btn" style="background: #f5f5f5; color: #999; cursor: not-allowed; padding: 8px 16px; border-radius: 6px;">Selanjutnya →</span>
            @endif
        </div>
    @endif
    <div style="padding:20px;background:#f9fafb;border-top:2px solid #e0e0e0;"><p style="margin:0;color:#666;font-size:14px;"><strong>Total:</strong> {{ $data->total() }} nasabah @if($search) | <strong>Pencarian:</strong> "{{ $search }}" @endif</p></div>
</div>
@endsection
