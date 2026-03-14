<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f6f8; font-family:Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8; padding:40px 0;">
        <tr>
            <td align="center">

                <!-- Container -->
                <table width="600" cellpadding="0" cellspacing="0"
                    style="
                background-color:#ffffff;
                border-radius:10px;
                overflow:hidden;
                box-shadow:0 6px 18px rgba(0,0,0,0.06);
            ">

                    <!-- Logo Header -->
                    <tr>
                        <td style="padding:30px 40px; text-align:center; background-color:#ffffff;">
                            <img src="{{ url('/logo.png') }}" alt="Atraxsys" style="max-width:180px; height:auto;">
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="height:1px; background-color:#eef1f4;"></td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:40px; color:#333333; font-size:14px; line-height:1.7;">
                            <p style="margin-top:0;">
                                Halo <strong>{{ $name ?? 'User' }}</strong>,
                            </p>

                            <p>
                                Kami menerima permintaan untuk mengatur ulang password akun
                                <strong>Atraxsys</strong> Anda.
                                Klik tombol di bawah ini untuk melanjutkan proses reset password.
                            </p>

                            <!-- Button -->
                            <table cellpadding="0" cellspacing="0" style="margin:32px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $resetUrl }}"
                                            style="
                                       background-color:#1e88e5;
                                       color:#ffffff;
                                       padding:14px 32px;
                                       text-decoration:none;
                                       border-radius:8px;
                                       font-weight:600;
                                       font-size:14px;
                                       display:inline-block;
                                       ">
                                            Reset Password
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p>
                                Link ini hanya berlaku selama <strong>60 menit</strong>.
                                Jika Anda tidak merasa melakukan permintaan reset password,
                                silakan abaikan email ini — akun Anda tetap aman.
                            </p>

                            <p style="margin-bottom:0;">
                                Salam,<br>
                                <strong>Tim Atraxsys</strong><br>
                                <span style="color:#777777; font-size:12px;">
                                    Assets, Always in Sight
                                </span>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="padding:24px 40px; background-color:#f8f9fa; font-size:12px; color:#777777; text-align:center;">
                            <p style="margin:0;">
                                © {{ date('Y') }} Atraxsys. All rights reserved.
                            </p>

                            <p style="margin:10px 0 4px;">
                                Jika tombol tidak berfungsi, salin dan buka link berikut:
                            </p>

                            <p style="word-break:break-all; margin:0;">
                                <a href="{{ $resetUrl }}" style="color:#1e88e5;">
                                    {{ $resetUrl }}
                                </a>
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
