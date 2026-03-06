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
                        'slate': { '50': '#f8fafc', '100': '#f1f5f9', '200': '#e2e8f0' }
                    }
                }
            }
        }
    </script>
    <style>
        .modal-backdrop {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.5); display: flex;
            justify-content: center; align-items: center; z-index: 9999;
            opacity: 0; visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .modal-backdrop.show { opacity: 1; visibility: visible; }
        .modal-content {
            background-color: #fff;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            width: 90%; 
            max-width: 800px;
            transform: translateY(-20px);
            transition: transform 0.3s ease;
        }
        .modal-backdrop.show .modal-content { transform: translateY(0); }
        
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
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
                <a href="{{ url('/logout') }}" class="link-confirm flex items-center justify-center w-full px-4 py-2.5 text-red-500 bg-red-50 hover:bg-red-100 rounded-lg font-semibold" data-title="Keluar dari Sistem?" data-text="Anda harus login kembali untuk masuk." data-icon="warning">
                    <i class="bi bi-box-arrow-right mr-3"></i> Logout
                </a>
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

            <div class="flex flex-col md:flex-row items-center justify-between mb-6 gap-4">
                <div class="relative w-full md:w-1/3">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchInput" placeholder="Cari Nama / NIP..." class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400 shadow-sm">
                </div>
                <button id="btnTambahAsn" class="w-full md:w-auto bg-hijau-500 text-white font-bold py-2.5 px-5 rounded-lg shadow-sm hover:bg-hijau-600 flex items-center justify-center space-x-2 transition-colors">
                    <i class="bi bi-plus-lg"></i><span>Tambah ASN Baru</span>
                </button>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="p-4 font-semibold text-gray-600 text-sm">Profil Pegawai</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm hidden md:table-cell">Jabatan</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm hidden lg:table-cell">Masa Kerja</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm hidden lg:table-cell">Atasan Langsung</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm text-center">Sisa Cuti</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="asnTableBody" class="divide-y divide-slate-100">
                            @forelse($asn as $a)
                                <tr class="asn-row hover:bg-slate-50 transition-colors" data-name="{{ strtolower($a->name) }}" data-nip="{{ strtolower($a->nip) }}">
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-hijau-100 text-hijau-600 flex items-center justify-center font-bold text-lg hidden sm:flex">
                                                {{ substr($a->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800">{{ $a->name }}</p>
                                                <p class="text-sm text-gray-500 font-mono mt-0.5">{{ $a->nip }}</p>
                                                <p class="text-xs text-gray-500 mt-1"><i class="bi bi-telephone-fill mr-1 text-slate-400"></i> {{ $a->telepon ?? 'Belum diisi' }}</p>
                                                <p class="text-xs text-gray-500 md:hidden mt-1 px-2 py-0.5 bg-slate-100 rounded inline-block">{{ $a->jabatan }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 text-sm text-gray-700 hidden md:table-cell">{{ $a->jabatan }}</td>
                                    <td class="p-4 text-sm hidden lg:table-cell">
                                        <span class="font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md">{{ $a->masa_kerja_total }}</span>
                                    </td>
                                    <td class="p-4 hidden lg:table-cell">
                                        <div class="flex flex-col gap-1.5">
                                            @if($a->atasan1)
                                                @php $at1 = $dataAtasan->where('nip', $a->atasan1)->first(); @endphp
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200" title="NIP: {{ $a->atasan1 }}">
                                                    <i class="bi bi-1-circle-fill text-slate-400 mr-1.5"></i> {{ $at1 ? $at1->nama : $a->atasan1 }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400 italic">-</span>
                                            @endif
                                            
                                            @if($a->atasan2)
                                                @php $at2 = $dataAtasan->where('nip', $a->atasan2)->first(); @endphp
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200" title="NIP: {{ $a->atasan2 }}">
                                                    <i class="bi bi-2-circle-fill text-slate-400 mr-1.5"></i> {{ $at2 ? $at2->nama : $a->atasan2 }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-4 text-center">
                                        @php $sisa = ($a->sisa_cuti_tahun_ini + $a->sisa_cuti_tahun_lalu) - $a->cuti_diambil; @endphp
                                        <div class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $sisa > 0 ? 'bg-hijau-100 text-hijau-700' : 'bg-red-100 text-red-700' }} font-bold text-sm">
                                            {{ $sisa }}
                                        </div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <button type="button" onclick="editAsn({{ $a }})" title="Edit Data" class="text-slate-400 hover:text-blue-600 p-2 rounded-lg hover:bg-blue-50 transition-colors"><i class="bi bi-pencil-square text-lg"></i></button>
                                            <a href="{{ url('/admin/asn/'.$a->id.'/hapus') }}" onclick="return confirm('Yakin ingin menghapus ASN {{ $a->name }}?')" title="Hapus Akun" class="text-slate-400 hover:text-red-600 p-2 rounded-lg hover:bg-red-50 transition-colors"><i class="bi bi-trash3 text-lg"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-10">
                                        <div class="text-gray-400 mb-2"><i class="bi bi-folder-x text-4xl"></i></div>
                                        <p class="text-gray-500">Belum ada data ASN.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div id="modalAsn" class="hidden modal-backdrop">
        <div class="modal-content flex flex-col h-[90vh] md:h-auto md:max-h-[90vh]">
            
            <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-4 shrink-0">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-800">Tambah ASN Baru</h3>
                <button id="closeModalAsnButton" class="text-gray-400 hover:text-gray-600 text-2xl w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100">&times;</button>
            </div>
            
            <div class="overflow-y-auto custom-scrollbar flex-1 pr-2">
                <form id="formAsn" action="{{ url('/admin/asn') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <h4 class="text-xs font-bold text-hijau-600 uppercase tracking-wider mb-3 flex items-center"><i class="bi bi-person-badge mr-2"></i> Data Profil</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block text-sm text-gray-600 mb-1">Nama Lengkap <span class="text-red-500">*</span></label><input type="text" name="name" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-hijau-400" required></div>
                            <div><label class="block text-sm text-gray-600 mb-1">NIP <span class="text-red-500">*</span></label><input type="text" name="nip" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-hijau-400" pattern="[0-9]*" required></div>
                            <div class="md:col-span-2"><label class="block text-sm text-gray-600 mb-1">Nomor Telepon</label><input type="tel" name="telepon" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-hijau-400" placeholder="Contoh: 08123456789"></div>
                        </div>
                    </div>

                    <hr class="border-slate-200 my-5">
                    
                    <div class="mb-6">
                        <h4 class="text-xs font-bold text-hijau-600 uppercase tracking-wider mb-3 flex items-center"><i class="bi bi-briefcase mr-2"></i> Data Kepegawaian</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block text-sm text-gray-600 mb-1">Jabatan <span class="text-red-500">*</span></label><input type="text" name="jabatan" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-hijau-400" required></div>
                            <div><label class="block text-sm text-gray-600 mb-1">Pangkat / Golongan</label><input type="text" name="pangkat_gol" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-hijau-400" placeholder="Contoh: Penata Muda / III a"></div>
                            <div><label class="block text-sm text-gray-600 mb-1">TMT Pangkat</label><input type="date" name="tmt" id="tmt_input" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-hijau-400"></div>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div><label class="block text-sm text-gray-600 mb-1">Masa Kerja (Thn)</label><input type="number" name="masa_kerja_tahun" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-hijau-400" value="0"></div>
                                <div><label class="block text-sm text-gray-600 mb-1">Bulan</label><input type="number" name="masa_kerja_bulan" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-hijau-400" value="0"></div>
                            </div>
                            
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Atasan Langsung 1</label>
                                <select name="atasan1" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-hijau-400">
                                    <option value="">-- Pilih Atasan --</option>
                                    @foreach($dataAtasan as $atasan)
                                        <option value="{{ $atasan->nip }}">{{ $atasan->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Atasan Langsung 2 <span class="text-xs text-gray-400">(Opsional)</span></label>
                                <select name="atasan2" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-hijau-400">
                                    <option value="">-- Pilih Atasan --</option>
                                    @foreach($dataAtasan as $atasan)
                                        <option value="{{ $atasan->nip }}">{{ $atasan->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-200 my-5">

                    <div>
                        <h4 class="text-xs font-bold text-hijau-600 uppercase tracking-wider mb-3 flex items-center"><i class="bi bi-shield-lock mr-2"></i> Akun & Saldo Cuti</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block text-sm text-gray-600 mb-1">Cuti Tahun Ini</label><input type="number" name="sisa_cuti_tahun_ini" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-hijau-400" value="12" required></div>
                            <div><label class="block text-sm text-gray-600 mb-1">Sisa Cuti Tahun Lalu</label><input type="number" name="sisa_cuti_tahun_lalu" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-hijau-400" value="0" required></div>
                            <div><label class="block text-sm text-gray-600 mb-1">Username Login <span class="text-red-500">*</span></label><input type="text" name="username" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-hijau-400" required></div>
                            <div><label class="block text-sm text-gray-600 mb-1">Password Login</label><input type="password" name="password" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-hijau-400" placeholder="Kosongkan untuk default: password123"></div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="pt-4 border-t border-slate-200 mt-4 shrink-0 flex justify-end gap-3">
                <button id="batalAsn" type="button" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 font-semibold transition-colors">Batal</button>
                <button type="submit" form="formAsn" class="px-5 py-2.5 bg-hijau-500 text-white rounded-lg hover:bg-hijau-600 font-semibold shadow-sm transition-colors">Simpan Data</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            function toggleMenu() {
                sidebar.classList.toggle('-translate-x-full');
                sidebarOverlay.classList.toggle('hidden');
            }

            if (menuToggle) menuToggle.addEventListener('click', toggleMenu);
            if (sidebarOverlay) sidebarOverlay.addEventListener('click', toggleMenu);

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

            const formAsn = document.getElementById('formAsn');
            const modalTitle = document.getElementById('modalTitle');

            btnTambahAsn.addEventListener('click', () => {
                if (modalTitle) modalTitle.textContent = 'Tambah ASN Baru';
                formAsn.action = `{{ url('/admin/asn') }}`;
                formAsn.reset();
                
                formAsn.elements['password'].required = false; 
                formAsn.elements['password'].placeholder = 'Kosongkan untuk default: password123';
                
                openModal();
            });

            window.editAsn = function(data) {
                modalTitle.textContent = 'Edit Data ASN';
                formAsn.action = `{{ url('/admin/asn') }}/${data.id}/update`;
                
                formAsn.elements['name'].value = data.name;
                formAsn.elements['nip'].value = data.nip;
                formAsn.elements['jabatan'].value = data.jabatan;
                formAsn.elements['pangkat_gol'].value = data.pangkat_gol || '';
                formAsn.elements['telepon'].value = data.telepon || '';
                formAsn.elements['masa_kerja_tahun'].value = data.masa_kerja_tahun;
                formAsn.elements['masa_kerja_bulan'].value = data.masa_kerja_bulan;
                formAsn.elements['sisa_cuti_tahun_ini'].value = data.sisa_cuti_tahun_ini;
                formAsn.elements['sisa_cuti_tahun_lalu'].value = data.sisa_cuti_tahun_lalu;
                formAsn.elements['username'].value = data.username;
                formAsn.elements['atasan1'].value = data.atasan1 || '';
                formAsn.elements['atasan2'].value = data.atasan2 || '';
                
                formAsn.elements['password'].required = false;
                formAsn.elements['password'].value = ''; 
                formAsn.elements['password'].placeholder = 'Kosongkan jika tidak ubah password';

                formAsn.elements['tmt'].value = data.tmt ? data.tmt.split(' ')[0] : '';
                
                openModal();
            };

            closeModalAsnButton.addEventListener('click', closeModal);
            batalAsn.addEventListener('click', closeModal);

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

    @include('components.notifikasi')
</body>
</html>