<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nuevo usuario registrado — Conoce Tandil</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f0;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f0;padding:40px 20px;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">

          <!-- Header -->
          <tr>
            <td style="background:linear-gradient(135deg,#2D6A4F 0%,#52B788 100%);padding:32px 40px;text-align:center;">
              <p style="margin:0 0 8px 0;color:rgba(255,255,255,0.7);font-size:12px;font-weight:600;letter-spacing:2px;text-transform:uppercase;">CONOCE TANDIL</p>
              <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:700;line-height:1.3;">Nuevo usuario registrado</h1>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding:40px;">
              <p style="margin:0 0 24px 0;font-size:15px;color:#444;line-height:1.6;">Un nuevo usuario se registró en la plataforma.</p>

              <!-- Data rows -->
              <table width="100%" cellpadding="0" cellspacing="0" style="background:#f9f9f7;border-radius:8px;margin:0 0 20px 0;border:1px solid #e8e8e4;">
                <tr style="border-bottom:1px solid #e8e8e4;">
                  <td style="padding:12px 16px;font-size:13px;color:#666;">Nombre</td>
                  <td style="padding:12px 16px;font-size:13px;color:#1A1A1A;font-weight:600;text-align:right;">{{ $user->name }}</td>
                </tr>
                <tr style="border-bottom:1px solid #e8e8e4;">
                  <td style="padding:12px 16px;font-size:13px;color:#666;">Email</td>
                  <td style="padding:12px 16px;font-size:13px;color:#1A1A1A;font-weight:600;text-align:right;">{{ $user->email }}</td>
                </tr>
                <tr>
                  <td style="padding:12px 16px;font-size:13px;color:#666;">Fecha de registro</td>
                  <td style="padding:12px 16px;font-size:13px;color:#1A1A1A;font-weight:600;text-align:right;">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                </tr>
              </table>

              <!-- Action button -->
              <div style="text-align:center;margin:28px 0 0 0;">
                <a href="{{ url('/admin/usuarios') }}" style="display:inline-block;background:#2D6A4F;color:#ffffff;font-weight:700;font-size:15px;padding:14px 32px;border-radius:8px;text-decoration:none;">Ver en panel admin →</a>
              </div>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background:#f9f9f7;border-top:1px solid #e8e8e4;padding:20px 40px;text-align:center;">
              <p style="margin:0;color:#888;font-size:12px;">© {{ date('Y') }} Conoce Tandil · Todos los derechos reservados</p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
