<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include SweetAlert dan HTML5-QRCode Script yang sudah Anda pasang sebelumnya -->
</head>
<body class="bg-gray-50 min-h-screen font-sans text-gray-800">

    <!-- Header Mobile Sapaan & Logo -->
    <div class="bg-white px-6 py-5 shadow-sm border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Selamat Datang,</p>
                <h2 class="text-lg font-bold text-blue-900">{{ Auth::user()->name }}</h2>
            </div>
            
            <!-- Logo Perusahaan dengan Penanganan Ukuran Otomatis -->
            <div>
                <img src="{{ asset('logos/dmu.png') }}" alt="Logo Perusahaan" class="h-10 w-auto object-contain">
            </div>
        </div>
    </div>

    <!-- Area Kamera -->
    <div class="max-w-md mx-auto p-4 mt-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-blue-50 px-4 py-3 border-b border-blue-100">
                <h3 class="text-center font-semibold text-blue-800">Arahkan Kamera ke Layar Kiosk</h3>
            </div>
            
            <div class="p-4">
                <!-- Wrapper agar kamera pas di layar HP -->
                <div id="reader" class="w-full h-auto rounded-lg overflow-hidden border-2 border-dashed border-gray-300"></div>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('karyawan.dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Pastikan Form POST dan Script Html5QrcodeScanner Anda ada di bawah sini -->
</body>
</html>