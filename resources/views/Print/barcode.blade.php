<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Barcode Label</title>
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

        /* Safe margin agar hasil print tidak mepet / terpotong */
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
            margin-bottom: 4mm;
        }

        .job {
            font-size: 13pt;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1.5mm;
        }

        .company {
            font-size: 12pt;
            line-height: 1.2;
            margin-bottom: 4mm;
        }

        .barcode-wrapper {
            width: 76mm;
            margin: 0 auto 2mm auto;
            text-align: center;
        }

        .barcode-wrapper svg {
            display: block;
            width: 100%;
            height: 16mm;
            margin: 0 auto;
        }

        .barcode-text {
            font-size: 11pt;
            font-weight: 700;
            letter-spacing: 0.7px;
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

                <div class="barcode-wrapper">
                    {!! $barcodeSvg !!}
                </div>
            </div>
        </div>
    </div>
</body>

</html>
