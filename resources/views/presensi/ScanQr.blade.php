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
        
        <!-- LOGIKA JIKA SUKSES -->
        @if(session('success'))
            <div style="text-align: center;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">✅</div>
                <h2 style="color: #10b981; margin-bottom: 0.5rem;">Absen Berhasil!</h2>
                <p style="color: #4b5563; margin-bottom: 2rem;">{{ session('success') }}</p>
                <a href="{{ route('attendance.scan.view') }}" style="display: inline-block; padding: 0.75rem 1.5rem; background-color: #1f2937; color: white; text-decoration: none; border-radius: 0.5rem; font-weight: bold;">Tutup & Kembali</a>
            </div>
        
        <!-- LOGIKA AWAL / ERROR (Tampilkan Kamera) -->
        @else
            <h2 style="text-align: center; color: #1f2937; margin-bottom: 1.5rem;">Arahkan Kamera ke Layar</h2>
            
            @if(session('error'))
                <div style="background: #fee2e2; color: #ef4444; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; text-align: center; font-weight: bold;">
                    ❌ {{ session('error') }}
                </div>
            @endif

            <div id="reader" style="width: 100%;"></div>
            
            <p id="status-text" style="text-align: center; color: #10b981; font-weight: bold; display: none; margin-top: 1rem; font-size: 1.2rem;">
                Berhasil membaca QR! Memproses...
            </p>
            
            <form id="attendance-form" action="{{ route('attendance.scan') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="qr_token" id="qr_token">
            </form>

            <script>
                const html5QrcodeScanner = new Html5QrcodeScanner(
                    "reader",
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    false
                );

                let isScanned = false; 

                function onScanSuccess(decodedText) {
                    if (isScanned) return; 
                    isScanned = true; 
                    
                    document.getElementById('reader').style.display = 'none';
                    document.getElementById('status-text').style.display = 'block';

                    html5QrcodeScanner.clear();

                    document.getElementById('qr_token').value = decodedText;
                    document.getElementById('attendance-form').submit();
                }

                html5QrcodeScanner.render(onScanSuccess);
            </script>
        @endif
    </div>
</body>
</html>