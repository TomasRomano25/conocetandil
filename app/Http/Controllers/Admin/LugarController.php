<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lugar;
use App\Models\LugarImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LugarController extends Controller
{
    public function index(Request $request)
    {
        $query = Lugar::ordered();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('direction', 'like', "%{$search}%");
            });
        }

        $lugares = $query->paginate(10)->withQueryString();
        return view('admin.lugares.index', compact('lugares', 'search'));
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
            'image_focal_x' => 'nullable|numeric|min:0|max:100',
            'image_focal_y' => 'nullable|numeric|min:0|max:100',
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

        $validated['featured']      = $request->boolean('featured');
        $validated['is_premium']    = $request->boolean('is_premium');
        $validated['order']         = $request->input('order', 0);
        $validated['slug']          = Lugar::generateSlug($validated['title']);
        $validated['image_focal_x'] = $request->input('image_focal_x', 50);
        $validated['image_focal_y'] = $request->input('image_focal_y', 50);

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
            'image_focal_x' => 'nullable|numeric|min:0|max:100',
            'image_focal_y' => 'nullable|numeric|min:0|max:100',
            'featured' => 'boolean',
            'is_premium' => 'boolean',
            'order' => 'integer',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer|exists:lugar_images,id',
            'gallery_order' => 'nullable|array',
            'gallery_order.*' => 'integer|exists:lugar_images,id',
            'existing_focal_x' => 'nullable|array',
            'existing_focal_x.*' => 'numeric|min:0|max:100',
            'existing_focal_y' => 'nullable|array',
            'existing_focal_y.*' => 'numeric|min:0|max:100',
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

        $validated['featured']      = $request->boolean('featured');
        $validated['is_premium']    = $request->boolean('is_premium');
        $validated['slug']          = Lugar::generateSlug($validated['title'], $lugar->id);
        $validated['image_focal_x'] = $request->input('image_focal_x', $lugar->image_focal_x ?? 50);
        $validated['image_focal_y'] = $request->input('image_focal_y', $lugar->image_focal_y ?? 50);

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

        // Reorder existing gallery images + save focal points
        if ($request->filled('gallery_order')) {
            $focalX = $request->input('existing_focal_x', []);
            $focalY = $request->input('existing_focal_y', []);
            foreach ($request->input('gallery_order') as $position => $imageId) {
                $update = ['order' => $position];
                if (isset($focalX[$imageId])) $update['focal_x'] = $focalX[$imageId];
                if (isset($focalY[$imageId])) $update['focal_y'] = $focalY[$imageId];
                LugarImage::where('id', $imageId)->where('lugar_id', $lugar->id)->update($update);
            }
        } elseif ($request->filled('existing_focal_x')) {
            // Focal points only (no reorder submitted)
            $focalX = $request->input('existing_focal_x', []);
            $focalY = $request->input('existing_focal_y', []);
            foreach ($focalX as $imageId => $fx) {
                LugarImage::where('id', $imageId)->where('lugar_id', $lugar->id)
                    ->update(['focal_x' => $fx, 'focal_y' => $focalY[$imageId] ?? 50]);
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

        unset($validated['gallery'], $validated['delete_images'], $validated['gallery_order'], $validated['existing_focal_x'], $validated['existing_focal_y']);
        $lugar->update($validated);

        return redirect()->route('admin.lugares.index')->with('success', 'Lugar actualizado correctamente.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'json_file' => 'required|file|mimes:json,txt|max:2048',
        ]);

        $content = file_get_contents($request->file('json_file')->getRealPath());
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            return back()->with('error', 'El archivo JSON no es válido.');
        }

        $created = 0;
        $updated = 0;
        $errors  = [];

        foreach ($data as $index => $item) {
            if (empty($item['title'])) {
                $errors[] = "Fila " . ($index + 1) . ": falta el campo 'title'.";
                continue;
            }

            $fields = [
                'direction'             => $item['direction'] ?? '',
                'description'           => $item['description'] ?? '',
                'image'                 => $item['image'] ?? null,
                'featured'              => !empty($item['featured']),
                'is_premium'            => !empty($item['is_premium']),
                'order'                 => $item['order'] ?? 0,
                'category'              => $item['category'] ?? null,
                'rating'                => isset($item['rating']) ? (float) $item['rating'] : null,
                'phone'                 => $item['phone'] ?? null,
                'website'               => $item['website'] ?? null,
                'opening_hours'         => $item['opening_hours'] ?? null,
                'promotion_title'       => $item['promotion_title'] ?? null,
                'promotion_description' => $item['promotion_description'] ?? null,
                'promotion_url'         => $item['promotion_url'] ?? null,
                'latitude'              => isset($item['latitude']) ? (float) $item['latitude'] : null,
                'longitude'             => isset($item['longitude']) ? (float) $item['longitude'] : null,
            ];

            $existing = Lugar::where('title', $item['title'])->first();

            if ($existing) {
                $fields['slug'] = Lugar::generateSlug($item['title'], $existing->id);
                $existing->update($fields);
                $updated++;
            } else {
                $fields['slug'] = Lugar::generateSlug($item['title']);
                Lugar::create(array_merge(['title' => $item['title']], $fields));
                $created++;
            }
        }

        $message = "Importación completada: {$created} creados, {$updated} actualizados.";
        if (!empty($errors)) {
            $message .= ' Errores: ' . implode(' | ', $errors);
        }

        return redirect()->route('admin.lugares.index')->with('success', $message);
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
