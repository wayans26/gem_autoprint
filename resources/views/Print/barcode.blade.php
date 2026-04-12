<!DOCTYPE html>
<html lang="en">

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
