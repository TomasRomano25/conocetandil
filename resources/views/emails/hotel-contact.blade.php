<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta — {{ $hotel->name }}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1A1A1A; background: #f5f5f5; margin:0; padding:20px;">
<div style="max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden;">

    <div style="background: #2D6A4F; color: white; padding: 24px 32px;">
        <p style="margin:0; font-size:13px; opacity:.8;">Conoce Tandil — Directorio de Hoteles</p>
        <h1 style="margin: 8px 0 0; font-size: 22px;">Nueva consulta para {{ $hotel->name }}</h1>
    </div>

    <div style="padding: 32px;">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee; color: #666; width: 120px;">Nombre</td>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee; font-weight: 600;">{{ $senderName }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee; color: #666;">Email</td>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee; font-weight: 600;">{{ $senderEmail }}</td>
            </tr>
            @if($senderPhone)
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee; color: #666;">Teléfono</td>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee; font-weight: 600;">{{ $senderPhone }}</td>
            </tr>
            @endif
        </table>

        <div style="margin-top: 24px;">
            <p style="color: #666; font-size: 14px; margin-bottom: 8px;">Mensaje:</p>
            <div style="background: #f9f9f9; border: 1px solid #eee; border-radius: 6px; padding: 16px; font-size: 14px; line-height: 1.6;">
                {{ $contactMessage }}
            </div>
        </div>

        <div style="margin-top: 24px; padding: 16px; background: #f0faf4; border-radius: 6px; font-size: 13px; color: #2D6A4F;">
            Podés responder directamente a <strong>{{ $senderEmail }}</strong>
        </div>
    </div>

    <div style="padding: 16px 32px; background: #f5f5f5; text-align: center; font-size: 12px; color: #999;">
        Enviado desde Conoce Tandil — Directorio de Hoteles
    </div>
</div>
</body>
</html>
