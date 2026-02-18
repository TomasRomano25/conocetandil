<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelOrder;
use Illuminate\Http\Request;

class HotelOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = HotelOrder::with(['hotel', 'user', 'plan'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders       = $query->paginate(20)->withQueryString();
        $pendingCount = HotelOrder::where('status', 'pending')->count();

        return view('admin.hotel-pedidos.index', compact('orders', 'pendingCount'));
    }

    public function show(HotelOrder $hotelOrder)
    {
        $hotelOrder->load(['hotel', 'user', 'plan']);
        return view('admin.hotel-pedidos.show', compact('hotelOrder'));
    }

    public function complete(Request $request, HotelOrder $hotelOrder)
    {
        abort_if(! $hotelOrder->isPending(), 422);

        if ($request->filled('admin_notes')) {
            $hotelOrder->update(['admin_notes' => $request->admin_notes]);
        }

        $hotelOrder->complete();

        return redirect()->route('admin.hotel-pedidos.show', $hotelOrder)
            ->with('success', "Pedido #$hotelOrder->id completado. Hotel \"{$hotelOrder->hotel->name}\" activado.");
    }

    public function cancel(Request $request, HotelOrder $hotelOrder)
    {
        abort_if($hotelOrder->isCompleted(), 422);

        if ($request->filled('admin_notes')) {
            $hotelOrder->update(['admin_notes' => $request->admin_notes]);
        }

        $hotelOrder->cancel();

        return redirect()->route('admin.hotel-pedidos.show', $hotelOrder)
            ->with('success', "Pedido #$hotelOrder->id cancelado.");
    }
}
