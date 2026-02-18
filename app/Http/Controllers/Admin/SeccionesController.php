<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\InicioSection;
use Illuminate\Http\Request;

class SeccionesController extends Controller
{
    public function index()
    {
        $sections = InicioSection::ordered()->get()->keyBy('key');

        $contactInfo = [
            'address' => Configuration::get('contact_address', ''),
            'phone'   => Configuration::get('contact_phone', ''),
            'email'   => Configuration::get('contact_email', ''),
            'hours'   => Configuration::get('contact_hours', ''),
        ];

        return view('admin.secciones.index', compact('sections', 'contactInfo'));
    }

    public function updateContactInfo(Request $request)
    {
        $request->validate([
            'contact_address' => 'nullable|string|max:255',
            'contact_phone'   => 'nullable|string|max:50',
            'contact_email'   => 'nullable|email|max:255',
            'contact_hours'   => 'nullable|string|max:255',
        ]);

        Configuration::set('contact_address', $request->input('contact_address', ''));
        Configuration::set('contact_phone',   $request->input('contact_phone', ''));
        Configuration::set('contact_email',   $request->input('contact_email', ''));
        Configuration::set('contact_hours',   $request->input('contact_hours', ''));

        return redirect()->route('admin.secciones.index', ['tab' => 'contacto'])
            ->with('success', 'Información de contacto guardada correctamente.');
    }
}
