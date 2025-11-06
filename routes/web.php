<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UkerController;
use App\Http\Controllers\RMFTController;
use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PenurunanBrilinkController;
use App\Http\Controllers\PenurunanMantriController;
use App\Http\Controllers\PenurunanMerchantMikroController;
use App\Http\Controllers\PenurunanMerchantRitelController;
use App\Http\Controllers\PenurunanNoSegmentMikroController;
use App\Http\Controllers\PenurunanNoSegmentRitelController;
use App\Http\Controllers\PenurunanSmeRitelController;
use App\Http\Controllers\Top10QrisPerUnitController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard - accessible by both but different views
    Route::get('/dashboard', function () {
        if (auth()->user()->isManager()) {
            return view('dashboard.manager');
        }
        return view('dashboard.rmft');
    })->name('dashboard');
    
    // Aktivitas - accessible by both Manager and RMFT
    Route::resource('aktivitas', AktivitasController::class);
    
    // Feedback Routes - RMFT only
    Route::get('aktivitas/{id}/feedback', [AktivitasController::class, 'feedback'])->name('aktivitas.feedback');
    Route::post('aktivitas/{id}/feedback', [AktivitasController::class, 'storeFeedback'])->name('aktivitas.storeFeedback');
    
    // Profile Routes - All authenticated users
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // API for nasabah autocomplete
    Route::get('api/nasabah/search', [NasabahController::class, 'searchByNorek'])->name('api.nasabah.search');
    Route::get('api/nasabah/get', [NasabahController::class, 'getByNorek'])->name('api.nasabah.get');
    
    // Manager and Admin Routes
    Route::middleware(['role:manager,admin'])->group(function () {
        
        // Nasabah Routes
        Route::resource('nasabah', NasabahController::class);
        
        // Akun Routes
        Route::get('akun', [AkunController::class, 'index'])->name('akun.index');
        
        // Uker Routes
        Route::resource('uker', UkerController::class);
        Route::post('uker/import', [UkerController::class, 'import'])->name('uker.import');
        Route::delete('uker-delete-all', [UkerController::class, 'deleteAll'])->name('uker.delete-all');
        
        // RMFT Routes
        Route::resource('rmft', RMFTController::class);
        Route::post('rmft/import', [RMFTController::class, 'import'])->name('rmft.import');
        Route::delete('rmft-delete-all', [RMFTController::class, 'deleteAll'])->name('rmft.delete-all');
        
        // Penurunan Brilink Routes - Admin Only
        Route::middleware(['role:admin'])->group(function () {
            Route::get('penurunan-brilink/import', [PenurunanBrilinkController::class, 'importForm'])->name('penurunan-brilink.import.form');
            Route::post('penurunan-brilink/import', [PenurunanBrilinkController::class, 'import'])->name('penurunan-brilink.import');
            Route::resource('penurunan-brilink', PenurunanBrilinkController::class);
            
            // Penurunan Mantri Routes - Admin Only
            Route::get('penurunan-mantri/import', [PenurunanMantriController::class, 'importForm'])->name('penurunan-mantri.import.form');
            Route::post('penurunan-mantri/import', [PenurunanMantriController::class, 'import'])->name('penurunan-mantri.import');
            Route::resource('penurunan-mantri', PenurunanMantriController::class);
            
            // Penurunan Merchant Mikro Routes - Admin Only
            Route::get('penurunan-merchant-mikro/import', [PenurunanMerchantMikroController::class, 'importForm'])->name('penurunan-merchant-mikro.import.form');
            Route::post('penurunan-merchant-mikro/import', [PenurunanMerchantMikroController::class, 'import'])->name('penurunan-merchant-mikro.import');
            Route::resource('penurunan-merchant-mikro', PenurunanMerchantMikroController::class);
            
            // Penurunan Merchant Ritel Routes - Admin Only
            Route::get('penurunan-merchant-ritel/import', [PenurunanMerchantRitelController::class, 'importForm'])->name('penurunan-merchant-ritel.import.form');
            Route::post('penurunan-merchant-ritel/import', [PenurunanMerchantRitelController::class, 'import'])->name('penurunan-merchant-ritel.import');
            Route::resource('penurunan-merchant-ritel', PenurunanMerchantRitelController::class);
            
            // Penurunan No-Segment Mikro Routes - Admin Only
            Route::get('penurunan-no-segment-mikro/import', [PenurunanNoSegmentMikroController::class, 'importForm'])->name('penurunan-no-segment-mikro.import.form');
            Route::post('penurunan-no-segment-mikro/import', [PenurunanNoSegmentMikroController::class, 'import'])->name('penurunan-no-segment-mikro.import');
            Route::resource('penurunan-no-segment-mikro', PenurunanNoSegmentMikroController::class);
            
            // Penurunan No-Segment Ritel Routes - Admin Only
            Route::get('penurunan-no-segment-ritel/import', [PenurunanNoSegmentRitelController::class, 'importForm'])->name('penurunan-no-segment-ritel.import.form');
            Route::post('penurunan-no-segment-ritel/import', [PenurunanNoSegmentRitelController::class, 'import'])->name('penurunan-no-segment-ritel.import');
            Route::resource('penurunan-no-segment-ritel', PenurunanNoSegmentRitelController::class);
            
            // Penurunan SME Ritel Routes - Admin Only
            Route::get('penurunan-sme-ritel/import', [PenurunanSmeRitelController::class, 'importForm'])->name('penurunan-sme-ritel.import.form');
            Route::post('penurunan-sme-ritel/import', [PenurunanSmeRitelController::class, 'import'])->name('penurunan-sme-ritel.import');
            Route::resource('penurunan-sme-ritel', PenurunanSmeRitelController::class);
            
            // Top 10 QRIS Per Unit Routes - Admin Only
            Route::get('top10-qris-per-unit/import', [Top10QrisPerUnitController::class, 'importForm'])->name('top10-qris-per-unit.import-form');
            Route::post('top10-qris-per-unit/import', [Top10QrisPerUnitController::class, 'import'])->name('top10-qris-per-unit.import');
            Route::resource('top10-qris-per-unit', Top10QrisPerUnitController::class);
        });
    });
});
