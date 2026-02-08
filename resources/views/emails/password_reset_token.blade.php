<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kode Reset Password Z-Study</title>
</head>
<body style="margin:0;padding:0;background:#f5f7fb;font-family:Arial,sans-serif;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f5f7fb;padding:24px 0;">
    <tr>
      <td align="center">
        <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background:#ffffff;border-radius:18px;overflow:hidden;box-shadow:0 12px 30px rgba(15,23,42,0.12);">
          <tr>
            <td style="background:linear-gradient(135deg,#4f46e5,#7c3aed);padding:24px 28px;color:#fff;">
              <div style="font-size:20px;font-weight:700;">Z-Study</div>
              <div style="opacity:.9;font-size:14px;">Online Learning System</div>
            </td>
          </tr>
          <tr>
            <td style="padding:28px;">
              <div style="font-size:18px;font-weight:700;color:#0f172a;">Kode Reset Password</div>
              <p style="margin:10px 0 16px;color:#475569;font-size:14px;line-height:1.6;">
                Hai {{ $name }}, gunakan kode berikut untuk reset password akun Z-Study Anda.
              </p>
              <div style="display:inline-block;background:#eef2ff;color:#4338ca;padding:12px 18px;border-radius:12px;font-size:22px;font-weight:700;letter-spacing:4px;">
                {{ $token }}
              </div>
              <p style="margin:18px 0 0;color:#94a3b8;font-size:12px;">
                Abaikan email ini jika Anda tidak meminta reset password.
              </p>
            </td>
          </tr>
          <tr>
            <td style="padding:16px 28px;background:#f8fafc;color:#94a3b8;font-size:12px;">
              © {{ date('Y') }} Z-Study. All rights reserved.
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
