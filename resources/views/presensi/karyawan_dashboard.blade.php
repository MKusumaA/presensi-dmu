<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Karyawan - DMU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">
    
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 px-4 py-3 sm:px-6 sm:py-4 flex flex-wrap justify-between items-center sticky top-0 z-10">
        <div class="mb-2 sm:mb-0">
            <h1 class="text-lg sm:text-xl font-bold text-blue-900 tracking-tight">Portal Karyawan</h1>
            <p class="text-xs text-gray-500 uppercase tracking-widest">PT Daya Matahari Utama</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm font-medium text-gray-600 hidden sm:inline-block">Halo, {{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs sm:text-sm bg-gray-100 text-gray-700 hover:bg-gray-200 px-3 py-1.5 sm:px-4 sm:py-2 rounded font-medium transition-colors border border-gray-200">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Content -->
    <main class="w-full max-w-5xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
        
        <!-- Header & Tombol Scan -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-6 bg-white p-5 rounded-lg border border-gray-200">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Ringkasan Kehadiran</h2>
                <p class="text-gray-500 text-sm mt-1">Riwayat absensi Anda bulan ini.</p>
            </div>
            
            <a href="{{ route('attendance.scan.view') }}" class="w-full sm:w-auto bg-blue-700 hover:bg-blue-800 text-white px-5 py-2.5 rounded text-sm font-semibold transition-colors flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Scan Kehadiran
            </a>
        </div>

        <!-- Tabel Riwayat -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 whitespace-nowrap">
                    <thead class="bg-gray-50 border-b border-gray-200 text-gray-700 uppercase text-xs font-semibold tracking-wider">
                        <tr>
                            <th class="px-4 py-3 sm:px-6 sm:py-4">Waktu Scan</th>
                            <th class="px-4 py-3 sm:px-6 sm:py-4 hidden sm:table-cell">IP Address</th>
                            <th class="px-4 py-3 sm:px-6 sm:py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($riwayat_absen as $absen)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-4 py-3 sm:px-6 sm:py-4">
                                    <div class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($absen->waktu_absen)->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($absen->waktu_absen)->format('H:i:s') }}</div>
                                </td>
                                <td class="px-4 py-3 sm:px-6 sm:py-4 hidden sm:table-cell">
                                    <span class="text-xs font-mono text-gray-500 bg-gray-50 px-2 py-1 rounded border border-gray-100">{{ $absen->ip_address }}</span>
                                </td>
                                <td class="px-4 py-3 sm:px-6 sm:py-4 text-center">
                                    @if($absen->status === 'Hadir')
                                        <span class="bg-green-50 text-green-700 border border-green-200 px-2.5 py-1 rounded-sm text-xs font-semibold tracking-wide">HADIR</span>
                                    @elseif($absen->status === 'Menunggu ACC')
                                        <span class="bg-yellow-50 text-yellow-700 border border-yellow-200 px-2.5 py-1 rounded-sm text-xs font-semibold tracking-wide">MENUNGGU</span>
                                    @else
                                        <span class="bg-red-50 text-red-700 border border-red-200 px-2.5 py-1 rounded-sm text-xs font-semibold tracking-wide">{{ strtoupper($absen->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 sm:px-6 text-center text-gray-500 text-sm">
                                    Belum ada data kehadiran bulan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>