<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;

class MembershipPlanController extends Controller
{
    public function index()
    {
        $plans = MembershipPlan::ordered()->get();
        return view('admin.planes.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'slug'            => 'required|string|max:100|unique:membership_plans',
            'description'     => 'nullable|string',
            'price'           => 'required|numeric|min:0',
            'duration_months' => 'required|integer|min:1',
            'sort_order'      => 'nullable|integer',
        ]);

        MembershipPlan::create($data);

        return redirect()->route('admin.planes.index')
            ->with('success', 'Plan creado correctamente.');
    }

    public function update(Request $request, MembershipPlan $plan)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'description'     => 'nullable|string',
            'price'           => 'required|numeric|min:0',
            'duration_months' => 'required|integer|min:1',
            'sort_order'      => 'nullable|integer',
        ]);

        $data['active'] = $request->boolean('active');

        $plan->update($data);

        return redirect()->route('admin.planes.index')
            ->with('success', "Plan \"{$plan->name}\" actualizado.");
    }

    public function destroy(MembershipPlan $plan)
    {
        $plan->delete();
        return redirect()->route('admin.planes.index')
            ->with('success', 'Plan eliminado.');
    }
}
