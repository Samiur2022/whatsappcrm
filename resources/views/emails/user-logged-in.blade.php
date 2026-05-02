<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuovo accesso - SNS CRM</title>
</head>
<body style="margin:0; padding:0; background-color:#f1f5f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9; padding: 20px 0;">
        <tr>
            <td align="center">
                <!-- Main container -->
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color:#0f172a; padding: 32px 40px; text-align: center;">
                            <div style="display: inline-flex; align-items: center; justify-content: center; width: 44px; height: 44px; background-color: #6366f1; border-radius: 16px; font-size: 20px; font-weight: bold; color: #ffffff; margin-bottom: 12px;">S</div>
                            <h1 style="margin:0; color:#ffffff; font-size: 22px; font-weight: 600;">SNS CRM</h1>
                            <p style="margin:4px 0 0; color:#94a3b8; font-size: 14px;">Pannello professionale</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <h2 style="margin: 0 0 8px; color: #0f172a; font-size: 18px; font-weight: 600;">🔐 Nuovo accesso rilevato</h2>
                            <p style="margin: 0 0 24px; color: #475569; font-size: 14px; line-height: 1.6;">
                                È stato effettuato un nuovo accesso al tuo account SNS CRM. Di seguito i dettagli della sessione.
                            </p>

                            <!-- Info card -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden;">
                                <tr>
                                    <td style="padding: 20px 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size: 13px; color: #334155;">
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; width: 120px;">Utente</td>
                                                <td style="padding: 6px 0; font-weight: 500;">{{ $user->name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b;">Email</td>
                                                <td style="padding: 6px 0; font-weight: 500;">{{ $user->email ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b;">Dispositivo</td>
                                                <td style="padding: 6px 0; font-weight: 500;">{{ $activity->device_type ?? 'Sconosciuto' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b;">Browser</td>
                                                <td style="padding: 6px 0; font-weight: 500;">{{ $activity->browser ?? 'N/D' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b;">Sistema operativo</td>
                                                <td style="padding: 6px 0; font-weight: 500;">{{ $activity->os ?? 'N/D' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b;">Indirizzo IP</td>
                                                <td style="padding: 6px 0; font-weight: 500;">{{ $activity->ip_address ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b;">Località</td>
                                                <td style="padding: 6px 0; font-weight: 500;">{{ ($activity->city ?? '?') }}, {{ ($activity->country ?? '?') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b;">Orario login</td>
                                                <td style="padding: 6px 0; font-weight: 500;">{{ $activity->logged_in_at?->format('d/m/Y H:i:s') ?? 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 24px 0 0; color: #94a3b8; font-size: 12px;">
                                Se non riconosci questa attività, ti consigliamo di cambiare immediatamente la password e contattare l'amministratore.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 40px; text-align: center; font-size: 12px; color: #94a3b8;">
                            © {{ date('Y') }} SNS CRM. Tutti i diritti riservati.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>