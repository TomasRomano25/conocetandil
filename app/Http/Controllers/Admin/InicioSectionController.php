<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InicioSection;
use Illuminate\Http\Request;

class InicioSectionController extends Controller
{
    public function index()
    {
        $sections = InicioSection::ordered()->get();
        return view('admin.inicio.index', compact('sections'));
    }

    public function update(Request $request, InicioSection $inicioSection)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'is_visible' => 'boolean',
        ]);

        $validated['is_visible'] = $request->boolean('is_visible');

        $inicioSection->update($validated);

        return redirect()->route('admin.inicio.index')->with('success', 'Sección actualizada correctamente.');
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:inicio_sections,id',
        ]);

        foreach ($validated['order'] as $position => $id) {
            InicioSection::where('id', $id)->update(['order' => $position + 1]);
        }

        return response()->json(['success' => true]);
    }
}
