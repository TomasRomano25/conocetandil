<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavItem;
use Illuminate\Http\Request;

class NavItemController extends Controller
{
    public function index()
    {
        $navItems = NavItem::ordered()->get();

        return view('admin.nav.index', compact('navItems'));
    }

    public function update(Request $request, NavItem $navItem)
    {
        $validated = $request->validate([
            'label'      => 'required|string|max:100',
            'is_visible' => 'boolean',
        ]);

        $validated['is_visible'] = $request->boolean('is_visible');

        $navItem->update($validated);

        return redirect()->route('admin.nav.index')
            ->with('success', 'Elemento del menú actualizado correctamente.');
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:nav_items,id',
        ]);

        foreach ($validated['order'] as $position => $id) {
            NavItem::where('id', $id)->update(['order' => $position + 1]);
        }

        return response()->json(['success' => true]);
    }
}
