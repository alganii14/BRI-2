@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="page-header">
    <h2>Selamat Datang di Dashboard</h2>
    <p>Sistem Manajemen Aktivitas Pipeline</p>
</div>

<div class="dashboard-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 24px;">
    <div class="card">
        <h3 style="color: #667eea;">Total Aktivitas</h3>
        <p style="font-size: 36px; font-weight: 700; color: #333; margin-top: 12px;">0</p>
        <p style="font-size: 13px; color: #666; margin-top: 8px;">Aktivitas terdaftar</p>
    </div>
    
    <div class="card">
        <h3 style="color: #764ba2;">Total Nasabah</h3>
        <p style="font-size: 36px; font-weight: 700; color: #333; margin-top: 12px;">0</p>
        <p style="font-size: 13px; color: #666; margin-top: 8px;">Nasabah terdaftar</p>
    </div>
    
    <div class="card">
        <h3 style="color: #f44336;">Total Uker</h3>
        <p style="font-size: 36px; font-weight: 700; color: #333; margin-top: 12px;">0</p>
        <p style="font-size: 13px; color: #666; margin-top: 8px;">Unit Kerja</p>
    </div>
    
    <div class="card">
        <h3 style="color: #4caf50;">Status Aktif</h3>
        <p style="font-size: 36px; font-weight: 700; color: #333; margin-top: 12px;">0</p>
        <p style="font-size: 13px; color: #666; margin-top: 8px;">Pipeline aktif</p>
    </div>
</div>

<div class="card">
    <h3>Aktivitas Terbaru</h3>
    <div style="padding: 20px; text-align: center; color: #666;">
        <p>Belum ada aktivitas terbaru</p>
    </div>
</div>
@endsection
