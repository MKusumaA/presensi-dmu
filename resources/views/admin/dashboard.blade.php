<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard HRD - PT Daya Matahari Utama</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <nav class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center shadow-sm">
        <div class="font-bold text-xl text-blue-700">HRIS Portal</div>
        <div class="flex items-center space-x-4">
            <span class="text-sm font-medium text-gray-600">Administrator (HRD)</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">Logout</button>
            </form>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Dashboard Manajemen Presensi</h2>
                <p class="text-gray-500 text-sm mt-1">Kelola data kehadiran harian dan koreksi status karyawan.</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="openKaryawanModal()" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow-sm text-sm font-medium transition">
                    + Tambah Karyawan
                </button>
                <a href="{{ route('admin.presensi.export.csv') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow-sm text-sm font-medium transition">
                    Export Laporan (CSV)
                </a>
            </div>
        </div>

        <!-- BAGIAN 1: PRESENSI HARI INI -->
        <div class="bg-white rounded-lg shadow-sm border border-blue-100 mb-8 overflow-hidden">
            <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-blue-900">
                    Data Presensi Hari Ini ({{ \Carbon\Carbon::today()->format('d F Y') }})
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                            <th class="p-4 font-semibold">Nama Karyawan</th>
                            <th class="p-4 font-semibold">Waktu Scan</th>
                            <th class="p-4 font-semibold">Status</th>
                            <th class="p-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-hari-ini">
                        <?php if (count($presensiHariIni) == 0): ?>
                        <tr id="empty-row">
                            <td colspan="4" class="p-8 text-center text-gray-500 italic">Belum ada data presensi hari ini.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($presensiHariIni as$absen): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="p-4 font-medium text-gray-800">{{ $absen->user->name }}</td>
                                <td class="p-4 text-gray-600">{{ $absen->created_at->format('H:i:s') }} WIB</td>
                                <td class="p-4">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full border bg-gray-100 text-gray-700">
                                        {{ $absen->status ?? 'Menunggu' }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <button onclick="openModal('{{ $absen->id }}')" class="text-sm text-blue-600 border border-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded transition font-medium">
                                        Edit Status
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- BAGIAN 2: RIWAYAT SEBELUMNYA -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    Riwayat Presensi Sebelumnya
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                            <th class="p-4 font-semibold">Tanggal</th>
                            <th class="p-4 font-semibold">Nama Karyawan</th>
                            <th class="p-4 font-semibold">Status Akhir</th>
                            <th class="p-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($riwayatPresensi) == 0): ?>
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-500 italic">Belum ada riwayat data.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($riwayatPresensi as$riwayat): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="p-4 text-gray-600">{{ $riwayat->created_at->format('d M Y') }}</td>
                                <td class="p-4 font-medium text-gray-800">{{ $riwayat->user->name }}</td>
                                <td class="p-4 text-gray-700 font-medium">{{ $riwayat->status }}</td>
                                <td class="p-4 text-center">
                                    <button onclick="openModal('{{ $riwayat->id }}')" class="text-sm text-gray-600 border border-gray-400 hover:bg-gray-100 px-3 py-1.5 rounded transition font-medium">
                                        Koreksi Data
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200">
                {{ $riwayatPresensi->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Tambah Karyawan -->
    <div id="karyawanModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">Registrasi Karyawan Baru</h3>
                <button onclick="closeKaryawanModal()" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.karyawan.store') }}">
                @csrf
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Perusahaan</label>
                        <input type="email" name="email" required class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Sementara</label>
                        <input type="password" name="password" required class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Entitas Perusahaan</label>
                        <select name="company_entity" required class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            <option value="PT. DMU">PT. Daya Matahari Utama (DMU)</option>
                            <option value="PT. DMS">PT. Dahlia Mitra Solusi (DMS)</option>
                            <option value="PT. RLW">PT. Relasi Laksana Wisata (RLW)</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeKaryawanModal()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 transition">Simpan Karyawan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Status -->
    <div id="statusModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">Koreksi Status Kehadiran</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
            </div>
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="px-6 py-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Keterangan Baru</label>
                    <select name="status" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="Hadir">Hadir (Tepat Waktu)</option>
                        <option value="MT">MT (Masuk Telat)</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Ijin">Ijin</option>
                        <option value="TMDL">TMDL (Tidak Masuk Dinas Luar)</option>
                        <option value="TMTD">TMTD (Tidak Masuk Tanpa Keterangan)</option>
                        <option value="TA">TA (Tidak Absen)</option>
                        <option value="Ditolak (Alpa)">Ditolak (Alpa)</option>
                    </select>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 transition">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script UI Modal -->
    <script>
        function openModal(id) {
            document.getElementById('statusModal').classList.remove('hidden');
            document.getElementById('statusModal').classList.add('flex');
            document.getElementById('editForm').action = '/admin/presensi/' + id + '/update-status';
        }

        function closeModal() {
            document.getElementById('statusModal').classList.add('hidden');
            document.getElementById('statusModal').classList.remove('flex');
        }

        function openKaryawanModal() {
            document.getElementById('karyawanModal').classList.remove('hidden');
            document.getElementById('karyawanModal').classList.add('flex');
        }

        function closeKaryawanModal() {
            document.getElementById('karyawanModal').classList.add('hidden');
            document.getElementById('karyawanModal').classList.remove('flex');
        }
    </script>

    @if (session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2000,
                });
            });
        </script>
    @endif

    <!-- Script Laravel Echo & Pusher untuk WebSocket -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
    
    <script>
        window.Pusher = Pusher;
        
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: '{{ env("REVERB_APP_KEY") }}',
            wsHost: window.location.hostname,
            wsPort: parseInt('{{ env("REVERB_PORT", 8080) }}'),
            wssPort: parseInt('{{ env("REVERB_PORT", 8080) }}'),
            forceTLS: false,
            enabledTransports: ['ws', 'wss'],
        });

        window.Echo.channel('hrd-dashboard')
            .listen('.presensi.baru', (data) => {
                
                let tbody = document.getElementById('table-hari-ini');
                
                let emptyRow = document.getElementById('empty-row');
                if (emptyRow) {
                    emptyRow.remove();
                }

                let tr = document.createElement('tr');
                tr.className = 'border-b border-gray-100 transition bg-green-100'; 

                tr.innerHTML = `
                    <td class="p-4 font-medium text-gray-800">${data.namaKaryawan}</td>
                    <td class="p-4 text-gray-600">${data.waktuScan}</td>
                    <td class="p-4">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full border bg-gray-100 text-gray-700">
                            ${data.presensi.status}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <button onclick="openModal('${data.presensi.id}')" class="text-sm text-blue-600 border border-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded transition font-medium">
                            Edit Status
                        </button>
                    </td>
                `;
                
                if(tbody) tbody.prepend(tr);
                
                setTimeout(() => {
                    tr.classList.remove('bg-green-100');
                    tr.classList.add('hover:bg-gray-50');
                }, 3000);
            });
    </script>
</body>
</html>