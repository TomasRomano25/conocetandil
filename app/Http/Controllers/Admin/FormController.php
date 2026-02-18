<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormField;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::withCount('messages')->get();

        return view('admin.formularios.index', compact('forms'));
    }

    public function campos(Form $formulario)
    {
        $formulario->load('fields');

        return view('admin.formularios.campos', compact('formulario'));
    }

    public function updateField(Request $request, Form $formulario, FormField $campo)
    {
        $request->validate([
            'label'       => 'required|string|max:100',
            'placeholder' => 'nullable|string|max:255',
            'required'    => 'boolean',
            'visible'     => 'boolean',
        ]);

        $campo->update([
            'label'       => $request->input('label'),
            'placeholder' => $request->input('placeholder'),
            'required'    => $request->boolean('required'),
            'visible'     => $request->boolean('visible'),
        ]);

        return back()->with('success', 'Campo actualizado correctamente.');
    }

    public function reorderFields(Request $request, Form $formulario)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer']);

        foreach ($request->input('order') as $position => $id) {
            FormField::where('id', $id)->where('form_id', $formulario->id)
                ->update(['sort_order' => $position]);
        }

        return response()->json(['ok' => true]);
    }

    public function updateForm(Request $request, Form $formulario)
    {
        $request->validate([
            'name'               => 'required|string|max:100',
            'description'        => 'nullable|string|max:255',
            'active'             => 'boolean',
            'send_notification'  => 'boolean',
            'notification_email' => 'nullable|email|max:255',
        ]);

        $formulario->update([
            'name'               => $request->input('name'),
            'description'        => $request->input('description'),
            'active'             => $request->boolean('active'),
            'send_notification'  => $request->boolean('send_notification'),
            'notification_email' => $request->input('notification_email') ?: null,
        ]);

        return back()->with('success', 'Formulario actualizado correctamente.');
    }
}
