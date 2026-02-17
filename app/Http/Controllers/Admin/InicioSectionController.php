<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InicioSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function updateHeroImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $hero = InicioSection::where('key', 'hero')->firstOrFail();

        if ($hero->image) {
            Storage::disk('public')->delete($hero->image);
        }

        $path = $request->file('image')->store('hero', 'public');
        $hero->update(['image' => $path]);

        return redirect()->route('admin.inicio.index')
            ->with('success', 'Imagen del hero actualizada correctamente.');
    }

    public function deleteHeroImage()
    {
        $hero = InicioSection::where('key', 'hero')->firstOrFail();

        if ($hero->image) {
            Storage::disk('public')->delete($hero->image);
            $hero->update(['image' => null]);
        }

        return redirect()->route('admin.inicio.index')
            ->with('success', 'Imagen del hero eliminada.');
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
