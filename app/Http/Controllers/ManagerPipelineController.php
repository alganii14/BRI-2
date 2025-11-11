<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenurunanBrilink;
use App\Models\PenurunanMantri;
use App\Models\PenurunanMerchantMikro;
use App\Models\PenurunanMerchantRitel;
use App\Models\PenurunanNoSegmentMikro;
use App\Models\PenurunanNoSegmentRitel;
use App\Models\PenurunanSmeRitel;
use App\Models\Top10QrisPerUnit;

class ManagerPipelineController extends Controller
{
    // Penurunan Brilink
    public function brilink(Request $request)
    {
        $user = auth()->user();
        $kodeKc = $user->kode_kanca;
        $search = $request->get('search');
        
        $query = PenurunanBrilink::where('kode_cabang_induk', $kodeKc);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_nasabah', 'like', "%{$search}%")
                  ->orWhere('no_rekening', 'like', "%{$search}%")
                  ->orWhere('cifno', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%");
            });
        }
        
        $data = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('manager-pipeline.brilink', compact('data', 'search'));
    }
    
    // Penurunan Mantri
    public function mantri(Request $request)
    {
        $user = auth()->user();
        $kodeKc = $user->kode_kanca;
        $search = $request->get('search');
        
        $query = PenurunanMantri::where('kode_cabang_induk', $kodeKc);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_nasabah', 'like', "%{$search}%")
                  ->orWhere('no_rekening', 'like', "%{$search}%")
                  ->orWhere('cifno', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%");
            });
        }
        
        $data = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('manager-pipeline.mantri', compact('data', 'search'));
    }
    
    // Penurunan Merchant Mikro
    public function merchantMikro(Request $request)
    {
        $user = auth()->user();
        $kodeKc = $user->kode_kanca;
        $search = $request->get('search');
        
        $query = PenurunanMerchantMikro::where('kode_cabang_induk', $kodeKc);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_nasabah', 'like', "%{$search}%")
                  ->orWhere('no_rekening', 'like', "%{$search}%")
                  ->orWhere('cifno', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%");
            });
        }
        
        $data = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('manager-pipeline.merchant-mikro', compact('data', 'search'));
    }
    
    // Penurunan Merchant Ritel
    public function merchantRitel(Request $request)
    {
        $user = auth()->user();
        $kodeKc = $user->kode_kanca;
        $search = $request->get('search');
        
        $query = PenurunanMerchantRitel::where('kode_cabang_induk', $kodeKc);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_nasabah', 'like', "%{$search}%")
                  ->orWhere('no_rekening', 'like', "%{$search}%")
                  ->orWhere('cifno', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%");
            });
        }
        
        $data = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('manager-pipeline.merchant-ritel', compact('data', 'search'));
    }
    
    // Penurunan No Segment Mikro
    public function noSegmentMikro(Request $request)
    {
        $user = auth()->user();
        $kodeKc = $user->kode_kanca;
        $search = $request->get('search');
        
        $query = PenurunanNoSegmentMikro::where('kode_cabang_induk', $kodeKc);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_nasabah', 'like', "%{$search}%")
                  ->orWhere('no_rekening', 'like', "%{$search}%")
                  ->orWhere('cifno', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%");
            });
        }
        
        $data = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('manager-pipeline.no-segment-mikro', compact('data', 'search'));
    }
    
    // Penurunan No Segment Ritel
    public function noSegmentRitel(Request $request)
    {
        $user = auth()->user();
        $kodeKc = $user->kode_kanca;
        $search = $request->get('search');
        
        $query = PenurunanNoSegmentRitel::where('kode_cabang_induk', $kodeKc);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_nasabah', 'like', "%{$search}%")
                  ->orWhere('no_rekening', 'like', "%{$search}%")
                  ->orWhere('cifno', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%");
            });
        }
        
        $data = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('manager-pipeline.no-segment-ritel', compact('data', 'search'));
    }
    
    // Penurunan SME Ritel
    public function smeRitel(Request $request)
    {
        $user = auth()->user();
        $kodeKc = $user->kode_kanca;
        $search = $request->get('search');
        
        $query = PenurunanSmeRitel::where('kode_cabang_induk', $kodeKc);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_nasabah', 'like', "%{$search}%")
                  ->orWhere('no_rekening', 'like', "%{$search}%")
                  ->orWhere('cifno', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%");
            });
        }
        
        $data = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('manager-pipeline.sme-ritel', compact('data', 'search'));
    }
    
    // Top 10 QRIS Per Unit
    public function qris(Request $request)
    {
        $user = auth()->user();
        $kodeKc = $user->kode_kanca;
        $search = $request->get('search');
        
        $query = Top10QrisPerUnit::where('mainbr', $kodeKc);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_merchant', 'like', "%{$search}%")
                  ->orWhere('no_rek', 'like', "%{$search}%")
                  ->orWhere('cif', 'like', "%{$search}%")
                  ->orWhere('brdesc', 'like', "%{$search}%");
            });
        }
        
        $data = $query->orderBy('rank', 'asc')->paginate(20);
        
        return view('manager-pipeline.qris', compact('data', 'search'));
    }
}
