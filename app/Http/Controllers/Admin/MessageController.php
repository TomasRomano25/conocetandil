<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $forms   = Form::all();
        $formId  = $request->input('form_id');
        $isRead  = $request->input('is_read');

        $query = Message::with('form')->latest();

        if ($formId) {
            $query->where('form_id', $formId);
        }

        if ($isRead !== null && $isRead !== '') {
            $query->where('is_read', (bool) $isRead);
        }

        $messages    = $query->paginate(30)->withQueryString();
        $unreadCount = Message::where('is_read', false)->count();

        return view('admin.mensajes.index', compact('messages', 'forms', 'formId', 'isRead', 'unreadCount'));
    }

    public function show(Message $mensaje)
    {
        if (! $mensaje->is_read) {
            $mensaje->update(['is_read' => true]);
        }

        $mensaje->load('form.fields');

        return view('admin.mensajes.show', compact('mensaje'));
    }

    public function markRead(Message $mensaje)
    {
        $mensaje->update(['is_read' => true]);

        return back()->with('success', 'Mensaje marcado como leído.');
    }

    public function markUnread(Message $mensaje)
    {
        $mensaje->update(['is_read' => false]);

        return back()->with('success', 'Mensaje marcado como no leído.');
    }

    public function destroy(Message $mensaje)
    {
        $mensaje->delete();

        return redirect()->route('admin.mensajes.index')
            ->with('success', 'Mensaje eliminado.');
    }
}
