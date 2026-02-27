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
        #modalAsn .modal-content { max-width: 672px; } /* max-w-2xl */
        .modal-backdrop.show .modal-content, .custom-modal-backdrop.show .custom-modal-content { transform: translateY(0); }
    </style>
</head>
<body class="font-sans bg-slate-50">
    <!-- Overlay for mobile menu -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

    <div class="relative min-h-screen lg:flex">
        <!-- Sidebar -->
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
            <!-- Mobile Header -->
            <header class="lg:hidden flex items-center justify-between mb-8">
                 <div class="flex items-center space-x-3">
                    <div class="bg-hijau-500 text-white p-2.5 rounded-lg shadow-sm"><i class="bi bi-shield-check text-xl"></i></div>
                    <div><h1 class="text-lg font-bold text-gray-800">ADMIN PANEL</h1></div>
                </div>
                <button id="menu-toggle" class="text-2xl text-gray-700 p-2">
                    <i class="bi bi-list"></i>
                </button>
            </header>

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Manajemen Akun ASN</h1>
                <p class="text-gray-500 mt-1">Tambah, edit, dan kelola akun untuk Aparatur Sipil Negara.</p>
            </div>

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
                        <tbody id="asnTableBody" class="divide-y divide-slate-100"></tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Add/Edit ASN Modal -->
    <div id="modalAsn" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center p-4 z-50 modal-backdrop">
        <div class="bg-white rounded-2xl shadow-xl w-full p-6 md:p-8 modal-content overflow-y-auto max-h-screen">
            <div class="flex items-center justify-between mb-6"><h3 id="modalAsnTitle" class="text-2xl font-bold text-gray-800"></h3><button id="closeModalAsnButton" class="text-gray-400 hover:text-gray-600 text-3xl">&times;</button></div>
            <form id="formAsn">
                <input type="hidden" id="asnId">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div><label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label><input type="text" id="nama_lengkap" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" required></div>
                    <div><label for="nip" class="block text-sm font-medium text-gray-700 mb-1">NIP</label><input type="text" id="nip" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" pattern="[0-9]*" required><p id="nipError" class="text-red-500 text-sm mt-1 hidden">NIP ini sudah terdaftar.</p></div>
                    <div><label for="jabatan" class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label><input type="text" id="jabatan" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" required></div>
                    <div><label for="pangkat_gol" class="block text-sm font-medium text-gray-700 mb-1">Pangkat / Golongan</label><input type="text" id="pangkat_gol" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" placeholder="Contoh: Penata Muda / III a"></div>
                    <div><label for="telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label><input type="tel" id="telepon" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" placeholder="Contoh: 08123456789"></div>
                    <div><label for="tmt_pangkat" class="block text-sm font-medium text-gray-700 mb-1">TMT Pangkat</label><input type="date" id="tmt_pangkat" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400"></div>
                    <div class="md:col-span-2 grid grid-cols-2 gap-4">
                        <div><label for="masa_kerja_awal_tahun" class="block text-sm font-medium text-gray-700 mb-1">Masa Kerja Awal (Tahun)</label><input type="number" id="masa_kerja_awal_tahun" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" value="0"></div>
                        <div><label for="masa_kerja_awal_bulan" class="block text-sm font-medium text-gray-700 mb-1">Masa Kerja Awal (Bulan)</label><input type="number" id="masa_kerja_awal_bulan" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" value="0"></div>
                    </div>
                    <div><label for="atasan1" class="block text-sm font-medium text-gray-700 mb-1">Atasan Langsung 1</label><select id="atasan1" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" required><option value="">Pilih Atasan</option></select></div>
                    <div><label for="atasan2" class="block text-sm font-medium text-gray-700 mb-1">Atasan Langsung 2 (Opsional)</label><select id="atasan2" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400"><option value="">Pilih Atasan</option></select></div>
                    <div><label for="sisa_cuti_tahun_ini" class="block text-sm font-medium text-gray-700 mb-1">Cuti Tahun Ini</label><input type="number" id="sisa_cuti_tahun_ini" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" required></div>
                    <div><label for="sisa_cuti_tahun_lalu" class="block text-sm font-medium text-gray-700 mb-1">Sisa Cuti Tahun Lalu</label><input type="number" id="sisa_cuti_tahun_lalu" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" required></div>
                    <div><label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label><input type="text" id="username" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" required></div>
                    <div><label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label><input type="password" id="password" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" placeholder="Kosongkan jika tidak ingin mengubah"></div>
                </div>
                <div class="flex justify-end gap-4 mt-8"><button id="batalAsn" type="button" class="px-6 py-3 bg-slate-200 text-gray-800 rounded-lg hover:bg-slate-300 font-semibold">Batal</button><button id="simpanAsn" type="submit" class="px-6 py-3 bg-hijau-500 text-white rounded-lg hover:bg-hijau-600 font-semibold">Simpan</button></div>
            </form>
        </div>
    </div>
    
    <div id="customAlertModal" class="custom-modal-backdrop">
        <div class="custom-modal-content"><h4 id="customAlertTitle" class="text-lg font-bold text-gray-800 mb-4"></h4><p id="customAlertMessage" class="text-gray-700 mb-6"></p><div class="flex justify-end space-x-3"><button id="customAlertCancel" class="px-4 py-2 bg-slate-200 text-gray-700 rounded-lg hover:bg-slate-300 hidden"></button><button id="customAlertOK" class="px-4 py-2 bg-hijau-500 text-white rounded-lg hover:bg-hijau-600"></button></div></div>
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

            // --- Existing Logic ---
            const modalAsn = document.getElementById('modalAsn');
            const closeModalAsnButton = document.getElementById('closeModalAsnButton'); 
            const batalAsn = document.getElementById('batalAsn');
            const btnTambahAsn = document.getElementById('btnTambahAsn');
            const modalAsnTitle = document.getElementById('modalAsnTitle');
            const formAsn = document.getElementById('formAsn');
            const asnTableBody = document.getElementById('asnTableBody');
            const searchInput = document.getElementById('searchInput');
            const nipInput = document.getElementById('nip'); 
            const nipError = document.getElementById('nipError');
            const atasan1Select = document.getElementById('atasan1');
            const atasan2Select = document.getElementById('atasan2');
            let currentEditId = null;

            let dataAsn = JSON.parse(localStorage.getItem('dataAsn')) || [];
            let dataAtasan = JSON.parse(localStorage.getItem('dataAtasan')) || [];
            
            function saveData() {
                localStorage.setItem('dataAsn', JSON.stringify(dataAsn));
            }

            function populateAtasanOptions() {
                atasan1Select.innerHTML = '<option value="">Pilih Atasan 1</option>';
                atasan2Select.innerHTML = '<option value="">Pilih Atasan 2 (Opsional)</option>';
                dataAtasan.forEach(atasan => {
                    const option = `<option value="${atasan.nip}">${atasan.nama} (${atasan.jabatan})</option>`;
                    atasan1Select.innerHTML += option;
                    atasan2Select.innerHTML += option;
                });
            }

            function hitungMasaKerja(tmtString, masaKerjaAwal) {
                if (!tmtString) return 'N/A';
                const tmt = new Date(tmtString);
                const targetDate = new Date('2025-08-01');

                if (isNaN(tmt.getTime())) return 'TMT tidak valid';

                const initialYears = masaKerjaAwal ? parseInt(masaKerjaAwal.tahun, 10) || 0 : 0;
                const initialMonths = masaKerjaAwal ? parseInt(masaKerjaAwal.bulan, 10) || 0 : 0;

                // Calculate total months difference, ignoring day part
                let diffTotalMonths = (targetDate.getFullYear() - tmt.getFullYear()) * 12 + (targetDate.getMonth() - tmt.getMonth());

                const initialTotalMonths = (initialYears * 12) + initialMonths;
                
                // Add 1 month to align with Excel's DATEDIF behavior for this specific context.
                const finalTotalMonths = initialTotalMonths + diffTotalMonths + 1;

                const finalYears = Math.floor(finalTotalMonths / 12);
                const finalMonths = finalTotalMonths % 12;

                return `${finalYears} thn, ${finalMonths} bln`;
            }

            function renderTable(data) {
                asnTableBody.innerHTML = '';
                if (data.length === 0) {
                    asnTableBody.innerHTML = `<tr><td colspan="6" class="text-center p-4 text-gray-500">Tidak ada data ditemukan.</td></tr>`;
                    return;
                }
                data.forEach(asn => {
                    const sisaCuti = asn.sisaCuti || { tahunIni: 12, tahunLalu: 0, diambil: 0 };
                    const totalSisaCuti = (sisaCuti.tahunIni + sisaCuti.tahunLalu) - sisaCuti.diambil;
                    
                    const atasan1 = dataAtasan.find(a => a.nip === asn.atasan1);
                    const atasan2 = dataAtasan.find(a => a.nip === asn.atasan2);
                    const masaKerja = hitungMasaKerja(asn.tmtPangkat, asn.masaKerjaAwal);

                    const row = `
                        <tr data-id="${asn.id}">
                            <td class="p-4">
                                <p class="font-semibold text-gray-800">${asn.nama}</p>
                                <p class="text-xs text-gray-500">${asn.pangkat_gol || 'Pangkat/Gol belum diisi'}</p>
                                <p class="text-xs text-gray-500">NIP: ${asn.nip}</p>
                                <p class="text-xs text-gray-500"><i class="bi bi-telephone-fill"></i> ${asn.telepon || '-'}</p>
                                <p class="text-sm text-gray-600 md:hidden mt-1">${asn.jabatan}</p>
                            </td>
                            <td class="p-4 text-sm text-gray-600 hidden md:table-cell">${asn.jabatan}</td>
                            <td class="p-4 text-sm text-gray-600 hidden lg:table-cell">${masaKerja}</td>
                            <td class="p-4 text-sm text-gray-600 hidden lg:table-cell">
                                ${atasan1 ? `1. ${atasan1.nama}` : '-'} <br>
                                ${atasan2 ? `2. ${atasan2.nama}` : ''}
                            </td>
                            <td class="p-4 text-sm text-gray-600 text-center">${totalSisaCuti}</td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button data-id="${asn.id}" title="Reset Password" class="btn-reset-pass text-gray-500 hover:text-yellow-600 p-2 rounded-md hover:bg-yellow-100"><i class="bi bi-key-fill"></i></button>
                                    <button data-id="${asn.id}" title="Edit Data" class="btn-edit text-gray-500 hover:text-blue-600 p-2 rounded-md hover:bg-blue-100"><i class="bi bi-pencil-fill"></i></button>
                                    <button data-id="${asn.id}" title="Hapus Akun" class="btn-hapus text-gray-500 hover:text-red-600 p-2 rounded-md hover:bg-red-100"><i class="bi bi-trash-fill"></i></button>
                                </div>
                            </td>
                        </tr>
                    `;
                    asnTableBody.insertAdjacentHTML('beforeend', row);
                });
            }

            function openModalAsn(title, data = {}) { 
                modalAsnTitle.textContent = title;
                currentEditId = data.id || null;
                
                document.getElementById('nama_lengkap').value = data.nama || '';
                document.getElementById('nip').value = data.nip || '';
                document.getElementById('jabatan').value = data.jabatan || '';
                document.getElementById('pangkat_gol').value = data.pangkat_gol || '';
                document.getElementById('telepon').value = data.telepon || '';
                document.getElementById('tmt_pangkat').value = data.tmtPangkat || '';
                document.getElementById('masa_kerja_awal_tahun').value = data.masaKerjaAwal ? data.masaKerjaAwal.tahun : 0;
                document.getElementById('masa_kerja_awal_bulan').value = data.masaKerjaAwal ? data.masaKerjaAwal.bulan : 0;
                atasan1Select.value = data.atasan1 || '';
                atasan2Select.value = data.atasan2 || '';
                document.getElementById('sisa_cuti_tahun_ini').value = data.sisaCuti ? data.sisaCuti.tahunIni : 12;
                document.getElementById('sisa_cuti_tahun_lalu').value = data.sisaCuti ? data.sisaCuti.tahunLalu : 0;
                document.getElementById('username').value = data.username || data.nip || '';
                document.getElementById('password').value = '';
                
                nipError.classList.add('hidden'); 
                modalAsn.classList.remove('hidden');
                setTimeout(() => modalAsn.classList.add('show'), 10);
            }

            function closeModalAsn() { 
                modalAsn.classList.remove('show');
                setTimeout(() => {
                    modalAsn.classList.add('hidden');
                    formAsn.reset();
                    currentEditId = null;
                }, 300);
            }
            
            btnTambahAsn.addEventListener('click', () => openModalAsn('Tambah ASN Baru'));

            asnTableBody.addEventListener('click', function(e) {
                const button = e.target.closest('button');
                if (!button) return;
                
                const id = parseInt(button.closest('tr').dataset.id);
                const asn = dataAsn.find(item => item.id === id);

                if (button.classList.contains('btn-edit')) {
                    openModalAsn('Edit Data ASN', asn);
                } else if (button.classList.contains('btn-hapus')) {
                    showConfirm(`Anda yakin ingin menghapus data ASN ${asn.nama}? Semua riwayat cuti yang terkait juga akan dihapus.`, (result) => {
                        if (result) {
                            dataAsn = dataAsn.filter(item => item.id !== id);
                            saveData();
                            let pengajuanCuti = JSON.parse(localStorage.getItem('pengajuanCuti')) || [];
                            pengajuanCuti = pengajuanCuti.filter(cuti => cuti.asnId !== id);
                            localStorage.setItem('pengajuanCuti', JSON.stringify(pengajuanCuti));
                            renderTable(dataAsn);
                            showAlert('Data ASN dan riwayat cuti terkait berhasil dihapus!');
                            addActivityLog(`Menghapus data ASN: ${asn.nama} beserta riwayat cutinya.`);
                        }
                    });
                } else if (button.classList.contains('btn-reset-pass')) {
                    const newPassword = 'password123';
                    showConfirm(`Anda yakin ingin mereset password untuk ${asn.nama}? Password baru akan menjadi: 'password123'`, (result) => {
                        if (result) {
                            const index = dataAsn.findIndex(item => item.id === id);
                            dataAsn[index].password = newPassword;
                            saveData();
                            showAlert(`Password untuk ${asn.nama} berhasil direset menjadi 'password123'.`);
                            addActivityLog(`Mereset password ASN: ${asn.nama}`);
                        }
                    });
                }
            });

            closeModalAsnButton.addEventListener('click', closeModalAsn); 
            batalAsn.addEventListener('click', closeModalAsn);
            
            formAsn.addEventListener('submit', function(e) {
                e.preventDefault();
                const newNip = nipInput.value;
                const isNipDuplicate = dataAsn.some(item => item.nip === newNip && item.id !== currentEditId);

                if (isNipDuplicate) {
                    nipError.classList.remove('hidden');
                    return; 
                }
                nipError.classList.add('hidden');

                const formData = {
                    nama: document.getElementById('nama_lengkap').value,
                    nip: newNip, 
                    jabatan: document.getElementById('jabatan').value,
                    pangkat_gol: document.getElementById('pangkat_gol').value,
                    telepon: document.getElementById('telepon').value,
                    tmtPangkat: document.getElementById('tmt_pangkat').value,
                    masaKerjaAwal: {
                        tahun: document.getElementById('masa_kerja_awal_tahun').value,
                        bulan: document.getElementById('masa_kerja_awal_bulan').value
                    },
                    atasan1: atasan1Select.value,
                    atasan2: atasan2Select.value,
                    sisaCuti: {
                        tahunIni: parseInt(document.getElementById('sisa_cuti_tahun_ini').value),
                        tahunLalu: parseInt(document.getElementById('sisa_cuti_tahun_lalu').value),
                        diambil: currentEditId ? dataAsn.find(item => item.id === currentEditId).sisaCuti.diambil : 0
                    },
                    username: document.getElementById('username').value,
                };
                const password = document.getElementById('password').value;

                if (currentEditId) {
                    const index = dataAsn.findIndex(item => item.id === currentEditId);
                    dataAsn[index] = { ...dataAsn[index], ...formData };
                    if (password) dataAsn[index].password = password;
                    showAlert('Data ASN berhasil diperbarui!');
                    addActivityLog(`Memperbarui data ASN: ${formData.nama}`);
                } else {
                    formData.id = dataAsn.length > 0 ? Math.max(...dataAsn.map(item => item.id)) + 1 : 1;
                    formData.status = 'Aktif';
                    formData.password = password || 'password123'; 
                    dataAsn.push(formData);
                    showAlert('ASN baru berhasil ditambahkan!');
                    addActivityLog(`Menambahkan ASN baru: ${formData.nama}`);
                }
                
                saveData();
                renderTable(dataAsn);
                closeModalAsn();
            });

            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const filteredData = dataAsn.filter(asn => 
                    asn.nama.toLowerCase().includes(searchTerm) || 
                    asn.nip.toLowerCase().includes(searchTerm)
                );
                renderTable(filteredData);
            });

            // Initial Render
            populateAtasanOptions();
            renderTable(dataAsn);
        });
    </script>
</body>
</html>
