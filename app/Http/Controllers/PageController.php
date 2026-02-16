<?php

namespace App\Http\Controllers;

use App\Models\InicioSection;
use App\Models\Lugar;

class PageController extends Controller
{
    public function inicio()
    {
        $featuredLugares = Lugar::with('images')->featured()->ordered()->limit(6)->get();
        $sections = InicioSection::visible()->ordered()->get()->keyBy('key');

        return view('pages.inicio', compact('featuredLugares', 'sections'));
    }

    public function lugares()
    {
        $lugares = Lugar::with('images')->ordered()->get();

        return view('pages.lugares', compact('lugares'));
    }

    public function lugar(Lugar $lugar)
    {
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
        return view('pages.guias');
    }

    public function contacto()
    {
        return view('pages.contacto');
    }
}
