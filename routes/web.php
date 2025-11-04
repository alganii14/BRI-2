<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UkerController;
use App\Http\Controllers\RMFTController;
use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\ProfileController;

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
    });
});
