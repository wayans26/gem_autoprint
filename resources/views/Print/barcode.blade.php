<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>QR Label</title>
    <style>
        @page {
            margin: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background: #ffffff;
            color: #000000;
            font-family: DejaVu Sans, sans-serif;
        }

        .page {
            position: relative;
            width: 100%;
            height: 100%;
        }

        /* Area aman agar tidak terpotong */
        .safe-area {
            position: absolute;
            top: 16mm;
            right: 4mm;
            bottom: 16mm;
            left: 4mm;
        }

        /*
         * Area teks memakai ruang di atas QR.
         * bottom disisakan agar tidak tabrakan dengan QR block.
         */
        .text-area {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 40mm;
            /* ruang khusus untuk QR + barcode text */
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .text-cell {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

        .text-content {
            width: 100%;
            text-align: center;
        }

        .name {
            margin-bottom: 0.5mm;
            font-size: 12pt;
            font-weight: 700;
            line-height: 1;
            text-align: center;
        }

        .job {
            margin-bottom: 0.5mm;
            font-size: 12pt;
            font-weight: 700;
            line-height: 1;
            text-align: center;
        }

        .company {
            margin-bottom: 0.5mm;
            font-size: 12pt;
            font-weight: 700;
            line-height: 0.75;
            text-align: center;

            /* supaya otomatis wrap  */
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-word;
        }

        /*
         * QR block selalu di tengah bawah safe area
         */
        .qr-block {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 31mm;
            text-align: center;
        }

        .qrcode-wrapper {
            width: 20mm;
            height: 20mm;
            margin: 0 auto 1mm auto;
            text-align: center;
        }

        .qrcode-image {
            display: block;
            width: 20mm;
            height: 20mm;
            margin: 0 auto;
        }

        .barcode-text {
            margin: 0;
            font-size: 11pt;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: 0.5px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="safe-area">

            <div class="text-area">
                <div class="text-cell">
                    <div class="text-content">
                        <div class="name">{{ $nama }}</div>
                        <div class="job">{{ $job }}</div>
                        <div class="company">{{ $company }}</div>
                    </div>
                </div>
            </div>

            <div class="qr-block">
                <div class="qrcode-wrapper">
                    <img src="{{ $barcodeSvg }}" alt="QR Code" class="qrcode-image">
                </div>
            </div>

        </div>
    </div>
</body>

</html>
