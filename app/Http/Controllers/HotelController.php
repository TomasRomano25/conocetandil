<?php

namespace App\Http\Controllers;

use App\Mail\HotelContactMail;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        // All active hotels with plan loaded (for tier grouping)
        $all = Hotel::active()->with(['plan'])->get();

        // Distinct types for filter tabs (from ALL active hotels, not filtered)
        $hotelTypes = $all->whereNotNull('hotel_type')
            ->pluck('hotel_type')->unique()->sort()->values();

        // Apply type filter
        if ($request->filled('type')) {
            $all = $all->where('hotel_type', $request->type);
        }

        // Apply amenity filter — ALL selected must be present
        $selectedAmenities = array_filter((array) $request->input('amenities', []));
        if (! empty($selectedAmenities)) {
            $all = $all->filter(function ($hotel) use ($selectedAmenities) {
                $services = $hotel->services ?? [];
                return count(array_intersect($selectedAmenities, $services)) === count($selectedAmenities);
            });
        }

        // Group by tier
        $featured = $all->filter(fn($h) => $h->plan && $h->plan->tier === 3)->values();
        $standard = $all->filter(fn($h) => $h->plan && $h->plan->tier === 2)->values();
        $basic    = $all->filter(fn($h) => $h->plan && $h->plan->tier === 1)->values();

        // Daily shuffle — same order all day, rotates at midnight
        $seed = crc32(date('Y-m-d'));
        foreach (['featured', 'standard', 'basic'] as $group) {
            $arr = $$group->all();
            srand($seed);
            shuffle($arr);
            $$group = collect($arr);
        }

        $amenityOptions = [
            'WiFi', 'Estacionamiento', 'Desayuno incluido',
            'Pileta', 'Spa', 'Pet Friendly', 'Parrilla', 'Aire acondicionado',
        ];

        return view('hoteles.index', compact(
            'featured', 'standard', 'basic',
            'hotelTypes', 'amenityOptions', 'selectedAmenities',
        ))->with('selectedType', $request->input('type', ''));
    }

    public function show(Hotel $hotel)
    {
        abort_if(! $hotel->isActive(), 404);

        $hotel->load(['plan', 'images', 'rooms']);

        return view('hoteles.show', compact('hotel'));
    }

    public function contact(Request $request, Hotel $hotel)
    {
        abort_if(! $hotel->isActive(), 404);

        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'phone'   => 'nullable|string|max:30',
            'message' => 'required|string|max:2000',
        ]);

        try {
            Mail::to($hotel->email)->send(new HotelContactMail(
                hotel: $hotel,
                senderName: $data['name'],
                senderEmail: $data['email'],
                senderPhone: $data['phone'] ?? '',
                contactMessage: $data['message'],
            ));
        } catch (\Throwable) {
            // Silent fail — same pattern as messaging module
        }

        return redirect()->back()->with('contact_success', true);
    }
}
