<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\MembershipPlan;
use App\Models\Order;
use App\Models\Promotion;
use App\Models\PromotionUse;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        return view('membership.checkout', [
            'plan'       => $plan,
            'bankConfig' => $this->bankConfig(),
        ]);
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
