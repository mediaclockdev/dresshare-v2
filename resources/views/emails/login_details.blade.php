<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to Dresshare</title>
</head>
<body style="font-family: Arial, sans-serif; background-color:#f7f7f7; padding:20px; margin:0;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:8px; padding:20px;">
                    <tr>
                        <td align="center" style="padding-bottom:10px;">
                            <h2 style="color:#333333; margin:0;">👗 Welcome to Dresshare!</h2>
                            <p style="color:#666666; margin:6px 0 0 0;">Your account was created successfully.</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:20px; color:#444444; font-size:15px;">
                            <p>Hi <strong>{{ $user->name }}</strong>,</p>

                            <p>Thanks for joining <strong>Dresshare</strong>. Here are your login details:</p>

                            <div style="background:#f1f1f1; padding:14px; border-radius:6px; margin:12px 0;">
                                <p style="margin:6px 0;"><strong>Email:</strong> {{ $user->email }}</p>
                                <p style="margin:6px 0;"><strong>Password:</strong> {{ $plainPassword }}</p>
                            </div>

                            <p>You can log in using the above details. For your security, please change your password after logging in.</p>

                            {{-- <p style="text-align:center; margin:20px 0;">
                                <a href="{{ url('/login') }}" style="display:inline-block; padding:12px 22px; text-decoration:none; border-radius:6px; background:#e91e63; color:#fff; font-weight:600;">
                                    Login to Dresshare
                                </a>
                            </p> --}}

                            <p style="color:#666666; font-size:13px;">If you did not register for Dresshare, please contact our support immediately.</p>

                            <p style="color:#999999; font-size:13px; text-align:center; margin-top:26px;">
                                © {{ date('Y') }} Dresshare. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
