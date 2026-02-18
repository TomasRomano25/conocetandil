<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelContact;
use App\Models\HotelView;
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

    public function analytics()
    {
        $totalViews    = HotelView::count();
        $monthViews    = HotelView::where('viewed_date', '>=', now()->startOfMonth()->toDateString())->count();
        $todayViews    = HotelView::where('viewed_date', today()->toDateString())->count();
        $totalContacts = HotelContact::count();

        // Per-hotel stats
        $hotels = Hotel::with(['plan'])
            ->withCount([
                'views as total_views',
                'views as month_views' => fn($q) => $q->where('viewed_date', '>=', now()->startOfMonth()->toDateString()),
                'contacts as total_contacts',
            ])
            ->orderByDesc('total_views')
            ->get();

        // Last 30 days chart data (all hotels combined)
        $chartData = HotelView::selectRaw('viewed_date, COUNT(*) as cnt')
            ->where('viewed_date', '>=', now()->subDays(29)->toDateString())
            ->groupBy('viewed_date')
            ->orderBy('viewed_date')
            ->pluck('cnt', 'viewed_date');

        // Fill in missing days with 0
        $days = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $days[$date] = $chartData[$date] ?? 0;
        }

        $recentContacts = HotelContact::with('hotel')->latest()->limit(10)->get();

        return view('admin.hoteles.analiticas', compact(
            'totalViews', 'monthViews', 'todayViews', 'totalContacts',
            'hotels', 'days', 'recentContacts',
        ));
    }

    public function show(Hotel $hotel)
    {
        $hotel->load(['user', 'plan', 'images', 'rooms', 'order']);

        $hotelStats = [
            'total_views'   => $hotel->views()->count(),
            'month_views'   => $hotel->views()->where('viewed_date', '>=', now()->startOfMonth()->toDateString())->count(),
            'today_views'   => $hotel->views()->where('viewed_date', today()->toDateString())->count(),
            'total_contacts'=> $hotel->contacts()->count(),
        ];
        $recentContacts = $hotel->contacts()->latest()->limit(5)->get();

        return view('admin.hoteles.show', compact('hotel', 'hotelStats', 'recentContacts'));
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
