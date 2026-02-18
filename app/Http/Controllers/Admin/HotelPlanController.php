<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelPlan;
use Illuminate\Http\Request;

class HotelPlanController extends Controller
{
    public function index()
    {
        $plans = HotelPlan::ordered()->get();
        return view('admin.hotel-planes.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:100',
            'slug'             => 'required|string|max:100|unique:hotel_plans',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'tier'             => 'required|integer|in:1,2,3',
            'max_images'       => 'required|integer|min:1',
            'duration_months'  => 'required|integer|min:1',
            'sort_order'       => 'nullable|integer',
        ]);

        $data['has_services']         = $request->boolean('has_services');
        $data['has_rooms']            = $request->boolean('has_rooms');
        $data['has_gallery_captions'] = $request->boolean('has_gallery_captions');
        $data['is_featured']          = $request->boolean('is_featured');

        HotelPlan::create($data);

        return redirect()->route('admin.hotel-planes.index')
            ->with('success', 'Plan de hotel creado correctamente.');
    }

    public function update(Request $request, HotelPlan $hotelPlan)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:100',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'tier'             => 'required|integer|in:1,2,3',
            'max_images'       => 'required|integer|min:1',
            'duration_months'  => 'required|integer|min:1',
            'sort_order'       => 'nullable|integer',
        ]);

        $data['has_services']         = $request->boolean('has_services');
        $data['has_rooms']            = $request->boolean('has_rooms');
        $data['has_gallery_captions'] = $request->boolean('has_gallery_captions');
        $data['is_featured']          = $request->boolean('is_featured');
        $data['is_active']            = $request->boolean('is_active');

        $hotelPlan->update($data);

        return redirect()->route('admin.hotel-planes.index')
            ->with('success', "Plan \"{$hotelPlan->name}\" actualizado.");
    }

    public function destroy(HotelPlan $hotelPlan)
    {
        if ($hotelPlan->hotels()->exists()) {
            return redirect()->route('admin.hotel-planes.index')
                ->with('error', 'No se puede eliminar un plan con hoteles asociados.');
        }

        $hotelPlan->delete();

        return redirect()->route('admin.hotel-planes.index')
            ->with('success', 'Plan eliminado.');
    }

    public function updateSale(Request $request, HotelPlan $hotelPlan)
    {
        $request->validate([
            'sale_price' => 'nullable|numeric|min:0',
            'sale_label' => 'nullable|string|max:50',
        ]);

        $hotelPlan->update([
            'sale_price' => $request->filled('sale_price') ? $request->input('sale_price') : null,
            'sale_label' => $request->input('sale_label') ?: null,
        ]);

        return redirect()->route('admin.hotel-planes.index')
            ->with('success', "Precio especial del plan \"{$hotelPlan->name}\" actualizado.");
    }
}
