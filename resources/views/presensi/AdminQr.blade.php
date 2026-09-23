<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi - DMU Group</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-900 to-gray-900 min-h-screen flex flex-col justify-between font-sans antialiased">

    <!-- Header / Jam Digital -->
    <div class="text-center pt-10">
        <h1 class="text-3xl font-bold text-white tracking-wider">PORTAL PRESENSI KARYAWAN</h1>
        <p class="text-blue-200 mt-2 text-lg">Silakan buka aplikasi HRIS di HP Anda dan scan QR Code di bawah</p>
    </div>

    <!-- Kotak QR Code Center -->
    <div class="flex-1 flex justify-center items-center">
        <div class="bg-white p-8 rounded-2xl shadow-2xl flex flex-col items-center transform transition hover:scale-105">
            
            <!-- Tempat QR Code dirender oleh JavaScript yang sudah ada -->
            <div id="qr-container" class="w-72 h-72 bg-gray-100 flex justify-center items-center rounded-lg border-2 border-dashed border-gray-300">
                <!-- Jika menggunakan plugin QR lama, biarkan tag div/canvas bawaannya di sini -->
                <div id="qrcode"></div>
            </div>

            <p class="mt-6 text-sm font-semibold text-red-600 animate-pulse">
                Token berganti otomatis demi keamanan...
            </p>
        </div>
    </div>

    <!-- Footer 3 Perusahaan -->
    <div class="bg-white/10 backdrop-blur-md border-t border-white/20 py-6">
        <div class="max-w-4xl mx-auto flex justify-center space-x-12 items-center px-4">
            <span class="text-white/80 font-semibold text-sm uppercase tracking-wider">PT. Daya Matahari Utama</span>
            <span class="text-white/30">|</span>
            <span class="text-white/80 font-semibold text-sm uppercase tracking-wider">PT. Dahlia Mitra Global</span>
            <span class="text-white/30">|</span>
            <span class="text-white/80 font-semibold text-sm uppercase tracking-wider">PT. Relasi Laksana Wisata</span>
        </div>
    </div>

    <!-- Pastikan script auto-refresh QR Code bawaan Anda diletakkan di bawah sini -->
</body>
</html>