@extends('layouts.app')

@section('title', 'Penurunan Merchant Mikro')

@section('page-title')
Data Penurunan Merchant Mikro - KC {{ auth()->user()->nama_kanca }}
@endsection

@section('content')
<style>
    .table-container { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; background: white; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; white-space: nowrap; }
    th { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; font-weight: 600; font-size: 14px; }
    tr:hover { background-color: #f5f5f5; }
    .search-box { margin-bottom: 20px; display: flex; gap: 10px; }
    .search-box input { flex: 1; padding: 10px 16px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
    .btn-search { padding: 10px 24px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; }
</style>

<div class="card">
    <div style="padding: 20px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px 12px 0 0;">
        <h3 style="color: white; margin: 0;">🏢 Data Penurunan Merchant Mikro</h3>
        <p style="color: rgba(255,255,255,0.9); margin: 8px 0 0 0; font-size: 14px;">
            Menampilkan data untuk KC {{ auth()->user()->nama_kanca }} ({{ auth()->user()->kode_kanca }})
        </p>
    </div>

    <div style="padding: 20px;">
        <form method="GET" action="{{ route('manager.pipeline.merchant-mikro') }}" class="search-box">
            <input type="text" name="search" placeholder="Cari berdasarkan CIFNO, Nama, No Rekening, atau Unit..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn-search">🔍 Cari</button>
            @if($search)
                <a href="{{ route('manager.pipeline.merchant-mikro') }}" class="btn-search" style="background: #6c757d;">Reset</a>
            @endif
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Regional Office</th>
                    <th>Cabang Induk</th>
                    <th>Unit Kerja</th>
                    <th>CIFNO</th>
                    <th>No. Rekening</th>
                    <th>Nama Nasabah</th>
                    <th>Jenis Simpanan</th>
                    <th>Saldo Terupdate</th>
                    <th>Saldo Last EOM</th>
                    <th>Delta</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>{{ $item->regional_office }}</td>
                        <td>{{ $item->cabang_induk }}</td>
                        <td>{{ $item->unit_kerja }}</td>
                        <td style="font-family: monospace; font-weight: 600;">{{ $item->cifno }}</td>
                        <td style="font-family: monospace;">{{ $item->no_rekening }}</td>
                        <td>{{ $item->nama_nasabah }}</td>
                        <td><span style="display: inline-block; background: #e1f5fe; color: #01579b; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">{{ $item->jenis_simpanan ?? 'N/A' }}</span></td>
                        <td style="text-align: right; color: #2e7d32; font-weight: 600; font-family: monospace;">
                            Rp {{ ($item->saldo_terupdate ?? 'Rp 0,00') }}
                        </td>
                        <td style="text-align: right; color: #1565c0; font-weight: 600; font-family: monospace;">
                            Rp {{ ($item->saldo_last_eom ?? 'Rp 0,00') }}
                        </td>
                        <td style="text-align: right; font-weight: 600; font-family: monospace; color: {{ strpos($item->delta, '-') === 0 ? '#d32f2f' : '#2e7d32' }};">
                            {{ $item->delta ?? 'Rp 0,00' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" style="text-align: center; padding: 40px; color: #666;">
                            <p>{{ $search ? "Tidak ada data yang cocok dengan pencarian \"$search\"" : 'Belum ada data penurunan merchant mikro untuk KC Anda' }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($data->hasPages())
        <div style="margin-top: 20px; display: flex; justify-content: center; gap: 8px; padding: 20px;">
            {{-- Previous Button --}}
            @if($data->onFirstPage())
                <span class="btn" style="background: #f5f5f5; color: #999; cursor: not-allowed; padding: 8px 16px; border-radius: 6px;">← Sebelumnya</span>
            @else
                <a href="{{ $data->previousPageUrl() }}" class="btn" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none;">← Sebelumnya</a>
            @endif

            {{-- Page Numbers --}}
            @php
                $lastPage = $data->lastPage();
            @endphp
            @foreach (range(1, min(5, $lastPage)) as $page)
                @if ($page == $data->currentPage())
                    <span class="btn" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 8px 16px; border-radius: 6px;">{{ $page }}</span>
                @else
                    <a href="{{ $data->url($page) }}" class="btn" style="background: #f5f5f5; color: #4facfe; padding: 8px 16px; border-radius: 6px; text-decoration: none;">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Next Button --}}
            @if($data->hasMorePages())
                <a href="{{ $data->nextPageUrl() }}" class="btn" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none;">Selanjutnya →</a>
            @else
                <span class="btn" style="background: #f5f5f5; color: #999; cursor: not-allowed; padding: 8px 16px; border-radius: 6px;">Selanjutnya →</span>
            @endif
        </div>
    @endif

    <div style="padding: 20px; background: #f9fafb; border-top: 2px solid #e0e0e0;">
        <p style="margin: 0; color: #666; font-size: 14px;">
            <strong>Total Data:</strong> {{ $data->total() }} nasabah
            @if($search) | <strong>Hasil Pencarian:</strong> "{{ $search }}" @endif
        </p>
    </div>
</div>
@endsection
