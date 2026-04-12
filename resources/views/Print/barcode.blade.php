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
            font-family: DejaVu Sans, sans-serif;
            color: #000000;
            background: #ffffff;
        }

        .page {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .safe-area {
            position: absolute;
            top: 4mm;
            right: 4mm;
            bottom: 4mm;
            left: 4mm;
            display: table;
            width: auto;
            height: auto;
            table-layout: fixed;
        }

        .content {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

        .name {
            font-size: 20pt;
            font-weight: 700;
            line-height: 1.15;
            margin-bottom: 0.5mm;
        }

        .job {
            font-size: 12pt;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 0.5mm;
        }

        .company {
            font-size: 12pt;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 0.5mm;
        }

        .qrcode-wrapper {
            width: 16mm;
            height: 16mm;
            margin: 0 auto 0.5mm auto;
            text-align: center;
        }

        .qrcode-image {
            display: block;
            width: 16mm;
            height: 16mm;
            margin: 0 auto;
        }

        .barcode-text {
            font-size: 11pt;
            font-weight: 700;
            letter-spacing: 0.6px;
            line-height: 1.2;
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="safe-area">
            <div class="content">
                <div class="name">{{ $nama }}</div>
                <div class="job">{{ $job }}</div>
                <div class="company">{{ $company }}</div>

                <div class="qrcode-wrapper">
                    <img src="{{ $barcodeSvg }}" alt="QR Code" class="qrcode-image">
                </div>
            </div>
        </div>
    </div>
</body>

</html>
