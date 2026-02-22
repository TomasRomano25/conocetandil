<?php

namespace App\Http\Controllers;

use App\Models\HotelOrder;
use App\Models\Order;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;

class MercadoPagoController extends Controller
{
    public function __construct(private MercadoPagoService $mp) {}

    // ───────────────────────────────────────
    // Membership
    // ───────────────────────────────────────

    public function createMembershipPreference(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);
        abort_if(! $order->isPending(), 404);

        if (! $this->mp->isConfigured()) {
            return back()->with('error', 'MercadoPago no está configurado.');
        }

        $order->load('plan');

        $finalAmount = max(0, (float) $order->total - (float) $order->discount);

        try {
            $preference = $this->mp->createPreference(
                items: [['title' => 'Plan ' . $order->plan->name . ' — Conoce Tandil', 'unit_price' => $finalAmount]],
                externalRef: 'membership_' . $order->id,
                successUrl: route('checkout.mp.membership.callback') . '?order_id=' . $order->id . '&status=approved',
                failureUrl:  route('checkout.mp.membership.callback') . '?order_id=' . $order->id . '&status=failure',
                pendingUrl:  route('checkout.mp.membership.callback') . '?order_id=' . $order->id . '&status=pending',
            );
        } catch (\Throwable $e) {
            return back()->with('error', 'Error al conectar con MercadoPago: ' . $e->getMessage());
        }

        $order->update(['mp_preference_id' => $preference['id']]);

        $initPoint = $this->mp->isSandbox()
            ? ($preference['sandbox_init_point'] ?? $preference['init_point'])
            : $preference['init_point'];

        return redirect()->away($initPoint);
    }

    public function membershipCallback(Request $request)
    {
        $status  = $request->query('status');
        $orderId = $request->query('order_id');

        if ($status === 'approved') {
            $order = Order::find($orderId);
            if ($order && $order->user_id === auth()->id() && $order->isPending()) {
                $order->complete();
            }
            return redirect()->route('membership.confirmacion', $orderId)
                ->with('success', 'Pago aprobado por MercadoPago. ¡Tu suscripción fue activada!');
        }

        if ($status === 'pending') {
            return redirect()->route('membership.confirmacion', $orderId)
                ->with('info', 'Tu pago está pendiente de acreditación. Te notificaremos cuando se confirme.');
        }

        return redirect()->route('membership.planes')
            ->with('error', 'El pago no se completó. Podés intentarlo nuevamente.');
    }

    // ───────────────────────────────────────
    // Hotel
    // ───────────────────────────────────────

    public function createHotelPreference(HotelOrder $hotelOrder)
    {
        abort_if($hotelOrder->user_id !== auth()->id(), 403);
        abort_if(! $hotelOrder->isPending(), 404);

        if (! $this->mp->isConfigured()) {
            return back()->with('error', 'MercadoPago no está configurado.');
        }

        $hotelOrder->load(['hotel', 'plan']);

        $finalAmount = max(0, (float) $hotelOrder->amount - (float) $hotelOrder->discount);

        try {
            $preference = $this->mp->createPreference(
                items: [['title' => $hotelOrder->hotel->name . ' — Plan ' . $hotelOrder->plan->name, 'unit_price' => $finalAmount]],
                externalRef: 'hotel_' . $hotelOrder->id,
                successUrl: route('checkout.mp.hotel.callback') . '?order_id=' . $hotelOrder->id . '&status=approved',
                failureUrl:  route('checkout.mp.hotel.callback') . '?order_id=' . $hotelOrder->id . '&status=failure',
                pendingUrl:  route('checkout.mp.hotel.callback') . '?order_id=' . $hotelOrder->id . '&status=pending',
            );
        } catch (\Throwable $e) {
            return back()->with('error', 'Error al conectar con MercadoPago: ' . $e->getMessage());
        }

        $hotelOrder->update(['mp_preference_id' => $preference['id']]);

        $initPoint = $this->mp->isSandbox()
            ? ($preference['sandbox_init_point'] ?? $preference['init_point'])
            : $preference['init_point'];

        return redirect()->away($initPoint);
    }

    public function hotelCallback(Request $request)
    {
        $status  = $request->query('status');
        $orderId = $request->query('order_id');

        if ($status === 'approved') {
            $order = HotelOrder::find($orderId);
            if ($order && $order->user_id === auth()->id() && $order->isPending()) {
                $order->complete();
            }
            return redirect()->route('hoteles.owner.confirmacion', $orderId)
                ->with('success', 'Pago aprobado por MercadoPago. ¡Tu hotel fue activado!');
        }

        if ($status === 'pending') {
            return redirect()->route('hoteles.owner.confirmacion', $orderId)
                ->with('info', 'Tu pago está pendiente de acreditación. Te notificaremos cuando se confirme.');
        }

        return redirect()->route('hoteles.owner.planes')
            ->with('error', 'El pago no se completó. Podés intentarlo nuevamente.');
    }

    // ───────────────────────────────────────
    // Webhook
    // ───────────────────────────────────────

    public function webhook(Request $request)
    {
        try {
            if ($request->input('type') !== 'payment') {
                return response()->json(['ok' => true]);
            }

            $paymentId = $request->input('data.id');
            if (! $paymentId) {
                return response()->json(['ok' => true]);
            }

            $payment = $this->mp->getPayment($paymentId);

            if (($payment['status'] ?? '') !== 'approved') {
                return response()->json(['ok' => true]);
            }

            $ref = $payment['external_reference'] ?? '';

            if (str_starts_with($ref, 'membership_')) {
                $orderId = (int) substr($ref, strlen('membership_'));
                $order   = Order::find($orderId);
                if ($order && $order->isPending()) {
                    $order->complete();
                }
            } elseif (str_starts_with($ref, 'hotel_')) {
                $orderId = (int) substr($ref, strlen('hotel_'));
                $order   = HotelOrder::find($orderId);
                if ($order && $order->isPending()) {
                    $order->complete();
                }
            }
        } catch (\Throwable) {
            // Always return 200 to MP
        }

        return response()->json(['ok' => true]);
    }
}
