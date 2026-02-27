<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen ASN - Admin Panel</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        'hijau': { '50': '#f0fdf4', '100': '#dcfce7', '200': '#bbf7d0', '300': '#86efac', '400': '#4ade80', '500': '#22c55e', '600': '#16a34a', '700': '#15803d' },
                        'slate': { '50': '#f8fafc', '100': '#f1f5f9', '200': '#e2e8f0' },
                        'merah': { '100': '#fee2e2', '500': '#ef4444', '700': '#b91c1c' },
                        'kuning': { '100': '#fef9c3', '700': '#a16207' }
                    }
                }
            }
        }
    </script>
    <style>
        .modal-backdrop, .custom-modal-backdrop {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.5); display: flex;
            justify-content: center; align-items: center; z-index: 9999;
            opacity: 0; visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .modal-backdrop.show, .custom-modal-backdrop.show { opacity: 1; visibility: visible; }
        .modal-content, .custom-modal-content {
            background-color: #fff; padding: 2rem; border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            width: 90%; max-width: 400px; transform: translateY(-20px);
            transition: transform 0.3s ease;
        }
        #modalAsn .modal-content { max-width: 672px; }
        .modal-backdrop.show .modal-content, .custom-modal-backdrop.show .custom-modal-content { transform: translateY(0); }
    </style>
