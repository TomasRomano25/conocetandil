<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; }
        .header { background: #2D6A4F; color: #fff; padding: 24px 32px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 4px 0 0; font-size: 13px; opacity: 0.8; }
        .body { padding: 32px; }
        .field { margin-bottom: 18px; }
        .label { font-size: 12px; font-weight: bold; color: #555; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
        .value { font-size: 15px; color: #1A1A1A; background: #f9f9f9; padding: 10px 14px; border-radius: 6px; border-left: 3px solid #52B788; white-space: pre-wrap; word-break: break-word; }
        .footer { background: #f9f9f9; padding: 16px 32px; font-size: 12px; color: #999; border-top: 1px solid #eee; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Nuevo mensaje recibido</h1>
        <p>Formulario: {{ $form->name }}</p>
    </div>
    <div class="body">
        @foreach ($form->fields as $field)
            @if ($field->visible && isset($message->data[$field->name]))
            <div class="field">
                <div class="label">{{ $field->label }}</div>
                <div class="value">{{ $message->data[$field->name] }}</div>
            </div>
            @endif
        @endforeach

        <p style="font-size: 12px; color: #aaa; margin-top: 24px;">
            Recibido el {{ $message->created_at->format('d/m/Y H:i') }}
            @if ($message->ip_address) — IP: {{ $message->ip_address }} @endif
        </p>
    </div>
    <div class="footer">
        Este email fue enviado automáticamente por el sistema de Conoce Tandil.
        <a href="{{ url('/admin/mensajes/' . $message->id) }}" style="color: #2D6A4F;">Ver en el panel admin</a>
    </div>
</div>
</body>
</html>
