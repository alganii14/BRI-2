@extends('layouts.app')

@section('title', 'Manajemen Akun')
@section('page-title', 'Manajemen Akun')

@section('content')
<style>
    .table-container {
        overflow-x: auto;
        margin-top: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
    }

    table td {
        padding: 12px;
        border-bottom: 1px solid #eee;
        font-size: 13px;
    }

    table tr:hover {
        background-color: #f8f9fa;
    }

    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-manager {
        background-color: #667eea;
        color: white;
    }

    .badge-rmft {
        background-color: #4caf50;
        color: white;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-top: 32px;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid #667eea;
    }

    .section-title:first-child {
        margin-top: 0;
    }

    .info-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 16px 20px;
        border-radius: 8px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .info-item {
        text-align: center;
    }

    .info-item .number {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .info-item .label {
        font-size: 13px;
        opacity: 0.9;
    }
</style>

<div class="page-header">
    <h2>Manajemen Akun</h2>
    <p>Kelola akun Manager dan RMFT</p>
</div>

<div class="info-box">
    <div class="info-item">
        <div class="number">{{ $managers->total() }}</div>
        <div class="label">Akun Manager</div>
    </div>
    <div class="info-item">
        <div class="number">{{ $rmfts->total() }}</div>
        <div class="label">Akun RMFT</div>
    </div>
    <div class="info-item">
        <div class="number">{{ $managers->total() + $rmfts->total() }}</div>
        <div class="label">Total Akun</div>
    </div>
</div>

<div class="card">
    <div class="section-title">👤 Akun Manager</div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NAMA</th>
                    <th>EMAIL</th>
                    <th>ROLE</th>
                    <th>DIBUAT</th>
                </tr>
            </thead>
            <tbody>
                @forelse($managers as $manager)
                <tr>
                    <td>{{ $loop->iteration + ($managers->currentPage() - 1) * $managers->perPage() }}</td>
                    <td>{{ $manager->name }}</td>
                    <td>{{ $manager->email }}</td>
                    <td><span class="badge badge-manager">MANAGER</span></td>
                    <td>{{ $manager->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px; color: #666;">
                        Tidak ada akun manager
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($managers->hasPages())
    <div class="pagination-wrapper" style="margin-top: 20px;">
        {{ $managers->links() }}
    </div>
    @endif
</div>

<div class="card">
    <div class="section-title">👥 Akun RMFT</div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>PERNR</th>
                    <th>NAMA</th>
                    <th>EMAIL</th>
                    <th>KANCA</th>
                    <th>KELOMPOK</th>
                    <th>ROLE</th>
                    <th>DIBUAT</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rmfts as $rmft)
                <tr>
                    <td>{{ $loop->iteration + ($rmfts->currentPage() - 1) * $rmfts->perPage() }}</td>
                    <td><strong>{{ $rmft->pernr ?? '-' }}</strong></td>
                    <td>{{ $rmft->name }}</td>
                    <td>{{ $rmft->email }}</td>
                    <td>{{ $rmft->rmftData->kanca ?? '-' }}</td>
                    <td>{{ $rmft->rmftData->kelompok_jabatan ?? '-' }}</td>
                    <td><span class="badge badge-rmft">RMFT</span></td>
                    <td>{{ $rmft->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #666;">
                        Tidak ada akun RMFT
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($rmfts->hasPages())
    <div class="pagination-wrapper" style="margin-top: 20px;">
        {{ $rmfts->links() }}
    </div>
    @endif
</div>

<div class="card" style="background-color: #f8f9fa; border-left: 4px solid #667eea;">
    <h3 style="color: #667eea; margin-bottom: 12px;">ℹ️ Informasi Login</h3>
    <p style="margin-bottom: 8px; color: #666;"><strong>Manager:</strong> Login menggunakan email dan password</p>
    <p style="margin-bottom: 8px; color: #666;"><strong>RMFT:</strong> Login menggunakan PERNR atau email</p>
    <p style="color: #666;"><strong>Password Default:</strong> <code style="background: white; padding: 2px 8px; border-radius: 4px; color: #667eea;">password</code></p>
</div>
@endsection
