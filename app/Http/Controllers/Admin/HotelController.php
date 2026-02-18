<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $query = Hotel::with(['user', 'plan'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $hotels       = $query->paginate(20)->withQueryString();
        $pendingCount = Hotel::where('status', 'pending')->count();

        return view('admin.hoteles.index', compact('hotels', 'pendingCount'));
    }

    public function show(Hotel $hotel)
    {
        $hotel->load(['user', 'plan', 'images', 'rooms', 'order']);
        return view('admin.hoteles.show', compact('hotel'));
    }

    public function approve(Request $request, Hotel $hotel)
    {
        $hotel->update([
            'status'      => 'active',
            'approved_at' => now(),
            'expires_at'  => now()->addMonths($hotel->plan->duration_months),
        ]);

        if ($request->filled('admin_notes') && $hotel->order) {
            $hotel->order->update(['admin_notes' => $request->admin_notes]);
        }

        return redirect()->route('admin.hoteles.show', $hotel)
            ->with('success', "Hotel \"{$hotel->name}\" aprobado y publicado.");
    }

    public function reject(Request $request, Hotel $hotel)
    {
        $hotel->update(['status' => 'rejected']);

        if ($request->filled('admin_notes') && $hotel->order) {
            $hotel->order->update(['admin_notes' => $request->admin_notes]);
        }

        return redirect()->route('admin.hoteles.show', $hotel)
            ->with('success', "Hotel \"{$hotel->name}\" rechazado.");
    }

    public function destroy(Hotel $hotel)
    {
        if ($hotel->cover_image) {
            Storage::disk('public')->delete($hotel->cover_image);
        }
        foreach ($hotel->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        foreach ($hotel->rooms as $room) {
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }
        }

        $hotel->delete();

        return redirect()->route('admin.hoteles.index')
            ->with('success', "Hotel eliminado.");
    }
}
