<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Presensi</title>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body style="font-family: sans-serif; background-color: #f3f4f6; margin: 0; padding: 2rem; display: flex; justify-content: center; align-items: center; min-height: 100vh;">
    <div style="background: white; padding: 2rem; border-radius: 1rem; width: 100%; max-width: 450px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        <h2 style="text-align: center; color: #1f2937; margin-bottom: 1.5rem;">Arahkan Kamera ke Layar</h2>
        <div id="reader" style="width: 100%;"></div>
        
        <form id="attendance-form" action="{{ route('attendance.scan') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="qr_token" id="qr_token">
        </form>
    </div>

    <script>
        const html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 15, qrbox: { width: 250, height: 250 } },
            false
        );

        function onScanSuccess(decodedText) {
            html5QrcodeScanner.clear();
            document.getElementById('qr_token').value = decodedText;
            document.getElementById('attendance-form').submit();
        }

        html5QrcodeScanner.render(onScanSuccess);
    </script>
</body>
</html>