<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - QR Presensi</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f3f4f6; margin: 0; font-family: sans-serif;">
    <div style="background: white; padding: 3rem; border-radius: 1rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); text-align: center;">
        <h1 style="color: #1f2937; margin-bottom: 2rem;">Presensi Karyawan</h1>
        <div id="qrcode" style="display: flex; justify-content: center;"></div>
        <p id="timer" style="color: #ef4444; margin-top: 2rem; font-weight: bold; font-size: 1.2rem;"></p>
    </div>

    <script>
        const qrContainer = document.getElementById("qrcode");
        const timerDisplay = document.getElementById("timer");
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let countdown = 15;

        async function fetchToken() {
            try {
                const response = await fetch('/api/qr/generate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                const data = await response.json();
                
                qrContainer.innerHTML = "";
                new QRCode(qrContainer, {
                    text: data.token,
                    width: 350,
                    height: 350,
                    colorDark : "#000000",
                    colorLight : "#ffffff",
                    correctLevel : QRCode.CorrectLevel.H
                });
                
                countdown = 15;
            } catch (error) {
                console.error(error);
            }
        }

        setInterval(() => {
            countdown--;
            timerDisplay.innerText = `Token berganti dalam ${countdown} detik...`;
            
            if (countdown <= 0) {
                fetchToken();
            }
        }, 1000);

        fetchToken();
    </script>
</body>
</html>