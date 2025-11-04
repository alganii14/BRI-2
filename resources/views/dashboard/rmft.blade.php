@extends('layouts.app')

@section('title', 'Dashboard RMFT')
@section('page-title', 'Dashboard RMFT')

@section('content')
<div class="page-header">
    <h2>Dashboard RMFT - {{ Auth::user()->name }}</h2>
    <p>Aktivitas dan Target Pipeline Anda</p>
</div>

<div class="dashboard-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 24px;">
    <div class="card">
        <h3 style="color: #667eea;">Aktivitas Hari Ini</h3>
        <p style="font-size: 36px; font-weight: 700; color: #333; margin-top: 12px;">0</p>
        <p style="font-size: 13px; color: #666; margin-top: 8px;">Aktivitas tercatat</p>
    </div>
    
    <div class="card">
        <h3 style="color: #764ba2;">Target Bulan Ini</h3>
        <p style="font-size: 36px; font-weight: 700; color: #333; margin-top: 12px;">0%</p>
        <p style="font-size: 13px; color: #666; margin-top: 8px;">Pencapaian target</p>
    </div>
    
    <div class="card">
        <h3 style="color: #f44336;">Nasabah Aktif</h3>
        <p style="font-size: 36px; font-weight: 700; color: #333; margin-top: 12px;">0</p>
        <p style="font-size: 13px; color: #666; margin-top: 8px;">Nasabah dalam pipeline</p>
    </div>
    
    <div class="card">
        <h3 style="color: #4caf50;">Follow Up</h3>
        <p style="font-size: 36px; font-weight: 700; color: #333; margin-top: 12px;">0</p>
        <p style="font-size: 13px; color: #666; margin-top: 8px;">Perlu ditindaklanjuti</p>
    </div>
</div>

<div class="card">
    <h3>Aktivitas Terbaru Saya</h3>
    <div style="padding: 20px; text-align: center; color: #666;">
        <p>Belum ada aktivitas tercatat</p>
    </div>
</div>

<div class="card">
    <h3>Ringkasan Kinerja</h3>
    <div style="padding: 20px;">
        <div style="margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="font-size: 14px; color: #666;">Target Pencapaian</span>
                <span style="font-size: 14px; font-weight: 600; color: #333;">0%</span>
            </div>
            <div style="width: 100%; height: 8px; background-color: #e0e0e0; border-radius: 4px; overflow: hidden;">
                <div style="width: 0%; height: 100%; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);"></div>
            </div>
        </div>
        
        <div style="margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="font-size: 14px; color: #666;">Aktivitas Selesai</span>
                <span style="font-size: 14px; font-weight: 600; color: #333;">0%</span>
            </div>
            <div style="width: 100%; height: 8px; background-color: #e0e0e0; border-radius: 4px; overflow: hidden;">
                <div style="width: 0%; height: 100%; background: linear-gradient(90deg, #4caf50 0%, #45a049 100%);"></div>
            </div>
        </div>
        
        <div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="font-size: 14px; color: #666;">Follow Up Rate</span>
                <span style="font-size: 14px; font-weight: 600; color: #333;">0%</span>
            </div>
            <div style="width: 100%; height: 8px; background-color: #e0e0e0; border-radius: 4px; overflow: hidden;">
                <div style="width: 0%; height: 100%; background: linear-gradient(90deg, #ff9800 0%, #f57c00 100%);"></div>
            </div>
        </div>
    </div>
</div>
@endsection
