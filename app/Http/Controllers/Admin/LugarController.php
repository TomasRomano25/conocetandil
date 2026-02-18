<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lugar;
use App\Models\LugarImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LugarController extends Controller
{
    public function index()
    {
        $lugares = Lugar::ordered()->paginate(10);
        return view('admin.lugares.index', compact('lugares'));
    }

    public function create()
    {
        return view('admin.lugares.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'direction' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'featured' => 'boolean',
            'is_premium' => 'boolean',
            'order' => 'integer',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|max:2048',
            'category' => 'nullable|string|max:100',
            'rating' => 'nullable|numeric|min:0|max:5',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|string|max:255',
            'opening_hours' => 'nullable|string|max:255',
            'promotion_title' => 'nullable|string|max:150',
            'promotion_description' => 'nullable|string',
            'promotion_url' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|min:-90|max:90',
            'longitude' => 'nullable|numeric|min:-180|max:180',
        ]);

        $validated['featured']   = $request->boolean('featured');
        $validated['is_premium'] = $request->boolean('is_premium');
        $validated['order'] = $request->input('order', 0);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('lugares', 'public');
        }

        unset($validated['gallery']);
        $lugar = Lugar::create($validated);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $index => $file) {
                $path = $file->store('lugares', 'public');
                $lugar->images()->create([
                    'path' => $path,
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.lugares.index')->with('success', 'Lugar creado correctamente.');
    }

    public function show(Lugar $lugar)
    {
        return redirect()->route('admin.lugares.edit', $lugar);
    }

    public function edit(Lugar $lugar)
    {
        $lugar->load('images');
        return view('admin.lugares.edit', compact('lugar'));
    }

    public function update(Request $request, Lugar $lugar)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'direction' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'featured' => 'boolean',
            'is_premium' => 'boolean',
            'order' => 'integer',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer|exists:lugar_images,id',
            'category' => 'nullable|string|max:100',
            'rating' => 'nullable|numeric|min:0|max:5',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|string|max:255',
            'opening_hours' => 'nullable|string|max:255',
            'promotion_title' => 'nullable|string|max:150',
            'promotion_description' => 'nullable|string',
            'promotion_url' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|min:-90|max:90',
            'longitude' => 'nullable|numeric|min:-180|max:180',
        ]);

        $validated['featured']   = $request->boolean('featured');
        $validated['is_premium'] = $request->boolean('is_premium');

        if ($request->hasFile('image')) {
            if ($lugar->image) {
                Storage::disk('public')->delete($lugar->image);
            }
            $validated['image'] = $request->file('image')->store('lugares', 'public');
        }

        // Delete selected gallery images
        if ($request->filled('delete_images')) {
            $imagesToDelete = LugarImage::whereIn('id', $request->input('delete_images'))
                ->where('lugar_id', $lugar->id)
                ->get();

            foreach ($imagesToDelete as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
        }

        // Add new gallery images
        if ($request->hasFile('gallery')) {
            $maxOrder = $lugar->images()->max('order') ?? -1;
            foreach ($request->file('gallery') as $index => $file) {
                $path = $file->store('lugares', 'public');
                $lugar->images()->create([
                    'path' => $path,
                    'order' => $maxOrder + $index + 1,
                ]);
            }
        }

        unset($validated['gallery'], $validated['delete_images']);
        $lugar->update($validated);

        return redirect()->route('admin.lugares.index')->with('success', 'Lugar actualizado correctamente.');
    }

    public function destroy(Lugar $lugar)
    {
        if ($lugar->image) {
            Storage::disk('public')->delete($lugar->image);
        }

        foreach ($lugar->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $lugar->delete();

        return redirect()->route('admin.lugares.index')->with('success', 'Lugar eliminado correctamente.');
    }
}
