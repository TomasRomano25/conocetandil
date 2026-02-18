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
        $query = Hotel::active()->with(['plan', 'images'])->ordered();

        if ($request->filled('tier')) {
            $query->whereHas('plan', fn($q) => $q->where('tier', $request->tier));
        }

        $hotels = $query->get();

        return view('hoteles.index', compact('hotels'));
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
