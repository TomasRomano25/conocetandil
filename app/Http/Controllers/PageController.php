<?php

namespace App\Http\Controllers;

use App\Models\Form;
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

        return view('pages.lugares', compact('lugares', 'categories', 'q', 'category', 'lugaresBanner'));
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
        $form = Form::with('visibleFields')
            ->where('slug', 'contacto')
            ->where('active', true)
            ->first();

        return view('pages.contacto', compact('form'));
    }
}
