<?php

namespace App\Http\Controllers;

use App\Mail\Admin\NewOrderMail;
use App\Models\Configuration;
use App\Models\MembershipPlan;
use App\Models\Order;
use App\Models\Promotion;
use App\Models\PromotionUse;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class MembershipController extends Controller
{
    public function planes()
    {
        $plans = MembershipPlan::active()->ordered()->get();
        return view('membership.planes', compact('plans'));
    }

    public function checkout(MembershipPlan $plan)
    {
        abort_if(! $plan->active, 404);

        // If already authenticated, go straight to checkout
        if (auth()->check()) {
            return view('membership.checkout', [
                'plan'       => $plan,
                'bankConfig' => $this->bankConfig(),
            ]);
        }

        // Guest: show inline auth form
        return view('membership.checkout', [
            'plan'       => $plan,
            'bankConfig' => $this->bankConfig(),
            'showAuth'   => true,
        ]);
    }

    public function checkoutRegister(Request $request, MembershipPlan $plan)
    {
        abort_if(! $plan->active, 404);

        $mode = $request->input('auth_mode', 'register');

        if ($mode === 'login') {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required|string',
            ]);

            if (! Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return back()->withErrors(['login_password' => 'Email o contraseña incorrectos.'])->withInput();
            }

            return redirect()->route('membership.checkout', $plan->slug);
        }

        // Register mode
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique'       => 'Ya existe una cuenta con ese email. ¿Querés iniciar sesión?',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('membership.checkout', $plan->slug);
    }

    public function store(Request $request, MembershipPlan $plan)
    {
        abort_if(! $plan->active, 404);

        if (!$this->verifyCaptcha($request)) {
            return back()->withErrors(['captcha' => 'Verificación de seguridad fallida. Intentá de nuevo.'])->withInput();
        }

        $request->validate([
            'transfer_reference' => 'nullable|string|max:255',
            'promotion_id'       => 'nullable|integer|exists:promotions,id',
        ]);

        // Reuse existing pending order for this user+plan to avoid duplicate orders
        $existingOrder = Order::where('user_id', auth()->id())
            ->where('plan_id', $plan->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        $discount = 0;
        $promotionId = null;

        if ($request->filled('promotion_id')) {
            $promo = Promotion::find($request->promotion_id);
            if ($promo) {
                $result = $promo->validateForCheckout('membership', $plan->id, (float) $plan->effective_price, auth()->id());
                if ($result['valid']) {
                    $discount    = $result['discount'];
                    $promotionId = $promo->id;
                }
            }
        }

        if ($existingOrder) {
            $existingOrder->update([
                'total'              => $plan->effective_price,
                'discount'           => $discount,
                'promotion_id'       => $promotionId,
                'transfer_reference' => $request->transfer_reference,
            ]);
            $order = $existingOrder;
        } else {
            $order = Order::create([
                'user_id'            => auth()->id(),
                'plan_id'            => $plan->id,
                'status'             => 'pending',
                'total'              => $plan->effective_price,
                'discount'           => $discount,
                'promotion_id'       => $promotionId,
                'transfer_reference' => $request->transfer_reference,
            ]);

            if ($promotionId) {
                PromotionUse::create([
                    'promotion_id'    => $promotionId,
                    'user_id'         => auth()->id(),
                    'orderable_type'  => 'membership',
                    'orderable_id'    => $order->id,
                    'discount_amount' => $discount,
                ]);
                Promotion::find($promotionId)->increment('uses_count');
            }

            if (Configuration::get('smtp_host')) {
                $adminEmail = Configuration::get('smtp_from_email');
                if ($adminEmail) {
                    try { Mail::to($adminEmail)->send(new NewOrderMail($order->load('plan', 'user'), 'membership')); } catch (\Throwable) {}
                }
            }
        }

        // If paying via MercadoPago, create preference and redirect directly
        if ($request->boolean('redirect_to_mp')) {
            $mp = app(MercadoPagoService::class);
            if (! $mp->isConfigured()) {
                return redirect()->route('membership.checkout', $plan->slug)
                    ->with('error', 'MercadoPago no está configurado correctamente. Contactá al administrador.')
                    ->with('open_mp_tab', true);
            }
            $finalAmount = max(0.01, (float) $order->total - (float) $order->discount);
            try {
                $preference = $mp->createPreference(
                    items: [['title' => 'Plan ' . $plan->name . ' — Conoce Tandil', 'unit_price' => $finalAmount]],
                    externalRef: 'membership_' . $order->id,
                    successUrl: route('checkout.mp.membership.callback') . '?order_id=' . $order->id . '&status=approved',
                    failureUrl:  route('checkout.mp.membership.callback') . '?order_id=' . $order->id . '&status=failure',
                    pendingUrl:  route('checkout.mp.membership.callback') . '?order_id=' . $order->id . '&status=pending',
                );
                $order->update(['mp_preference_id' => $preference['id']]);
                $initPoint = $mp->isSandbox()
                    ? ($preference['sandbox_init_point'] ?? $preference['init_point'])
                    : $preference['init_point'];
                return redirect()->away($initPoint);
            } catch (\Throwable $e) {
                return redirect()->route('membership.checkout', $plan->slug)
                    ->with('error', 'Error al conectar con MercadoPago: ' . $e->getMessage())
                    ->with('open_mp_tab', true);
            }
        }

        return redirect()->route('membership.confirmacion', $order);
    }

    public function confirmacion(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);
        return view('membership.confirmacion', [
            'order'      => $order->load('plan'),
            'bankConfig' => $this->bankConfig(),
        ]);
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

    private function bankConfig(): array
    {
        return [
            'bank_name'      => Configuration::get('bank_name', ''),
            'account_holder' => Configuration::get('bank_account_holder', ''),
            'cbu'            => Configuration::get('bank_cbu', ''),
            'alias'          => Configuration::get('bank_alias', ''),
            'account_number' => Configuration::get('bank_account_number', ''),
            'instructions'   => Configuration::get('bank_instructions', ''),
        ];
    }
}
