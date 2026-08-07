<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $request->session()->put('captcha', $this->generateCaptcha());
        return view('auth.login');
    }

    private function generateCaptcha(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = '';
        for ($i = 0; $i < 5; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $code;
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|string',
        ]);

        $captchaValid = $request->captcha === $request->session()->get('captcha');
        $request->session()->put('captcha', $this->generateCaptcha());

        if (! $captchaValid) {
            return back()->withErrors(['captcha' => 'Kode captcha tidak sesuai.'])->onlyInput('username');
        }

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->id_role == 1) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('mahasiswa.dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetToken(Request $request)
    {
        $request->validate(['username' => 'required|string']);
        $user = User::where('username', $request->username)->first();

        if (! $user) {
            return back()->withErrors(['username' => 'Username tidak ditemukan.'])->onlyInput('username');
        }

        $token = Str::random(40);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->username],
            ['token' => $token, 'created_at' => now()]
        );

        return view('auth.reset-link-sent', ['token' => $token]);
    }

    public function showResetForm(string $token)
    {
        $record = DB::table('password_reset_tokens')->where('token', $token)->first();

        if (! $record || now()->diffInMinutes($record->created_at) > 60) {
            return redirect()->route('password.request')->withErrors([
                'username' => 'Link reset sudah kedaluwarsa, silakan ajukan ulang.',
            ]);
        }

        return view('auth.reset-password', ['token' => $token, 'username' => $record->email]);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')->where('token', $validated['token'])->first();

        if (! $record || now()->diffInMinutes($record->created_at) > 60) {
            return redirect()->route('password.request')->withErrors([
                'username' => 'Link reset sudah kedaluwarsa, silakan ajukan ulang.',
            ]);
        }

        $user = User::where('username', $record->email)->first();
        $user->password = Hash::make($validated['password']);
        $user->save();

        DB::table('password_reset_tokens')->where('token', $validated['token'])->delete();

        return redirect()->route('login')->with('success', 'Password berhasil direset, silakan login dengan password baru.');
    }
}