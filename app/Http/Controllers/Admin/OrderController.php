<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'plan'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders       = $query->paginate(30)->withQueryString();
        $pendingCount = Order::where('status', 'pending')->count();

        return view('admin.pedidos.index', compact('orders', 'pendingCount'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'plan']);
        return view('admin.pedidos.show', compact('order'));
    }

    public function complete(Request $request, Order $order)
    {
        abort_if(! $order->isPending(), 422);

        if ($request->filled('admin_notes')) {
            $order->update(['admin_notes' => $request->admin_notes]);
        }

        $order->complete();

        return redirect()->route('admin.pedidos.show', $order)
            ->with('success', "Pedido #$order->id completado. Se otorgó acceso Premium a {$order->user->name}.");
    }

    public function cancel(Request $request, Order $order)
    {
        abort_if($order->isCompleted(), 422);

        if ($request->filled('admin_notes')) {
            $order->update(['admin_notes' => $request->admin_notes]);
        }

        $order->cancel();

        return redirect()->route('admin.pedidos.show', $order)
            ->with('success', "Pedido #$order->id cancelado.");
    }
}
