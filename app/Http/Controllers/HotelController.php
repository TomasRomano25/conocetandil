<?php

namespace App\Http\Controllers;

use App\Mail\HotelContactMail;
use App\Models\Hotel;
use App\Models\HotelContact;
use App\Models\HotelView;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

        // Apply search filter
        $search = trim($request->input('search', ''));
        if ($search !== '') {
            $needle = mb_strtolower($search);
            $all = $all->filter(function ($hotel) use ($needle) {
                return str_contains(mb_strtolower($hotel->name), $needle)
                    || str_contains(mb_strtolower($hotel->address ?? ''), $needle);
            });
        }

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

        $totalResults = $featured->count() + $standard->count() + $basic->count();

        return view('hoteles.index', compact(
            'featured', 'standard', 'basic',
            'hotelTypes', 'amenityOptions', 'selectedAmenities',
            'totalResults',
        ))->with('selectedType', $request->input('type', ''))
          ->with('selectedSearch', $search);
    }

    public function show(Hotel $hotel)
    {
        abort_if(! $hotel->isActive(), 404);

        $hotel->load(['plan', 'images', 'rooms']);

        // Record unique daily session view (silent fail)
        try {
            HotelView::firstOrCreate([
                'hotel_id'    => $hotel->id,
                'session_id'  => session()->getId(),
                'viewed_date' => today()->toDateString(),
            ]);
        } catch (\Throwable) {}

        return view('hoteles.show', compact('hotel'));
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

    public function contact(Request $request, Hotel $hotel)
    {
        abort_if(! $hotel->isActive(), 404);

        if (!$this->verifyCaptcha($request)) {
            return back()->withErrors(['captcha' => 'Verificación de seguridad fallida. Intentá de nuevo.'])->withInput();
        }

        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'phone'   => 'nullable|string|max:30',
            'message' => 'required|string|max:2000',
        ]);

        // Save contact to DB first
        $contact = HotelContact::create([
            'hotel_id'     => $hotel->id,
            'sender_name'  => $data['name'],
            'sender_email' => $data['email'],
            'sender_phone' => $data['phone'] ?? null,
            'message'      => $data['message'],
            'email_sent'   => false,
        ]);

        // Attempt to send email
        try {
            Mail::to($hotel->email)->send(new HotelContactMail(
                hotel: $hotel,
                senderName: $data['name'],
                senderEmail: $data['email'],
                senderPhone: $data['phone'] ?? '',
                contactMessage: $data['message'],
            ));
            $contact->update(['email_sent' => true]);
        } catch (\Throwable) {
            // Silent fail — email saved to DB regardless
        }

        return redirect()->back()->with('contact_success', true);
    }
}
