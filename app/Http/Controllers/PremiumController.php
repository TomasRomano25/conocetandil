<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use Illuminate\Http\Request;

class PremiumController extends Controller
{
    /** Smart entry point: hub for premium users, upsell for everyone else */
    public function index()
    {
        if (auth()->check() && auth()->user()->isPremium()) {
            return redirect()->route('premium.hub');
        }

        return view('premium.upsell');
    }

    /** Premium hub / panel — only for premium users */
    public function hub()
    {
        return view('premium.hub');
    }

    /** Planner questionnaire — only premium users */
    public function planner()
    {
        return view('premium.planner');
    }

    /** Results — match itineraries against questionnaire answers */
    public function resultados(Request $request)
    {
        $request->validate([
            'days'   => 'required|integer|min:1|max:7',
            'type'   => 'required|in:nature,gastronomy,adventure,relax,mixed',
            'season' => 'required|in:summer,winter,all',
            'kids'   => 'nullable|boolean',
            'car'    => 'nullable|boolean',
        ]);

        $days   = (int)  $request->input('days');
        $type   = $request->input('type');
        $season = $request->input('season');
        $kids   = $request->boolean('kids');
        $car    = $request->boolean('car');

        $itineraries = Itinerary::with(['items.lugar.images'])
            ->matchFilters($days, $type, $season, $kids, $car)
            ->get();

        return view('premium.resultados', compact('itineraries', 'days', 'type', 'season', 'kids', 'car'));
    }

    /** Full itinerary detail — day-by-day timeline */
    public function show(Itinerary $itinerary)
    {
        abort_if(! $itinerary->active, 404);

        $itinerary->load(['items' => fn($q) => $q->with('lugar.images')]);

        $byDay = $itinerary->itemsByDay();

        return view('premium.itinerario', compact('itinerary', 'byDay'));
    }
}
