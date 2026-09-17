<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard HRD - DMU</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans antialiased">
    
    <!-- Navbar -->
    <nav class="bg-white shadow-sm px-6 py-4 flex justify-between items-center">
        <div>
            <h1 class="text-xl font-bold text-slate-800">HRIS Dashboard</h1>
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
    <main class="max-w-7xl mx-auto px-6 py-8">
        
        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-600 p-4 rounded-lg mb-6 text-sm font-semibold border border-emerald-200">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 text-sm font-semibold border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex justify-between items-end mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Data Presensi Harian</h2>
                <p class="text-slate-500 text-sm mt-1">Kelola dan verifikasi kehadiran karyawan</p>
            </div>
            <!-- Tombol Export CSV -->
            <a href="{{ route('admin.presensi.export.csv') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export Laporan (CSV)
            </a>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Nama Karyawan</th>
                        <th class="px-6 py-4 font-semibold">Waktu Scan</th>
                        <th class="px-6 py-4 font-semibold">IP Address</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi HRD</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($presensis as $presensi)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $presensi->user->name }}</td>
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($presensi->waktu_absen)->format('d M Y, H:i:s') }}</td>
                            <td class="px-6 py-4 text-xs font-mono bg-slate-100 rounded px-2 py-1 mx-6 inline-block mt-3">{{ $presensi->ip_address }}</td>
                            <td class="px-6 py-4">
                                @if($presensi->status === 'Hadir')
                                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold">Hadir</span>
                                @elseif($presensi->status === 'Menunggu ACC')
                                    <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-bold">Menunggu ACC</span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">{{ $presensi->status }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($presensi->status === 'Menunggu ACC')
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Tombol ACC -->
                                        <form action="{{ route('admin.presensi.acc', $presensi->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" onclick="return confirm('Yakin ingin ACC presensi ini?')" class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1 rounded text-xs font-semibold transition-colors">ACC</button>
                                        </form>

                                        <!-- Tombol Tolak -->
                                        <form action="{{ route('admin.presensi.tolak', $presensi->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" onclick="return confirm('Yakin ingin MENOLAK presensi ini?')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-semibold transition-colors">Tolak</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-slate-400 text-xs italic">- Selesai -</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                Belum ada data absensi hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>