<?php

use App\Http\Controllers\Auth\AuthControllers;
use Illuminate\Support\Facades\Route;

// Root redirect
Route::get('/', function () {
    return auth()->check() 
        ? redirect()->route('dashboard') 
        : redirect()->route('login');
});

// Auth Routes
Route::controller(AuthControllers::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('register', 'showRegisterForm')->name('register');
        Route::post('register', 'register');
        Route::get('login', 'showLoginForm')->name('login');
        Route::post('login', 'login');

        // Google OAuth
        Route::get('auth/google/redirect', 'redirectToGoogle')->name('google.redirect');
        Route::get('auth/google/callback', 'handleGoogleCallback')->name('google.callback');
        
        // Email Verification Routes
        Route::get('verify', 'showVerifyForm')->name('verification.notice');
        Route::post('verify', 'verify')->name('verification.verify');
        Route::post('verify/resend', 'resend')->name('verification.resend');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', 'logout')->name('logout');
    });
});

// Dashboard - hanya untuk authenticated users
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return view('dashboard', compact('user'));
    })->name('dashboard');
});

// Test Supabase Connection (optional - bisa dihapus jika tidak perlu)
Route::get('/test-supabase', function () {
    try {
        $pdo = DB::connection()->getPdo();
        $dbName = DB::connection()->getDatabaseName();
        $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
        
        return response()->json([
            'status' => 'success',
            'message' => '✅ Koneksi Supabase berhasil!',
            'database' => $dbName,
            'tables' => collect($tables)->pluck('table_name'),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => '❌ Error koneksi: ' . $e->getMessage(),
        ], 500);
    }
});