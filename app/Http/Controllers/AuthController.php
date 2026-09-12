<?php

namespace App\Http\Controllers;

// PASTIKAN 4 BARIS INI ADA DI BAGIAN ATAS
use App\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. Menampilkan Halaman Register
    public function showRegister()
    {
        return view('auth.register');
    }

    // 2. Proses Register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Admin Humas', // Default role
        ]);

        // Langsung login setelah daftar
        Auth::login($user);

        // Redirect ke route 'home' sesuai dengan web.php Anda
        return redirect()->route('home')->with('success', 'Akun berhasil dibuat!');
    }

    // 3. Menampilkan Halaman Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // 4. Proses Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Redirect ke route 'home'
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // 5. Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // --- FITUR LUPA SANDI (OTP) ---

    // 6. Menampilkan form input email untuk lupa sandi
    public function showEmailForm()
    {
        return view('auth.passwords.email');
    }

    // 7. Proses pengiriman OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'Alamat email tidak ditemukan dalam sistem kami.'
        ]);

        $otp = rand(100000, 999999);
        
        // Simpan OTP dan waktu kadaluwarsa (misal 5 menit dari sekarang)
        session([
            'reset_email' => $request->email, 
            'reset_otp' => $otp,
            'otp_expires_at' => now()->addMinutes(5)
        ]);
        
        // Atur durasi toast menjadi 10 detik agar pengguna punya waktu membacanya
        session()->flash('duration', 10000);

        return redirect()->route('password.otp')->with('success', 'Kode OTP Anda: ' . $otp);
    }

    // 8. Menampilkan form input OTP dan sandi baru
    public function showOtpForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.passwords.otp');
    }

    // 9. Proses validasi OTP dan ubah kata sandi
    public function resetWithOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 1. Cek apakah OTP sudah kadaluwarsa
        if (now()->greaterThan(session('otp_expires_at'))) {
            session()->forget(['reset_email', 'reset_otp', 'otp_expires_at']);
            return back()->withErrors(['otp' => 'Kode OTP telah kadaluwarsa. Silakan minta kode baru.']);
        }

        // 2. Cek kecocokan OTP
        if ($request->otp != session('reset_otp')) {
            return back()->withErrors(['otp' => 'Kode OTP salah. Pastikan Anda memasukkan kode yang benar.']);
        }

        $user = User::where('email', session('reset_email'))->first();
        
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
            
            // Hapus session OTP setelah berhasil
            session()->forget(['reset_email', 'reset_otp', 'otp_expires_at']);
            
            return redirect()->route('login')->with('success', 'Kata sandi berhasil diubah. Silakan masuk dengan sandi baru Anda.');
        }

        return back()->withErrors(['email' => 'Terjadi kesalahan, pengguna tidak ditemukan.']);
    }
}