</head>
<body class="font-sans bg-slate-50">
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

    <div class="relative min-h-screen lg:flex">
        <aside id="sidebar" class="bg-white w-64 flex-col fixed inset-y-0 left-0 transform -translate-x-full transition-transform duration-300 ease-in-out z-50 lg:relative lg:translate-x-0 lg:flex border-r border-slate-200">
            <div class="h-20 flex items-center px-6">
                <div class="flex items-center space-x-3">
                    <div class="bg-hijau-500 text-white p-2.5 rounded-lg shadow-sm"><i class="bi bi-shield-check text-xl"></i></div>
                    <div><h1 class="text-lg font-bold text-gray-800">ADMIN PANEL</h1><p class="text-xs text-gray-500">SITI</p></div>
                </div>
            </div>
            
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ url('/admin') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-grid-1x2-fill mr-3"></i> Dashboard</a>
                <a href="{{ url('/admin/asn') }}" class="flex items-center px-4 py-2.5 text-white bg-hijau-500 rounded-lg font-semibold"><i class="bi bi-people-fill mr-3"></i> Manajemen ASN</a>
                <a href="{{ url('/admin/cuti') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-calendar-range-fill mr-3"></i> Manajemen Jenis Cuti</a>
                <a href="{{ url('/admin/atasan') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-person-badge-fill mr-3"></i> Manajemen Atasan</a>
                <a href="{{ url('/admin/libur') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-calendar-x-fill mr-3"></i> Manajemen Hari Libur</a>
                <a href="{{ url('/admin/log') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-file-earmark-text-fill mr-3"></i> Log Aktivitas</a>
            </nav>

            <div class="p-4 mt-auto">
                <a href="{{ url('/logout') }}" class="flex items-center justify-center w-full px-4 py-2.5 text-red-500 bg-red-50 hover:bg-red-100 rounded-lg font-semibold"><i class="bi bi-box-arrow-right mr-3"></i> Logout</a>
            </div>
        </aside>

        <main class="flex-1 p-4 md:p-8 overflow-y-auto">
            <header class="lg:hidden flex items-center justify-between mb-8">
                 <div class="flex items-center space-x-3">
                    <div class="bg-hijau-500 text-white p-2.5 rounded-lg shadow-sm"><i class="bi bi-shield-check text-xl"></i></div>
                    <div><h1 class="text-lg font-bold text-gray-800">ADMIN PANEL</h1></div>
                </div>
                <button id="menu-toggle" class="text-2xl text-gray-700 p-2"><i class="bi bi-list"></i></button>
            </header>

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Manajemen Akun ASN</h1>
                <p class="text-gray-500 mt-1">Tambah, edit, dan kelola akun untuk Aparatur Sipil Negara.</p>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-hijau-100 text-hijau-700 rounded-lg font-semibold">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg font-semibold">{{ $errors->first() }}</div>
            @endif

            <div class="flex flex-col md:flex-row items-center justify-between mb-6 gap-4">
                <div class="relative w-full md:w-1/3">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchInput" placeholder="Cari ASN..." class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400">
                </div>
                <button id="btnTambahAsn" class="w-full md:w-auto bg-hijau-500 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:bg-hijau-600 flex items-center justify-center space-x-2">
                    <i class="bi bi-plus-circle-fill"></i><span>Tambah ASN Baru</span>
                </button>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="p-4 font-semibold text-gray-600 text-sm">Nama Lengkap</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm hidden md:table-cell">Jabatan</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm hidden lg:table-cell">Masa Kerja</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm hidden lg:table-cell">Atasan</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm text-center">Sisa Cuti</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="asnTableBody" class="divide-y divide-slate-100">
                            @forelse($asn as $a)
                                <tr class="asn-row hover:bg-slate-50" data-name="{{ strtolower($a->name) }}" data-nip="{{ strtolower($a->nip) }}">
                                    <td class="p-4">
                                        <p class="font-semibold text-gray-800">{{ $a->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $a->pangkat_gol ?? 'Pangkat/Gol belum diisi' }}</p>
                                        <p class="text-xs text-gray-500">NIP: {{ $a->nip }}</p>
                                        <p class="text-xs text-gray-500"><i class="bi bi-telephone-fill"></i> {{ $a->telepon ?? '-' }}</p>
                                        <p class="text-sm text-gray-600 md:hidden mt-1">{{ $a->jabatan }}</p>
                                    </td>
                                    <td class="p-4 text-sm text-gray-600 hidden md:table-cell">{{ $a->jabatan }}</td>
                                    <td class="p-4 text-sm text-gray-600 hidden lg:table-cell">{{ $a->masa_kerja_tahun }} thn, {{ $a->masa_kerja_bulan }} bln</td>
                                    <td class="p-4 text-sm text-gray-600 hidden lg:table-cell">
                                        {{ $a->atasan1 ? '1. NIP: '.$a->atasan1 : '-' }} <br>
                                        {{ $a->atasan2 ? '2. NIP: '.$a->atasan2 : '' }}
                                    </td>
                                    <td class="p-4 text-sm text-gray-600 text-center font-bold text-hijau-600">
                                        {{ ($a->sisa_cuti_tahun_ini + $a->sisa_cuti_tahun_lalu) - $a->cuti_diambil }}
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ url('/admin/asn/'.$a->id.'/hapus') }}" onclick="return confirm('Yakin ingin menghapus ASN {{ $a->name }}?')" title="Hapus Akun" class="text-gray-500 hover:text-red-600 p-2 rounded-md hover:bg-red-100"><i class="bi bi-trash-fill"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center p-4 text-gray-500">Tidak ada data ditemukan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div id="modalAsn" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center p-4 z-50 modal-backdrop">
        <div class="bg-white rounded-2xl shadow-xl w-full p-6 md:p-8 modal-content overflow-y-auto max-h-screen">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Tambah ASN Baru</h3>
                <button id="closeModalAsnButton" class="text-gray-400 hover:text-gray-600 text-3xl">&times;</button>
            </div>
            
            <form action="{{ url('/admin/asn') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label><input type="text" name="name" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" required></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">NIP</label><input type="text" name="nip" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" pattern="[0-9]*" required></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label><input type="text" name="jabatan" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" required></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Pangkat / Golongan</label><input type="text" name="pangkat_gol" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" placeholder="Contoh: Penata Muda / III a"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label><input type="tel" name="telepon" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" placeholder="Contoh: 08123456789"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">TMT Pangkat</label><input type="date" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400"></div>
                    
                    <div class="md:col-span-2 grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Masa Kerja Awal (Tahun)</label><input type="number" name="masa_kerja_tahun" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" value="0"></div>
                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Masa Kerja Awal (Bulan)</label><input type="number" name="masa_kerja_bulan" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" value="0"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Atasan Langsung 1</label>
                        <select name="atasan1" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400">
                            <option value="">Pilih Atasan</option>
                            @foreach($dataAtasan as $atasan)
                                <option value="{{ $atasan->nip }}">{{ $atasan->name }} ({{ $atasan->jabatan }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Atasan Langsung 2 (Opsional)</label>
                        <select name="atasan2" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400">
                            <option value="">Pilih Atasan</option>
                            @foreach($dataAtasan as $atasan)
                                <option value="{{ $atasan->nip }}">{{ $atasan->name }} ({{ $atasan->jabatan }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Cuti Tahun Ini</label><input type="number" name="sisa_cuti_tahun_ini" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" value="12" required></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Sisa Cuti Tahun Lalu</label><input type="number" name="sisa_cuti_tahun_lalu" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" value="0" required></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Username Login</label><input type="text" name="username" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" required></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Password Login</label><input type="password" name="password" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" placeholder="Minimal 6 karakter" required></div>
                </div>
                <div class="flex justify-end gap-4 mt-8">
                    <button id="batalAsn" type="button" class="px-6 py-3 bg-slate-200 text-gray-800 rounded-lg hover:bg-slate-300 font-semibold">Batal</button>
                    <button type="submit" class="px-6 py-3 bg-hijau-500 text-white rounded-lg hover:bg-hijau-600 font-semibold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Menu Toggle
            const menuToggle = document.getElementById('menu-toggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            function toggleMenu() {
                sidebar.classList.toggle('-translate-x-full');
                sidebarOverlay.classList.toggle('hidden');
            }

            if (menuToggle) menuToggle.addEventListener('click', toggleMenu);
            if (sidebarOverlay) sidebarOverlay.addEventListener('click', toggleMenu);

            // Modal Logic
            const modalAsn = document.getElementById('modalAsn');
            const btnTambahAsn = document.getElementById('btnTambahAsn');
            const closeModalAsnButton = document.getElementById('closeModalAsnButton');
            const batalAsn = document.getElementById('batalAsn');

            function openModal() {
                modalAsn.classList.remove('hidden');
                setTimeout(() => modalAsn.classList.add('show'), 10);
            }

            function closeModal() {
                modalAsn.classList.remove('show');
                setTimeout(() => modalAsn.classList.add('hidden'), 300);
            }

            btnTambahAsn.addEventListener('click', openModal);
            closeModalAsnButton.addEventListener('click', closeModal);
            batalAsn.addEventListener('click', closeModal);

            // Live Search Logic (Tanpa request ke server)
            const searchInput = document.getElementById('searchInput');
            const rows = document.querySelectorAll('.asn-row');

            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                rows.forEach(row => {
                    const name = row.getAttribute('data-name');
                    const nip = row.getAttribute('data-nip');
                    if(name.includes(searchTerm) || nip.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>