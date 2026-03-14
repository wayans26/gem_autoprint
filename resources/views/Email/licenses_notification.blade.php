<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <title>License & Permit Notification</title>

    <style>
        /* Gmail web/mobile support; Outlook may ignore but layout still safe because table-based. */
        @media screen and (max-width: 600px) {
            .container {
                width: 100% !important;
            }

            .px {
                padding-left: 16px !important;
                padding-right: 16px !important;
            }

            .py {
                padding-top: 16px !important;
                padding-bottom: 16px !important;
            }

            /* Stack columns on mobile */
            .col {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
            }

            .col-pad-r {
                padding-right: 0 !important;
            }

            .col-pad-l {
                padding-left: 0 !important;
            }

            /* Button full width on mobile */
            .btn-wrap {
                width: 100% !important;
            }

            .btn-link {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>

<body style="margin:0;padding:0;background:#f6f8fb;font-family:Arial, Helvetica, sans-serif;color:#0f172a;">
    <!-- Preheader (hidden) -->
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">
        {{ $status === 'expired'
            ? 'Peringatan: izin/perizinan sudah kedaluwarsa.'
            : 'Pengingat: izin/perizinan akan segera kedaluwarsa.' }}
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
        style="background:#f6f8fb;padding:24px 12px;">
        <tr>
            <td align="center">

                <!-- Use width=100% + max-width (lebih aman daripada width fixed) -->
                <table role="presentation" class="container" width="100%" cellpadding="0" cellspacing="0"
                    style="max-width:680px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 24px rgba(15,23,42,.08);">

                    <!-- Header -->
                    <tr>
                        <td class="px py"
                            style="padding:22px;background:#0ea5e9;background:linear-gradient(135deg,#0ea5e9,#22c55e);">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td valign="middle" style="padding-right:12px;">
                                        <img src="{{ $logoCid ?? ($logoUrl ?? '') }}" alt="{{ $appName ?? 'Atraxsys' }}"
                                            style="display:block;height:38px;max-width:180px;width:auto;border:0;outline:none;text-decoration:none;">
                                    </td>
                                    <td align="right" valign="middle" style="font-size:12px;color:#eafff5;">
                                        <div style="opacity:.95;font-weight:700;">{{ $appName ?? 'Atraxsys' }}</div>
                                        <div style="opacity:.9;margin-top:2px;">Licenses &amp; Permits Alert</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    @php
                        $isExpired = $status === 'expired';
                        $badgeBg = $isExpired ? '#fee2e2' : '#dcfce7';
                        $badgeTx = $isExpired ? '#991b1b' : '#166534';
                        $title = $isExpired ? 'License/Permit Sudah Expired' : 'License/Permit Akan Expired';
                        $subtitle = $isExpired
                            ? 'Mohon segera tindak lanjut untuk menghindari risiko kepatuhan.'
                            : 'Mohon persiapkan perpanjangan sebelum jatuh tempo.';
                    @endphp

                    <!-- Title + Badge -->
                    <tr>
                        <td class="px" style="padding:18px 22px 0 22px;">
                            <div style="font-size:20px;line-height:1.3;font-weight:800;color:#0f172a;">
                                {{ $title }}
                            </div>

                            <div style="padding-top:10px;">
                                <span
                                    style="display:inline-block;padding:6px 10px;border-radius:999px;background:{{ $badgeBg }};color:{{ $badgeTx }};font-size:12px;font-weight:800;">
                                    {{ $isExpired ? 'EXPIRED' : 'EXPIRING' }}
                                    @if (!$isExpired && isset($daysLeft))
                                        • {{ (int) $daysLeft <= 0 ? 'Hari Ini' : $daysLeft . ' Hari Lagi' }}
                                    @endif
                                </span>
                            </div>

                            <div style="margin-top:10px;color:#334155;font-size:13px;line-height:1.7;">
                                {{ $subtitle }}
                            </div>
                        </td>
                    </tr>

                    <!-- Main card -->
                    <tr>
                        <td class="px" style="padding:16px 22px 0 22px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;">
                                <tr>
                                    <td style="padding:14px;background:#f8fafc;border-bottom:1px solid #e5e7eb;">
                                        <div style="font-size:12px;color:#64748b;">Permit No.</div>
                                        <div style="font-size:16px;font-weight:900;color:#0f172a;margin-top:2px;">
                                            {{ $license->permit_no ?? '-' }}
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:14px;">
                                        <div style="font-size:12px;color:#64748b;">Deskripsi</div>
                                        <div style="font-size:13px;color:#0f172a;margin-top:4px;line-height:1.6;">
                                            {{ $license->description ?? '-' }}
                                        </div>

                                        <div style="height:12px;line-height:12px;font-size:12px;">&nbsp;</div>

                                        <!-- ✅ Two columns desktop, stack mobile -->
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <!-- Left col -->
                                                <td class="col col-pad-r" valign="top"
                                                    style="width:50%;max-width:340px;padding-right:10px;">
                                                    <div style="font-size:12px;color:#64748b;">Kategori</div>
                                                    <div style="font-size:13px;color:#0f172a;margin-top:4px;">
                                                        {{ $license->category ?? '-' }}
                                                    </div>

                                                    <div style="height:12px;line-height:12px;font-size:12px;">&nbsp;
                                                    </div>

                                                    <div style="font-size:12px;color:#64748b;">Start Date</div>
                                                    <div style="font-size:13px;color:#0f172a;margin-top:4px;">
                                                        {{ isset($license->permit_start_date) ? \Carbon\Carbon::parse($license->permit_start_date)->format('d M Y') : '-' }}
                                                    </div>

                                                    <div style="height:12px;line-height:12px;font-size:12px;">&nbsp;
                                                    </div>

                                                    <div style="font-size:12px;color:#64748b;">Business Unit</div>
                                                    <div style="font-size:13px;color:#0f172a;margin-top:4px;">
                                                        {{ $businessUnitName ?? ($license->business_unit_id ?? '-') }}
                                                    </div>
                                                </td>

                                                <!-- Right col -->
                                                <td class="col col-pad-l" valign="top"
                                                    style="width:50%;max-width:340px;padding-left:10px;">
                                                    <div style="font-size:12px;color:#64748b;">Scope</div>
                                                    <div style="font-size:13px;color:#0f172a;margin-top:4px;">
                                                        {{ $license->scope ?? '-' }}
                                                    </div>

                                                    <div style="height:12px;line-height:12px;font-size:12px;">&nbsp;
                                                    </div>

                                                    <div style="font-size:12px;color:#64748b;">End Date</div>
                                                    <div
                                                        style="font-size:13px;color:#0f172a;margin-top:4px;font-weight:800;">
                                                        {{ isset($license->permit_end_date) ? \Carbon\Carbon::parse($license->permit_end_date)->format('d M Y') : '-' }}
                                                    </div>

                                                    <div style="height:12px;line-height:12px;font-size:12px;">&nbsp;
                                                    </div>

                                                    <div style="font-size:12px;color:#64748b;">Register Date</div>
                                                    <div style="font-size:13px;color:#0f172a;margin-top:4px;">
                                                        {{ isset($license->register_date) ? \Carbon\Carbon::parse($license->register_date)->format('d M Y') : '-' }}
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>

                                        @if (!empty($license->remarks))
                                            <div style="height:14px;line-height:14px;font-size:14px;">&nbsp;</div>

                                            <div style="font-size:12px;color:#64748b;">Catatan</div>
                                            <div style="font-size:13px;color:#0f172a;margin-top:4px;line-height:1.6;">
                                                {{ $license->remarks }}
                                            </div>
                                        @endif

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ✅ CTA: anti overflow -->
                    <tr>
                        <td class="px" style="padding:16px 22px 0 22px;">
                            @if (!empty($actionUrl))
                                <table role="presentation" class="btn-wrap" width="100%" cellpadding="0"
                                    cellspacing="0" style="width:100%;">
                                    <tr>
                                        <td align="left" style="width:100%;">
                                            <a href="{{ $actionUrl }}" class="btn-link"
                                                style="display:block;width:100%;max-width:100%;box-sizing:border-box;background:#0f172a;color:#ffffff;text-decoration:none;padding:12px 16px;border-radius:10px;font-size:13px;font-weight:800;text-align:center;">
                                                Lihat Detail &amp; Tindak Lanjut
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            <div style="margin-top:12px;font-size:12px;line-height:1.7;color:#64748b;">
                                @if ($isExpired)
                                    Status ini terdeteksi otomatis berdasarkan tanggal end date. Jika sudah
                                    diperpanjang, mohon update data agar notifikasi berhenti.
                                @else
                                    Pengingat ini membantu tim menyiapkan perpanjangan tepat waktu.
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="px" style="padding:18px 22px 22px 22px;">
                            <div
                                style="border-top:1px solid #e5e7eb;padding-top:14px;color:#94a3b8;font-size:11px;line-height:1.7;">
                                Email ini dikirim otomatis oleh sistem {{ $appName ?? 'Atraxsys' }}.
                                <br>
                                Jika butuh bantuan, silakan hubungi tim terkait atau balas email ini
                                <a href="mailto:Yan.Ajah.Dulu@BlinkIT.com"
                                    style="color:#64748b;text-decoration:underline;">
                                    Yan.Ajah.Dulu@BlinkIT.com
                                </a>.
                            </div>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>
</body>

</html>
