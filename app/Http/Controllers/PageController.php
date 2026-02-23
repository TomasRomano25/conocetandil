<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\InicioSection;
use App\Models\Lugar;
use App\Models\LugarBanner;
use App\Models\PageView;

class PageController extends Controller
{
    public function inicio()
    {
        PageView::record('inicio');
        $featuredLugares = Lugar::with('images')->featured()->ordered()->limit(6)->get();
        $sections = InicioSection::visible()->ordered()->get()->keyBy('key');

        return view('pages.inicio', compact('featuredLugares', 'sections'));
    }

    public function lugares()
    {
        PageView::record('lugares');
        $q        = trim(request('q', ''));
        $category = trim(request('category', ''));

        $query = Lugar::with('images')->ordered();

        if ($q !== '') {
            $query->search($q);
        }

        if ($category !== '') {
            $query->where('category', $category);
        }

        $lugares    = $query->get();
        $categories = Lugar::whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $lugaresBanner = InicioSection::where('key', 'lugares_hero')->first();

        // Load active promotional banners keyed by position
        $lugarBanners = LugarBanner::active()->orderBy('position')->get()->keyBy('position');

        return view('pages.lugares', compact('lugares', 'categories', 'q', 'category', 'lugaresBanner', 'lugarBanners'));
    }

    public function lugar(Lugar $lugar)
    {
        PageView::record('lugar', $lugar->slug);
        $lugar->load('images');

        $relatedPlaces = Lugar::with('images')
            ->where('id', '!=', $lugar->id)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return view('pages.lugar', compact('lugar', 'relatedPlaces'));
    }

    public function guias()
    {
        PageView::record('guias');
        $guiasBanner = InicioSection::where('key', 'guias_hero')->first();
        return view('pages.guias', compact('guiasBanner'));
    }

    public function contacto()
    {
        PageView::record('contacto');
        $form = Form::with('visibleFields')
            ->where('slug', 'contacto')
            ->where('active', true)
            ->first();

        $contactoBanner = InicioSection::where('key', 'contacto_hero')->first();

        $contactInfo = [
            'address' => \App\Models\Configuration::get('contact_address', '9 de Julio 555, Tandil, Buenos Aires, Argentina'),
            'phone'   => \App\Models\Configuration::get('contact_phone', '(0249) 444-1234'),
            'email'   => \App\Models\Configuration::get('contact_email', 'info@conocetandil.com'),
            'hours'   => \App\Models\Configuration::get('contact_hours', ''),
        ];

        return view('pages.contacto', compact('form', 'contactoBanner', 'contactInfo'));
    }
}
