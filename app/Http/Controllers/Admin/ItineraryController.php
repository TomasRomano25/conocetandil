<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\ItineraryItem;
use App\Models\Lugar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ItineraryController extends Controller
{
    public function index()
    {
        $itineraries = Itinerary::withCount('items')->ordered()->get();
        return view('admin.itinerarios.index', compact('itineraries'));
    }

    public function create()
    {
        return view('admin.itinerarios.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['title']) . '-' . uniqid();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('itineraries', 'public');
        }

        $itinerary = Itinerary::create($data);

        return redirect()->route('admin.itinerarios.items', $itinerary)
            ->with('success', 'Itinerario creado. Ahora agregá las actividades.');
    }

    public function edit(Itinerary $itinerario)
    {
        return view('admin.itinerarios.edit', compact('itinerario'));
    }

    public function update(Request $request, Itinerary $itinerario)
    {
        $data = $this->validated($request, $itinerario);

        if ($request->hasFile('cover_image')) {
            if ($itinerario->cover_image) {
                Storage::disk('public')->delete($itinerario->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('itineraries', 'public');
        }

        $itinerario->update($data);

        return redirect()->route('admin.itinerarios.index')
            ->with('success', 'Itinerario actualizado correctamente.');
    }

    public function destroy(Itinerary $itinerario)
    {
        if ($itinerario->cover_image) {
            Storage::disk('public')->delete($itinerario->cover_image);
        }
        $itinerario->delete();

        return redirect()->route('admin.itinerarios.index')
            ->with('success', 'Itinerario eliminado.');
    }

    /** Items management page */
    public function items(Itinerary $itinerario)
    {
        $itinerario->load('items.lugar');
        $lugares = Lugar::ordered()->get(['id', 'title', 'category']);

        return view('admin.itinerarios.items', compact('itinerario', 'lugares'));
    }

    /** Add a new item to the itinerary */
    public function storeItem(Request $request, Itinerary $itinerario)
    {
        $request->validate([
            'lugar_id'         => 'nullable|exists:lugares,id',
            'day'              => 'required|integer|min:1',
            'time_block'       => 'required|in:morning,lunch,afternoon,evening',
            'custom_title'     => 'nullable|string|max:150',
            'duration_minutes' => 'nullable|integer|min:15',
            'estimated_cost'   => 'nullable|string|max:100',
            'why_order'        => 'nullable|string|max:500',
            'contextual_notes' => 'nullable|string|max:500',
            'skip_if'          => 'nullable|string|max:300',
            'why_worth_it'          => 'nullable|string|max:300',
            'travel_minutes_to_next' => 'nullable|integer|min:1|max:999',
        ]);

        $sortOrder = $itinerario->items()
            ->where('day', $request->day)
            ->where('time_block', $request->time_block)
            ->max('sort_order') + 1;

        $itinerario->items()->create(array_merge(
            $request->only([
                'lugar_id', 'day', 'time_block', 'custom_title',
                'duration_minutes', 'estimated_cost',
                'why_order', 'contextual_notes', 'skip_if', 'why_worth_it',
                'travel_minutes_to_next',
            ]),
            ['sort_order' => $sortOrder]
        ));

        return redirect()->route('admin.itinerarios.items', $itinerario)
            ->with('success', 'Actividad agregada.');
    }

    /** Update an existing item */
    public function updateItem(Request $request, Itinerary $itinerario, ItineraryItem $item)
    {
        $request->validate([
            'day'              => 'required|integer|min:1',
            'time_block'       => 'required|in:morning,lunch,afternoon,evening',
            'custom_title'     => 'nullable|string|max:150',
            'duration_minutes' => 'nullable|integer|min:15',
            'estimated_cost'   => 'nullable|string|max:100',
            'why_order'        => 'nullable|string|max:500',
            'contextual_notes' => 'nullable|string|max:500',
            'skip_if'          => 'nullable|string|max:300',
            'why_worth_it'          => 'nullable|string|max:300',
            'travel_minutes_to_next' => 'nullable|integer|min:1|max:999',
        ]);

        $item->update($request->only([
            'day', 'time_block', 'custom_title', 'duration_minutes',
            'estimated_cost', 'why_order', 'contextual_notes', 'skip_if', 'why_worth_it',
            'travel_minutes_to_next',
        ]));

        return redirect()->route('admin.itinerarios.items', $itinerario)
            ->with('success', 'Actividad actualizada.');
    }

    /** Delete an item */
    public function destroyItem(Itinerary $itinerario, ItineraryItem $item)
    {
        $item->delete();

        return redirect()->route('admin.itinerarios.items', $itinerario)
            ->with('success', 'Actividad eliminada.');
    }

    private function validated(Request $request, ?Itinerary $itinerario = null): array
    {
        $data = $request->validate([
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'intro_tip'   => 'nullable|string|max:500',
            'days_min'    => 'required|integer|min:1|max:7',
            'days_max'    => 'required|integer|min:1|max:7|gte:days_min',
            'type'        => 'required|in:nature,gastronomy,adventure,relax,mixed',
            'season'      => 'required|in:summer,winter,all',
            'requires_car'=> 'boolean',
            'kid_friendly'=> 'boolean',
            'active'      => 'boolean',
            'sort_order'  => 'integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $data['requires_car'] = $request->boolean('requires_car');
        $data['kid_friendly'] = $request->boolean('kid_friendly');
        $data['active']       = $request->boolean('active');
        unset($data['cover_image']);

        return $data;
    }
}
