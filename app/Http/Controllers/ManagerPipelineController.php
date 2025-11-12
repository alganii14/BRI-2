<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenurunanBrilink;
use App\Models\PenurunanMantri;
use App\Models\PenurunanMerchantMikro;
use App\Models\PenurunanMerchantRitel;
use App\Models\PenurunanRitel;
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
        $month = $request->get('month');
        $year = $request->get('year');
        
        $query = PenurunanBrilink::where('kode_cabang_induk', $kodeKc);
        
        // Filter by year
        if ($year) {
            $query->whereYear('created_at', $year);
        }
        
        // Filter by month
        if ($month) {
            $query->whereMonth('created_at', $month);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_nasabah', 'like', "%{$search}%")
                  ->orWhere('no_rekening', 'like', "%{$search}%")
                  ->orWhere('cifno', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%");
            });
        }
        
        $data = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get available years
        $availableYears = PenurunanBrilink::where('kode_cabang_induk', $kodeKc)
            ->selectRaw('DISTINCT YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        return view('manager-pipeline.brilink', compact('data', 'search', 'month', 'year', 'availableYears'));
    }
    
    // Penurunan Mantri
    public function mantri(Request $request)
    {
        $user = auth()->user();
        $kodeKc = $user->kode_kanca;
        $search = $request->get('search');
        $month = $request->get('month');
        $year = $request->get('year');
        
        $query = PenurunanMantri::where('kode_cabang_induk', $kodeKc);
        
        // Filter by year
        if ($year) {
            $query->whereYear('created_at', $year);
        }
        
        // Filter by month
        if ($month) {
            $query->whereMonth('created_at', $month);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_nasabah', 'like', "%{$search}%")
                  ->orWhere('no_rekening', 'like', "%{$search}%")
                  ->orWhere('cifno', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%");
            });
        }
        
        $data = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get available years
        $availableYears = PenurunanMantri::where('kode_cabang_induk', $kodeKc)
            ->selectRaw('DISTINCT YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        return view('manager-pipeline.mantri', compact('data', 'search', 'month', 'year', 'availableYears'));
    }
    
    // Penurunan Merchant Mikro
    public function merchantMikro(Request $request)
    {
        $user = auth()->user();
        $kodeKc = $user->kode_kanca;
        $search = $request->get('search');
        $month = $request->get('month');
        $year = $request->get('year');
        
        $query = PenurunanMerchantMikro::where('kode_cabang_induk', $kodeKc);
        
        // Filter by year
        if ($year) {
            $query->whereYear('created_at', $year);
        }
        
        // Filter by month
        if ($month) {
            $query->whereMonth('created_at', $month);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_nasabah', 'like', "%{$search}%")
                  ->orWhere('no_rekening', 'like', "%{$search}%")
                  ->orWhere('cifno', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%");
            });
        }
        
        $data = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get available years
        $availableYears = PenurunanMerchantMikro::where('kode_cabang_induk', $kodeKc)
            ->selectRaw('DISTINCT YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        return view('manager-pipeline.merchant-mikro', compact('data', 'search', 'month', 'year', 'availableYears'));
    }
    
    // Penurunan Merchant Ritel
    public function merchantRitel(Request $request)
    {
        $user = auth()->user();
        $kodeKc = $user->kode_kanca;
        $search = $request->get('search');
        $month = $request->get('month');
        $year = $request->get('year');
        
        $query = PenurunanMerchantRitel::where('kode_cabang_induk', $kodeKc);
        
        // Filter by year
        if ($year) {
            $query->whereYear('created_at', $year);
        }
        
        // Filter by month
        if ($month) {
            $query->whereMonth('created_at', $month);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_nasabah', 'like', "%{$search}%")
                  ->orWhere('no_rekening', 'like', "%{$search}%")
                  ->orWhere('cifno', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%");
            });
        }
        
        $data = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get available years
        $availableYears = PenurunanMerchantRitel::where('kode_cabang_induk', $kodeKc)
            ->selectRaw('DISTINCT YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        return view('manager-pipeline.merchant-ritel', compact('data', 'search', 'month', 'year', 'availableYears'));
    }
    
    // Penurunan Ritel
    public function ritel(Request $request)
    {
        $user = auth()->user();
        $kodeKc = $user->kode_kanca;
        $search = $request->get('search');
        $month = $request->get('month');
        $year = $request->get('year');
        
        $query = PenurunanRitel::where('kode_cabang_induk', $kodeKc);
        
        // Filter by year
        if ($year) {
            $query->whereYear('created_at', $year);
        }
        
        // Filter by month
        if ($month) {
            $query->whereMonth('created_at', $month);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_nasabah', 'like', "%{$search}%")
                  ->orWhere('no_rekening', 'like', "%{$search}%")
                  ->orWhere('cifno', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%");
            });
        }
        
        $data = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get available years
        $availableYears = PenurunanRitel::where('kode_cabang_induk', $kodeKc)
            ->selectRaw('DISTINCT YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        return view('manager-pipeline.ritel', compact('data', 'search', 'month', 'year', 'availableYears'));
    }
    
    // Penurunan SME Ritel
    public function smeRitel(Request $request)
    {
        $user = auth()->user();
        $kodeKc = $user->kode_kanca;
        $search = $request->get('search');
        $month = $request->get('month');
        $year = $request->get('year');
        
        $query = PenurunanSmeRitel::where('kode_cabang_induk', $kodeKc);
        
        // Filter by year
        if ($year) {
            $query->whereYear('created_at', $year);
        }
        
        // Filter by month
        if ($month) {
            $query->whereMonth('created_at', $month);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_nasabah', 'like', "%{$search}%")
                  ->orWhere('no_rekening', 'like', "%{$search}%")
                  ->orWhere('cifno', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%");
            });
        }
        
        $data = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get available years
        $availableYears = PenurunanSmeRitel::where('kode_cabang_induk', $kodeKc)
            ->selectRaw('DISTINCT YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        return view('manager-pipeline.sme-ritel', compact('data', 'search', 'month', 'year', 'availableYears'));
    }
    
    // Top 10 QRIS Per Unit
    public function qris(Request $request)
    {
        $user = auth()->user();
        $kodeKc = $user->kode_kanca;
        $search = $request->get('search');
        $month = $request->get('month');
        $year = $request->get('year');
        
        $query = Top10QrisPerUnit::where('mainbr', $kodeKc);
        
        // Filter by year
        if ($year) {
            $query->whereYear('created_at', $year);
        }
        
        // Filter by month
        if ($month) {
            $query->whereMonth('created_at', $month);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_merchant', 'like', "%{$search}%")
                  ->orWhere('no_rek', 'like', "%{$search}%")
                  ->orWhere('cif', 'like', "%{$search}%")
                  ->orWhere('brdesc', 'like', "%{$search}%");
            });
        }
        
        $data = $query->orderBy('rank', 'asc')->paginate(20);
        
        // Get available years
        $availableYears = Top10QrisPerUnit::where('mainbr', $kodeKc)
            ->selectRaw('DISTINCT YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        return view('manager-pipeline.qris', compact('data', 'search', 'month', 'year', 'availableYears'));
    }
}
