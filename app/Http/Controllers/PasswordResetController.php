<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class PasswordResetController extends Controller
{
    public function request()
    {
        return view('auth.forgot_password');
    }

    public function email(Request $request)
    {
        $request->validate([
            'identity' => 'required|string',
        ], [
            'identity.required' => 'Username / NIM / NIDN / Email wajib diisi',
        ]);

        $identity = trim((string) $request->identity);

        $user = User::where('email', $identity)
            ->orWhere('username', $identity)
            ->orWhere('nim', $identity)
            ->orWhere('nidn', $identity)
            ->first();

        if (!$user) {
            return back()->withErrors(['identity' => 'Akun tidak ditemukan.'])->withInput();
        }

        $token = (string) random_int(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]
        );

        Mail::send('emails.password_reset_token', [
            'name' => $user->name ?? 'Pengguna',
            'token' => $token,
        ], function ($message) use ($user) {
            $message->to($user->email, $user->name)
                ->subject('Kode Reset Password Z-Study');
        });

        return back()
            ->with('status', 'Kode reset telah dikirim ke email Anda.')
            ->with('reset_email', $user->email);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|digits:6',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'token.required' => 'Token wajib diisi',
            'token.digits' => 'Token harus 6 digit',
        ]);

        $row = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$row || !Hash::check($request->token, $row->token)) {
            return back()->withErrors(['token' => 'Token reset tidak valid.'])->withInput();
        }

        $request->session()->put('reset_email', $request->email);
        $request->session()->put('reset_verified', true);

        return redirect()->route('password.reset', ['token' => 'verified', 'email' => $request->email]);
    }

    public function reset(Request $request, string $token)
    {
        return view('auth.reset_password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password baru wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        $verified = $request->session()->get('reset_verified');
        $email = $request->session()->get('reset_email');
        if (!$verified || $email !== $request->email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Silakan verifikasi token terlebih dahulu.']);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.'])->withInput();
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        $request->session()->forget(['reset_verified', 'reset_email']);
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password berhasil direset.');
    }
}
