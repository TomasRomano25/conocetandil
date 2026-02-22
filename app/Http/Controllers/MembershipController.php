<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\MembershipPlan;
use App\Models\Order;
use App\Models\Promotion;
use App\Models\PromotionUse;
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

        // If paying via MercadoPago, redirect to MP preference creation
        if ($request->boolean('redirect_to_mp')) {
            return redirect()->route('checkout.mp.membership', $order);
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
