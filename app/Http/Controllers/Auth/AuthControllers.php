<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\EmailVerification;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthControllers extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Buat user baru (belum terverifikasi)
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin', // Default role
            'email_verified_at' => null, // Belum terverifikasi
        ]);

        // Generate kode verifikasi 6 digit
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Hapus kode verifikasi lama jika ada
        EmailVerification::where('user_id', $user->id)->delete();

        // Simpan kode verifikasi baru
        EmailVerification::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(15),
        ]);

        // Kirim email verifikasi
        try {
            Mail::to($user->email)->send(new VerificationCodeMail($code, $user->name));
        } catch (\Exception $e) {
            // Log error tapi tetap lanjutkan proses
            Log::error('Email verification failed: ' . $e->getMessage());
        }

        // Simpan user_id di session untuk proses verifikasi
        session(['verification_user_id' => $user->id]);

        return redirect()->route('verification.notice')->with('status', 'Kode verifikasi telah dikirim ke email Anda. Silakan cek inbox atau spam folder.');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended('dashboard')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Anda telah logout.');
    }

    // =================== EMAIL VERIFICATION METHODS ===================

    /**
     * Tampilkan halaman input kode verifikasi
     */
    public function showVerifyForm()
    {
        $userId = session('verification_user_id');
        
        if (!$userId) {
            return redirect()->route('login')->withErrors(['email' => 'Sesi verifikasi tidak ditemukan. Silakan login atau daftar kembali.']);
        }

        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('register')->withErrors(['email' => 'User tidak ditemukan.']);
        }

        // Jika sudah terverifikasi, redirect ke dashboard
        if ($user->email_verified_at) {
            Auth::login($user);
            session()->forget('verification_user_id');
            return redirect()->route('dashboard')->with('success', 'Email Anda sudah terverifikasi.');
        }

        return view('auth.verify', compact('user'));
    }

    /**
     * Proses verifikasi kode
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6', 'regex:/^[0-9]{6}$/'],
        ], [
            'code.required' => 'Kode verifikasi harus diisi.',
            'code.size' => 'Kode verifikasi harus 6 digit.',
            'code.regex' => 'Kode verifikasi harus berupa angka.',
        ]);

        $userId = session('verification_user_id');
        
        if (!$userId) {
            return redirect()->route('login')->withErrors(['email' => 'Sesi verifikasi tidak ditemukan.']);
        }

        // Cari kode verifikasi
        $verification = EmailVerification::where('user_id', $userId)
            ->where('code', $request->code)
            ->first();

        if (!$verification) {
            return back()->withErrors(['code' => 'Kode verifikasi salah. Silakan coba lagi.']);
        }

        // Cek apakah kode sudah kadaluarsa
        if ($verification->isExpired()) {
            $verification->delete();
            return back()->withErrors(['code' => 'Kode verifikasi telah kadaluarsa. Silakan kirim ulang kode baru.']);
        }

        // Verifikasi berhasil - update user
        $user = User::find($userId);
        $user->email_verified_at = now();
        $user->save();

        // Hapus kode verifikasi
        $verification->delete();
        
        // Hapus session verifikasi
        session()->forget('verification_user_id');

        // Login user
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', '🎉 Email berhasil diverifikasi! Selamat datang di ROMS.');
    }

    /**
     * Kirim ulang kode verifikasi
     */
    public function resend(Request $request)
    {
        $userId = session('verification_user_id');
        
        if (!$userId) {
            return redirect()->route('login')->withErrors(['email' => 'Sesi verifikasi tidak ditemukan.']);
        }

        $user = User::find($userId);
        
        if (!$user) {
            return redirect()->route('register')->withErrors(['email' => 'User tidak ditemukan.']);
        }

        // Cek apakah sudah terverifikasi
        if ($user->email_verified_at) {
            Auth::login($user);
            session()->forget('verification_user_id');
            return redirect()->route('dashboard')->with('success', 'Email Anda sudah terverifikasi.');
        }

        // Generate kode baru
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Hapus kode lama dan buat baru
        EmailVerification::where('user_id', $user->id)->delete();
        
        EmailVerification::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(15),
        ]);

        // Kirim email
        try {
            Mail::to($user->email)->send(new VerificationCodeMail($code, $user->name));
            return back()->with('status', '✉️ Kode verifikasi baru telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            Log::error('Resend verification failed: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Gagal mengirim email. Silakan coba lagi.']);
        }
    }

    // --- [METODE BARU UNTUK GOOGLE AUTH] ---

    /**
     * Arahkan pengguna ke halaman autentikasi Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Dapatkan informasi pengguna dari Google dan tangani login/register.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // 1. Cari pengguna berdasarkan google_id
            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                // 2. Jika ketemu, langsung login
                Auth::login($user);
                return redirect()->intended('dashboard')->with('success', 'Login berhasil!');
            }

            // 3. Jika tidak ketemu by google_id, cari berdasarkan email
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // 4. Jika email ketemu (akun dibuat manual), update google_id-nya
                $user->update(['google_id' => $googleUser->id]);
            } else {
                // 5. Jika tidak ada sama sekali, buat pengguna baru
                $user = User::create([
                    'google_id' => $googleUser->id,
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make(Str::random(16)), // Buat password acak
                    'email_verified_at' => now(), // Anggap sudah terverifikasi
                    'role' => 'admin', // Default role
                ]);
            }

            // 6. Login pengguna baru
            Auth::login($user);
            return redirect()->intended('dashboard')->with('success', 'Login berhasil! Selamat datang.');

        } catch (\Exception $e) {
            // Jika ada error, kembali ke login
            return redirect()->route('login')->withErrors([
                'email' => 'Gagal login dengan Google: ' . $e->getMessage()
            ]);
        }
    }
}