<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LugarBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LugarBannerController extends Controller
{
    public function index()
    {
        $banners = LugarBanner::orderBy('position')->get();
        return view('admin.lugar-banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.lugar-banners.form', ['banner' => new LugarBanner()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:120',
            'subtitle'    => 'nullable|string|max:255',
            'cta_text'    => 'nullable|string|max:60',
            'cta_url'     => 'nullable|url|max:255',
            'bg_color'    => 'nullable|string|max:20',
            'position'    => 'required|integer|min:1|max:999',
            'active'      => 'nullable|boolean',
            'image_desktop' => 'nullable|image|max:4096',
            'image_mobile'  => 'nullable|image|max:2048',
        ]);

        $data['active'] = $request->boolean('active');
        $data['bg_color'] = $data['bg_color'] ?: '#2D6A4F';

        if ($request->hasFile('image_desktop')) {
            $data['image_desktop'] = $request->file('image_desktop')->store('banners', 'public');
        }
        if ($request->hasFile('image_mobile')) {
            $data['image_mobile'] = $request->file('image_mobile')->store('banners', 'public');
        }

        LugarBanner::create($data);

        return redirect()->route('admin.lugar-banners.index')->with('success', 'Banner creado correctamente.');
    }

    public function edit(LugarBanner $lugarBanner)
    {
        return view('admin.lugar-banners.form', ['banner' => $lugarBanner]);
    }

    public function update(Request $request, LugarBanner $lugarBanner)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:120',
            'subtitle'    => 'nullable|string|max:255',
            'cta_text'    => 'nullable|string|max:60',
            'cta_url'     => 'nullable|url|max:255',
            'bg_color'    => 'nullable|string|max:20',
            'position'    => 'required|integer|min:1|max:999',
            'active'      => 'nullable|boolean',
            'image_desktop' => 'nullable|image|max:4096',
            'image_mobile'  => 'nullable|image|max:2048',
        ]);

        $data['active'] = $request->boolean('active');
        $data['bg_color'] = $data['bg_color'] ?: '#2D6A4F';

        if ($request->hasFile('image_desktop')) {
            if ($lugarBanner->image_desktop) {
                Storage::disk('public')->delete($lugarBanner->image_desktop);
            }
            $data['image_desktop'] = $request->file('image_desktop')->store('banners', 'public');
        }

        if ($request->hasFile('image_mobile')) {
            if ($lugarBanner->image_mobile) {
                Storage::disk('public')->delete($lugarBanner->image_mobile);
            }
            $data['image_mobile'] = $request->file('image_mobile')->store('banners', 'public');
        }

        // Allow deleting images
        if ($request->boolean('delete_image_desktop') && !$request->hasFile('image_desktop')) {
            if ($lugarBanner->image_desktop) {
                Storage::disk('public')->delete($lugarBanner->image_desktop);
            }
            $data['image_desktop'] = null;
        }

        if ($request->boolean('delete_image_mobile') && !$request->hasFile('image_mobile')) {
            if ($lugarBanner->image_mobile) {
                Storage::disk('public')->delete($lugarBanner->image_mobile);
            }
            $data['image_mobile'] = null;
        }

        $lugarBanner->update($data);

        return redirect()->route('admin.lugar-banners.index')->with('success', 'Banner actualizado correctamente.');
    }

    public function destroy(LugarBanner $lugarBanner)
    {
        if ($lugarBanner->image_desktop) {
            Storage::disk('public')->delete($lugarBanner->image_desktop);
        }
        if ($lugarBanner->image_mobile) {
            Storage::disk('public')->delete($lugarBanner->image_mobile);
        }
        $lugarBanner->delete();

        return redirect()->route('admin.lugar-banners.index')->with('success', 'Banner eliminado.');
    }
}
