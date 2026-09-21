<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Karyawan - DMU</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans antialiased">
    
    <!-- Navbar -->
    <nav class="bg-white shadow-sm px-6 py-4 flex justify-between items-center">
        <div>
            <h1 class="text-xl font-bold text-blue-600">Portal Karyawan</h1>
            <p class="text-xs text-slate-500">PT Daya Matahari Utama</p>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-slate-600">Halo, {{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-lg font-medium transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Content -->
    <main class="max-w-5xl mx-auto px-6 py-8">
        
        <!-- Header & Tombol Scan -->
        <div class="flex justify-between items-end mb-6 bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Ringkasan Kehadiran</h2>
                <p class="text-slate-500 text-sm mt-1">Lihat riwayat kehadiran Anda di bawah ini.</p>
            </div>
            
            <a href="{{ route('attendance.scan.view') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-bold shadow-md transition-all flex items-center gap-2 transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Scan Kehadiran Hari Ini
            </a>
        </div>

        <!-- Tabel Riwayat -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Waktu Scan</th>
                        <th class="px-6 py-4 font-semibold">IP Address Anda</th>
                        <th class="px-6 py-4 font-semibold text-center">Status HRD</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($riwayat_absen as $absen)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($absen->waktu_absen)->format('d M Y, H:i:s') }}</td>
                            <td class="px-6 py-4 text-xs font-mono bg-slate-100 rounded px-2 py-1 mx-6 inline-block mt-3">{{ $absen->ip_address }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($absen->status === 'Hadir')
                                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold">Hadir</span>
                                @elseif($absen->status === 'Menunggu ACC')
                                    <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-bold">Menunggu ACC</span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">{{ $absen->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-slate-500">
                                Anda belum memiliki riwayat absen.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>