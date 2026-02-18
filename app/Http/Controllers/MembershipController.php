<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\MembershipPlan;
use App\Models\Order;
use Illuminate\Http\Request;

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

        $request->validate([
            'transfer_reference' => 'nullable|string|max:255',
        ]);

        $order = Order::create([
            'user_id'            => auth()->id(),
            'plan_id'            => $plan->id,
            'status'             => 'pending',
            'total'              => $plan->price,
            'transfer_reference' => $request->transfer_reference,
        ]);

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
