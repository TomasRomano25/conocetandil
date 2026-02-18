<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\PromotionUse;
use App\Models\HotelPlan;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::withCount('uses')->latest()->get();
        $totalUses      = PromotionUse::count();
        $totalDiscount  = PromotionUse::sum('discount_amount');
        $activeCount    = Promotion::active()->count();
        $membershipPlans = MembershipPlan::active()->ordered()->get();
        $hotelPlans      = HotelPlan::active()->ordered()->get();

        return view('admin.promociones.index', compact(
            'promotions', 'totalUses', 'totalDiscount', 'activeCount',
            'membershipPlans', 'hotelPlans'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'               => 'required|string|max:50|unique:promotions,code',
            'name'               => 'required|string|max:255',
            'description'        => 'nullable|string|max:1000',
            'discount_type'      => 'required|in:fixed,percentage',
            'discount_value'     => 'required|numeric|min:0.01',
            'applies_to'         => 'required|in:all,membership,hotel',
            'plan_ids'           => 'nullable|array',
            'plan_ids.*'         => 'integer',
            'min_amount'         => 'nullable|numeric|min:0',
            'max_discount'       => 'nullable|numeric|min:0',
            'max_uses'           => 'nullable|integer|min:1',
            'max_uses_per_user'  => 'required|integer|min:1',
            'valid_from'         => 'nullable|date',
            'valid_until'        => 'nullable|date|after_or_equal:valid_from',
            'is_active'          => 'boolean',
        ]);

        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['plan_ids'] = (!empty($data['plan_ids'])) ? $data['plan_ids'] : null;

        Promotion::create($data);

        return back()->with('success', 'Promoción creada correctamente.');
    }

    public function update(Request $request, Promotion $promo)
    {
        $data = $request->validate([
            'code'               => 'required|string|max:50|unique:promotions,code,' . $promo->id,
            'name'               => 'required|string|max:255',
            'description'        => 'nullable|string|max:1000',
            'discount_type'      => 'required|in:fixed,percentage',
            'discount_value'     => 'required|numeric|min:0.01',
            'applies_to'         => 'required|in:all,membership,hotel',
            'plan_ids'           => 'nullable|array',
            'plan_ids.*'         => 'integer',
            'min_amount'         => 'nullable|numeric|min:0',
            'max_discount'       => 'nullable|numeric|min:0',
            'max_uses'           => 'nullable|integer|min:1',
            'max_uses_per_user'  => 'required|integer|min:1',
            'valid_from'         => 'nullable|date',
            'valid_until'        => 'nullable|date|after_or_equal:valid_from',
            'is_active'          => 'boolean',
        ]);

        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['plan_ids'] = (!empty($data['plan_ids'])) ? $data['plan_ids'] : null;

        $promo->update($data);

        return back()->with('success', 'Promoción actualizada.');
    }

    public function destroy(Promotion $promo)
    {
        if ($promo->uses_count > 0) {
            $promo->update(['is_active' => false]);
            return back()->with('success', 'La promoción tiene usos registrados — fue desactivada en lugar de eliminada.');
        }

        $promo->delete();
        return back()->with('success', 'Promoción eliminada.');
    }
}
