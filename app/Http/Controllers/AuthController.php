<?php

namespace App\Http\Controllers;

use App\Mail\Admin\NewUserMail;
use App\Models\Configuration;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ─── Login ──────────────────────────────────────────────────────────────

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (auth()->user()->is_admin) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('premium.upsell'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // ─── Registration ───────────────────────────────────────────────────────

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        if (!$this->verifyCaptcha($request)) {
            return back()->withErrors(['captcha' => 'Verificación de seguridad fallida. Intentá de nuevo.'])->withInput();
        }

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);

        if (Configuration::get('smtp_host')) {
            $adminEmail = Configuration::get('smtp_from_email');
            if ($adminEmail) {
                try { Mail::to($adminEmail)->send(new NewUserMail($user)); } catch (\Throwable) {}
            }
        }

        return redirect()->intended(route('premium.upsell'));
    }

    // ─── Forgot password ────────────────────────────────────────────────────

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $this->applySmtpConfig();

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Te enviamos un email con el enlace para restablecer tu contraseña.')
            : back()->withErrors(['email' => 'No encontramos un usuario con ese email.']);
    }

    // ─── Reset password ─────────────────────────────────────────────────────

    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Tu contraseña fue restablecida. Podés iniciar sesión.')
            : back()->withErrors(['email' => __($status)]);
    }

    // ─── Helpers ────────────────────────────────────────────────────────────

    private function verifyCaptcha(Request $request): bool
    {
        $secret = Configuration::get('recaptcha_secret_key');
        if (!$secret) return true;
        $resp = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => $secret,
            'response' => $request->input('g-recaptcha-response', ''),
        ]);
        $data = $resp->json();
        return ($data['success'] ?? false) && ($data['score'] ?? 0) >= 0.5;
    }

    private function applySmtpConfig(): void
    {
        config([
            'mail.mailers.smtp.host'       => Configuration::get('smtp_host',       config('mail.mailers.smtp.host')),
            'mail.mailers.smtp.port'       => Configuration::get('smtp_port',       config('mail.mailers.smtp.port')),
            'mail.mailers.smtp.encryption' => Configuration::get('smtp_encryption', config('mail.mailers.smtp.encryption')),
            'mail.mailers.smtp.username'   => Configuration::get('smtp_username',   config('mail.mailers.smtp.username')),
            'mail.mailers.smtp.password'   => Configuration::get('smtp_password',   config('mail.mailers.smtp.password')),
            'mail.from.address'            => Configuration::get('smtp_from_email', config('mail.from.address')),
            'mail.from.name'               => Configuration::get('smtp_from_name',  config('mail.from.name')),
        ]);
    }
}
