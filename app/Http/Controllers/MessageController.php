<?php

namespace App\Http\Controllers;

use App\Mail\NewMessageNotification;
use App\Models\Configuration;
use App\Models\Form;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    public function store(Request $request, string $slug)
    {
        if (!$this->verifyCaptcha($request)) {
            return back()->withErrors(['captcha' => 'Verificación de seguridad fallida. Intentá de nuevo.'])->withInput();
        }

        $form = Form::where('slug', $slug)->where('active', true)->firstOrFail();

        // Build validation rules from visible + required fields
        $rules = [];
        foreach ($form->visibleFields as $field) {
            $rule = $field->required ? 'required' : 'nullable';
            $rule .= match ($field->type) {
                'email'    => '|email|max:255',
                'textarea' => '|string|max:5000',
                default    => '|string|max:1000',
            };
            $rules[$field->name] = $rule;
        }

        $validated = $request->validate($rules);

        // Only store data for visible fields
        $data = [];
        foreach ($form->visibleFields as $field) {
            $data[$field->name] = $validated[$field->name] ?? null;
        }

        $message = Message::create([
            'form_id'    => $form->id,
            'data'       => $data,
            'ip_address' => $request->ip(),
        ]);

        // Send notification email if configured
        if ($form->send_notification) {
            $recipient = $form->notification_email
                ?? Configuration::get('smtp_from_email')
                ?? config('mail.from.address');

            if ($recipient) {
                try {
                    $this->applySmtpConfig();
                    Mail::to($recipient)->send(new NewMessageNotification($message, $form));
                } catch (\Throwable) {
                    // Silently fail — message is already saved
                }
            }
        }

        return back()->with('success', '¡Mensaje enviado correctamente! Te responderemos a la brevedad.');
    }

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
        $keys = ['host', 'port', 'encryption', 'username', 'password', 'from_email', 'from_name'];

        foreach ($keys as $key) {
            $value = Configuration::get("smtp_{$key}");
            if ($value === null) {
                continue;
            }
            match ($key) {
                'host'       => config(['mail.mailers.smtp.host' => $value]),
                'port'       => config(['mail.mailers.smtp.port' => (int) $value]),
                'encryption' => config(['mail.mailers.smtp.encryption' => $value]),
                'username'   => config(['mail.mailers.smtp.username' => $value]),
                'password'   => config(['mail.mailers.smtp.password' => $value]),
                'from_email' => config(['mail.from.address' => $value]),
                'from_name'  => config(['mail.from.name' => $value]),
            };
        }
    }
}
