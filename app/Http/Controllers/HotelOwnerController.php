<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\Hotel;
use App\Models\HotelOrder;
use App\Models\HotelPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HotelOwnerController extends Controller
{
    /** Public landing page — hotel owner info + plan comparison */
    public function propietarios()
    {
        $plans = HotelPlan::active()->ordered()->get();
        return view('hoteles.propietarios', compact('plans'));
    }

    /** Plan selection (auth required) */
    public function planes()
    {
        // If user already has a hotel, redirect to panel
        if (auth()->user()->hotel()->exists()) {
            return redirect()->route('hoteles.owner.panel');
        }

        $plans = HotelPlan::active()->ordered()->get();
        return view('hoteles.planes', compact('plans'));
    }

    /** Registration form for chosen plan */
    public function create(HotelPlan $plan)
    {
        abort_if(! $plan->is_active, 404);

        if (auth()->user()->hotel()->exists()) {
            return redirect()->route('hoteles.owner.panel');
        }

        return view('hoteles.registrar', compact('plan'));
    }

    /** Store new hotel + order */
    public function store(Request $request, HotelPlan $plan)
    {
        abort_if(! $plan->is_active, 404);

        if (auth()->user()->hotel()->exists()) {
            return redirect()->route('hoteles.owner.panel');
        }

        $rules = [
            'name'               => 'required|string|max:150',
            'hotel_type'         => 'nullable|string|max:100',
            'short_description'  => 'nullable|string|max:255',
            'description'        => 'required|string',
            'address'            => 'required|string|max:255',
            'phone'              => 'nullable|string|max:30',
            'email'              => 'required|email|max:150',
            'cover_image'        => 'nullable|image|max:3072',
            'transfer_reference' => 'nullable|string|max:255',
        ];

        if ($plan->tier >= 2) {
            $rules['website']       = 'nullable|url|max:255';
            $rules['stars']         = 'nullable|integer|min:1|max:5';
            $rules['checkin_time']  = 'nullable|string|max:20';
            $rules['checkout_time'] = 'nullable|string|max:20';
            $rules['services']      = 'nullable|string';
            $rules['gallery.*']     = 'image|max:3072';
        }

        if ($plan->tier >= 3) {
            $rules['rooms']              = 'nullable|array';
            $rules['rooms.*.name']       = 'required|string|max:100';
            $rules['rooms.*.capacity']   = 'nullable|integer|min:1';
            $rules['rooms.*.price']      = 'nullable|numeric|min:0';
            $rules['rooms.*.description']= 'nullable|string|max:500';
            $rules['gallery_captions']   = 'nullable|array';
        }

        $data = $request->validate($rules);

        // Handle cover image
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('hotels', 'public');
        }

        // Services: comma-separated string → array
        $services = null;
        if ($plan->tier >= 2 && $request->filled('services')) {
            $services = array_filter(array_map('trim', explode(',', $request->services)));
        }

        $slug = Str::slug($data['name']);
        $original = $slug;
        $i = 1;
        while (Hotel::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $i++;
        }

        $hotel = Hotel::create([
            'user_id'           => auth()->id(),
            'plan_id'           => $plan->id,
            'name'              => $data['name'],
            'slug'              => $slug,
            'hotel_type'        => $data['hotel_type'] ?? null,
            'short_description' => $data['short_description'] ?? null,
            'description'       => $data['description'],
            'address'           => $data['address'],
            'phone'             => $data['phone'] ?? null,
            'email'             => $data['email'],
            'website'           => $data['website'] ?? null,
            'stars'             => $data['stars'] ?? null,
            'checkin_time'      => $data['checkin_time'] ?? null,
            'checkout_time'     => $data['checkout_time'] ?? null,
            'services'          => $services,
            'cover_image'       => $coverPath,
            'status'            => 'pending',
        ]);

        // Gallery images
        if ($plan->tier >= 2 && $request->hasFile('gallery')) {
            $captions = $request->input('gallery_captions', []);
            foreach (array_slice($request->file('gallery'), 0, $plan->max_images) as $i => $file) {
                $path = $file->store('hotels/gallery', 'public');
                $hotel->images()->create([
                    'path'    => $path,
                    'caption' => $captions[$i] ?? null,
                    'order'   => $i,
                ]);
            }
        }

        // Rooms (tier 3)
        if ($plan->tier >= 3 && $request->filled('rooms')) {
            foreach ($request->input('rooms', []) as $i => $room) {
                $roomImage = null;
                if ($request->hasFile("room_images.$i")) {
                    $roomImage = $request->file("room_images.$i")->store('hotels/rooms', 'public');
                }
                $hotel->rooms()->create([
                    'name'           => $room['name'],
                    'description'    => $room['description'] ?? null,
                    'capacity'       => $room['capacity'] ?? null,
                    'price_per_night'=> $room['price'] ?? null,
                    'image'          => $roomImage,
                    'order'          => $i,
                ]);
            }
        }

        // Create order
        $order = HotelOrder::create([
            'hotel_id'           => $hotel->id,
            'user_id'            => auth()->id(),
            'plan_id'            => $plan->id,
            'amount'             => $plan->price,
            'status'             => 'pending',
            'transfer_reference' => $request->transfer_reference ?? null,
        ]);

        return redirect()->route('hoteles.owner.confirmacion', $order);
    }

    /** Owner hotel management panel */
    public function panel()
    {
        $hotel = auth()->user()->hotel()->with(['plan', 'images', 'order'])->first();

        if (! $hotel) {
            return redirect()->route('hoteles.propietarios');
        }

        return view('hoteles.panel', compact('hotel'));
    }

    /** Edit hotel form */
    public function edit()
    {
        $hotel = auth()->user()->hotel()->with(['plan', 'images', 'rooms'])->first();
        abort_if(! $hotel, 404);

        $plan = $hotel->plan;
        return view('hoteles.registrar', compact('hotel', 'plan'));
    }

    /** Update hotel */
    public function update(Request $request)
    {
        $hotel = auth()->user()->hotel()->with('plan')->first();
        abort_if(! $hotel, 404);

        $plan = $hotel->plan;

        $rules = [
            'name'              => 'required|string|max:150',
            'hotel_type'        => 'nullable|string|max:100',
            'short_description' => 'nullable|string|max:255',
            'description'       => 'required|string',
            'address'           => 'required|string|max:255',
            'phone'             => 'nullable|string|max:30',
            'email'             => 'required|email|max:150',
            'cover_image'       => 'nullable|image|max:3072',
        ];

        if ($plan->tier >= 2) {
            $rules['website']       = 'nullable|url|max:255';
            $rules['stars']         = 'nullable|integer|min:1|max:5';
            $rules['checkin_time']  = 'nullable|string|max:20';
            $rules['checkout_time'] = 'nullable|string|max:20';
            $rules['services']      = 'nullable|string';
            $rules['gallery.*']     = 'image|max:3072';
        }

        $data = $request->validate($rules);

        // Cover image
        if ($request->hasFile('cover_image')) {
            if ($hotel->cover_image) Storage::disk('public')->delete($hotel->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('hotels', 'public');
        } else {
            unset($data['cover_image']);
        }

        // Services
        if ($plan->tier >= 2) {
            $data['services'] = $request->filled('services')
                ? array_filter(array_map('trim', explode(',', $request->services)))
                : null;
        }

        // Delete marked gallery images
        if ($request->filled('delete_images')) {
            foreach ($hotel->images()->whereIn('id', $request->delete_images)->get() as $img) {
                Storage::disk('public')->delete($img->path);
                $img->delete();
            }
        }

        // New gallery images
        if ($plan->tier >= 2 && $request->hasFile('gallery')) {
            $existing = $hotel->images()->count();
            $slots    = $plan->max_images - $existing;
            $captions = $request->input('gallery_captions', []);
            foreach (array_slice($request->file('gallery'), 0, max(0, $slots)) as $i => $file) {
                $path = $file->store('hotels/gallery', 'public');
                $hotel->images()->create([
                    'path'    => $path,
                    'caption' => $captions[$i] ?? null,
                    'order'   => $existing + $i,
                ]);
            }
        }

        $hotel->update($data);

        // Re-pend after edit (admin must re-approve)
        if ($hotel->isActive()) {
            $hotel->update(['status' => 'pending']);
        }

        return redirect()->route('hoteles.owner.panel')
            ->with('success', 'Tu hotel fue actualizado. Revisaremos los cambios pronto.');
    }

    /** Confirmation page after registration */
    public function confirmacion(HotelOrder $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        return view('hoteles.confirmacion', [
            'order'      => $order->load(['hotel', 'plan']),
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
