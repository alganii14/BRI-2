<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AkunController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Admin melihat semua akun
        // Manager hanya lihat akun di KC mereka
        if ($user->isAdmin()) {
            $managers = User::where('role', 'manager')
                            ->orderBy('name', 'asc')
                            ->paginate(20, ['*'], 'managers_page');
            
            $rmfts = User::where('role', 'rmft')
                         ->with('rmftData')
                         ->orderBy('name', 'asc')
                         ->paginate(20, ['*'], 'rmfts_page');
        } elseif ($user->isManager() && $user->kode_kanca) {
            $managers = User::where('role', 'manager')
                            ->where('kode_kanca', $user->kode_kanca)
                            ->orderBy('name', 'asc')
                            ->paginate(20, ['*'], 'managers_page');
            
            $rmfts = User::where('role', 'rmft')
                         ->where('kode_kanca', $user->kode_kanca)
                         ->with('rmftData')
                         ->orderBy('name', 'asc')
                         ->paginate(20, ['*'], 'rmfts_page');
        } else {
            // Fallback jika bukan admin atau manager
            $managers = collect();
            $rmfts = collect();
        }
        
        return view('akun.index', compact('managers', 'rmfts'));
    }
}
