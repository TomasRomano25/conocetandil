<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\Itinerary;
use App\Models\InicioSection;
use App\Models\Order;
use Illuminate\Http\Request;

class PremiumController extends Controller
{
    /** Smart entry point: hub for premium users, upsell for everyone else */
    public function index()
    {
        if (auth()->check() && auth()->user()->isPremium()) {
            return redirect()->route('premium.hub');
        }

        $premiumBanner = InicioSection::where('key', 'premium_hero')->first();

        return view('premium.upsell', compact('premiumBanner'));
    }

    /** Premium hub / panel — only for premium users */
    public function hub()
    {
        $recentOrders = Order::where('user_id', auth()->id())
            ->with('plan')
            ->latest()
            ->take(5)
            ->get();

        return view('premium.hub', compact('recentOrders'));
    }

    /** Planner questionnaire — only premium users */
    public function planner()
    {
        $allDays    = range(1, 7);
        $allTypes   = ['nature' => ['🌿', 'Naturaleza'], 'gastronomy' => ['🧀', 'Gastronomía'], 'adventure' => ['🧗', 'Aventura'], 'relax' => ['🛁', 'Relax'], 'mixed' => ['✨', 'Mixto']];
        $allSeasons = ['summer' => ['☀️', 'Verano'], 'winter' => ['❄️', 'Invierno'], 'all' => ['🍃', 'No sé']];

        $enabledDays    = json_decode(Configuration::get('itinerary_days_enabled',    json_encode($allDays)), true)                ?? $allDays;
        $enabledTypes   = json_decode(Configuration::get('itinerary_types_enabled',   json_encode(array_keys($allTypes))), true)   ?? array_keys($allTypes);
        $enabledSeasons = json_decode(Configuration::get('itinerary_seasons_enabled', json_encode(array_keys($allSeasons))), true) ?? array_keys($allSeasons);

        // Fallback: if all disabled, show all
        if (empty($enabledDays))    $enabledDays    = $allDays;
        if (empty($enabledTypes))   $enabledTypes   = array_keys($allTypes);
        if (empty($enabledSeasons)) $enabledSeasons = array_keys($allSeasons);

        $days    = $enabledDays;
        $types   = array_filter($allTypes,   fn($k) => in_array($k, $enabledTypes),   ARRAY_FILTER_USE_KEY);
        $seasons = array_filter($allSeasons, fn($k) => in_array($k, $enabledSeasons), ARRAY_FILTER_USE_KEY);

        return view('premium.planner', compact('days', 'types', 'seasons'));
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
