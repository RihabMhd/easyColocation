<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyColoc Invitation</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        <tr>
            <td style="padding: 40px 0; text-align: center; background-color: #4f46e5;">
                <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: bold; letter-spacing: -0.025em;">EasyColoc</h1>
            </td>
        </tr>

        <tr>
            <td style="padding: 40px 30px;">
                <h2 style="margin: 0 0 20px; color: #111827; font-size: 24px; font-weight: 700;">Hello!</h2>
                <p style="margin: 0 0 24px; color: #4b5563; font-size: 16px; line-height: 24px;">
                    You have been invited to join a colocation on <strong>EasyColoc</strong>. Start managing your shared expenses and tracking balances with your roommates effortlessly. [cite: 1, 2]
                </p>
                
                <table border="0" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                    <tr>
                        <td align="center" style="border-radius: 6px;" bgcolor="#4f46e5">
                            <a href="{{ route('user.invitations.accept', $invitation->token) }}" 
                               target="_blank" 
                               style="display: inline-block; padding: 14px 32px; font-size: 16px; font-weight: 600; color: #ffffff; text-decoration: none; border-radius: 6px;">
                                Accept Invitation
                            </a>
                        </td>
                    </tr>
                </table>

                <p style="margin: 30px 0 0; color: #9ca3af; font-size: 14px; line-height: 20px; text-align: center;">
                    If the button above doesn't work, copy and paste this link into your browser:<br>
                    <span style="color: #4f46e5; word-break: break-all;">{{ route('user.invitations.accept', $invitation->token) }}</span>
                </p>
            </td>
        </tr>

        <tr>
            <td style="padding: 20px 30px; background-color: #f9fafb; text-align: center;">
                <p style="margin: 0; color: #6b7280; font-size: 12px;">
                    &copy; {{ date('Y') }} EasyColoc. All rights reserved.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>