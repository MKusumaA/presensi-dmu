<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Karyawan - PT Daya Matahari Utama</title>
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
                <h2 class="text-2xl font-bold text-gray-900">Kelola Data Karyawan</h2>
                <p class="text-gray-500 text-sm mt-1">Daftar semua akun karyawan pada sistem.</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.dashboard') }}" class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow-sm text-sm font-medium transition">
                    &larr; Kembali ke Dashboard
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Daftar Karyawan</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                            <th class="p-4 font-semibold">Nama</th>
                            <th class="p-4 font-semibold">Email</th>
                            <th class="p-4 font-semibold">Entitas Perusahaan</th>
                            <th class="p-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($karyawans as $karyawan)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="p-4 font-medium text-gray-800">{{ $karyawan->name }}</td>
                            <td class="p-4 text-gray-600">{{ $karyawan->email }}</td>
                            <td class="p-4 text-gray-600">{{ $karyawan->company_entity ?? '-' }}</td>
                            <td class="p-4 flex justify-center space-x-2">
                                <button onclick="openEditModal({{ $karyawan->id }}, '{{ addslashes($karyawan->name) }}', '{{ addslashes($karyawan->email) }}', '{{ addslashes($karyawan->company_entity) }}')" class="text-sm text-blue-600 border border-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded transition font-medium">
                                    Edit
                                </button>
                                <form action="{{ route('admin.karyawan.destroy', $karyawan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus karyawan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 border border-red-600 hover:bg-red-50 px-3 py-1.5 rounded transition font-medium">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-500 italic">Belum ada data karyawan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Edit Karyawan -->
    <div id="editKaryawanModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">Edit Karyawan</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
            </div>
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" id="edit_name" required class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Perusahaan</label>
                        <input type="email" name="email" id="edit_email" required class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Entitas Perusahaan</label>
                        <select name="company_entity" id="edit_company_entity" required class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            <option value="PT. DMU">PT. Daya Matahari Utama (DMU)</option>
                            <option value="PT. DMS">PT. Dahlia Mitra Solusi (DMS)</option>
                            <option value="PT. RLW">PT. Relasi Laksana Wisata (RLW)</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 transition">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script UI Modal -->
    <script>
        function openEditModal(id, name, email, companyEntity) {
            document.getElementById('editKaryawanModal').classList.remove('hidden');
            document.getElementById('editKaryawanModal').classList.add('flex');
            
            document.getElementById('editForm').action = '/admin/karyawan/' + id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            
            // Set select value
            const select = document.getElementById('edit_company_entity');
            for(let i=0; i<select.options.length; i++) {
                if(select.options[i].value === companyEntity) {
                    select.selectedIndex = i;
                    break;
                }
            }
        }

        function closeEditModal() {
            document.getElementById('editKaryawanModal').classList.add('hidden');
            document.getElementById('editKaryawanModal').classList.remove('flex');
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
</body>
</html>
