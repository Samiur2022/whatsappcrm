<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disconnessione - SNS CRM</title>
</head>
<body style="margin:0; padding:0; background-color:#f1f5f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9; padding: 20px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <tr>
                        <td style="background-color:#0f172a; padding: 32px 40px; text-align: center;">
                            <div style="display: inline-flex; align-items: center; justify-content: center; width: 44px; height: 44px; background-color: #ef4444; border-radius: 16px; font-size: 20px; font-weight: bold; color: #ffffff; margin-bottom: 12px;">👋</div>
                            <h1 style="margin:0; color:#ffffff; font-size: 22px;">SNS CRM</h1>
                            <p style="margin:4px 0 0; color:#94a3b8; font-size: 14px;">Disconnessione registrata</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h2 style="margin:0 0 16px; color:#0f172a;">L'utente ha effettuato il logout</h2>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f8fafc; border:1px solid #e2e8f0; border-radius:16px;">
                                <tr>
                                    <td style="padding:20px 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:13px; color:#334155;">
                                            <tr>
                                                <td style="padding:6px 0; color:#64748b; width:140px;">Utente</td>
                                                <td style="padding:6px 0; font-weight:500;">{{ $user->name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0; color:#64748b;">Email</td>
                                                <td style="padding:6px 0;">{{ $user->email }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0; color:#64748b;">Login effettuato il</td>
                                                <td style="padding:6px 0;">{{ $activity->logged_in_at?->format('d/m/Y H:i:s') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0; color:#64748b;">Logout effettuato il</td>
                                                <td style="padding:6px 0;">{{ $activity->logged_out_at?->format('d/m/Y H:i:s') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0; color:#64748b;">Durata sessione</td>
                                                <td style="padding:6px 0;">{{ $activity->duration_minutes }} minuti</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0; color:#64748b;">IP</td>
                                                <td style="padding:6px 0;">{{ $activity->ip_address }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0; color:#64748b;">Dispositivo</td>
                                                <td style="padding:6px 0;">{{ $activity->device_type }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin-top:24px; color:#94a3b8; font-size:12px;">© {{ date('Y') }} SNS CRM</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>