<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Atasan - Admin Panel</title>
    
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
                        'hijau': { '500': '#22c55e', '600': '#16a34a' },
                        'slate': { '50': '#f8fafc', '100': '#f1f5f9' }
                    }
                }
            }
        }
    </script>
    <style>
        .custom-modal-backdrop {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.5); display: flex;
            justify-content: center; align-items: center; z-index: 9999;
            opacity: 0; visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .custom-modal-backdrop.show { opacity: 1; visibility: visible; }
        .custom-modal-content {
            background-color: #fff; padding: 2rem; border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            width: 90%; max-width: 400px; transform: translateY(-20px);
            transition: transform 0.3s ease;
        }
        .custom-modal-backdrop.show .custom-modal-content { transform: translateY(0); }
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
                <a href="{{ url('/manajemen_asn') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-people-fill mr-3"></i> Manajemen ASN</a>
                <a href="{{ url('/manajemen_cuti') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-calendar-range-fill mr-3"></i> Manajemen Jenis Cuti</a>
                <a href="{{ url('/manajemen_atasan') }}" class="flex items-center px-4 py-2.5 text-white bg-hijau-500 rounded-lg font-semibold"><i class="bi bi-person-badge-fill mr-3"></i> Manajemen Atasan</a>
                <a href="{{ url('/manajemen_libur') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-calendar-x-fill mr-3"></i> Manajemen Hari Libur</a>
                <a href="{{ url('/log_aktivitas') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-file-earmark-text-fill mr-3"></i> Log Aktivitas</a>
            </nav>

            <div class="p-4 mt-auto">
                <a href="{{ url('/') }}" class="flex items-center justify-center w-full px-4 py-2.5 text-red-500 bg-red-50 hover:bg-red-100 rounded-lg font-semibold"><i class="bi bi-box-arrow-right mr-3"></i> Logout</a>
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
                <h1 class="text-3xl font-bold text-gray-800">Manajemen Data Atasan</h1>
                <p class="text-gray-500 mt-1">Tambah, edit, dan kelola data atasan untuk ASN.</p>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-between mb-6 gap-4">
                <div class="relative w-full md:w-1/3">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchInput" placeholder="Cari atasan..." class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400">
                </div>
                <button id="btnTambahAtasan" class="w-full md:w-auto bg-hijau-500 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:bg-hijau-600 flex items-center justify-center space-x-2">
                    <i class="bi bi-plus-circle-fill"></i><span>Tambah Data Atasan</span>
                </button>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="p-4 font-semibold text-gray-600 text-sm">Nama Lengkap</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm hidden md:table-cell">NIP</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm hidden lg:table-cell">Jabatan</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="atasanTableBody" class="divide-y divide-slate-100"></tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Add/Edit Atasan Modal -->
    <div id="modalAtasan" class="hidden custom-modal-backdrop">
        <div class="custom-modal-content">
            <div class="flex items-center justify-between mb-6"><h3 id="modalAtasanTitle" class="text-2xl font-bold text-gray-800"></h3><button id="closeModalAtasanButton" class="text-gray-400 hover:text-gray-600 text-3xl">&times;</button></div>
            <form id="formAtasan">
                <input type="hidden" id="atasanId">
                <div class="space-y-6">
                    <div><label for="atasan_nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label><input type="text" id="atasan_nama" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" required></div>
                    <div><label for="atasan_nip" class="block text-sm font-medium text-gray-700 mb-1">NIP</label><input type="text" id="atasan_nip" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" pattern="[0-9]*" required><p id="atasanNipError" class="text-red-500 text-sm mt-1 hidden">NIP ini sudah terdaftar.</p></div>
                    <div><label for="atasan_jabatan" class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label><input type="text" id="atasan_jabatan" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" required></div>
                </div>
                <div class="flex justify-end gap-4 mt-8"><button id="batalAtasan" type="button" class="px-6 py-3 bg-slate-200 text-gray-800 rounded-lg hover:bg-slate-300 font-semibold">Batal</button><button id="simpanAtasan" type="submit" class="px-6 py-3 bg-hijau-500 text-white rounded-lg hover:bg-hijau-600 font-semibold">Simpan</button></div>
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
            const modalAtasan = document.getElementById('modalAtasan');
            const closeModalAtasanButton = document.getElementById('closeModalAtasanButton'); 
            const batalAtasan = document.getElementById('batalAtasan');
            const btnTambahAtasan = document.getElementById('btnTambahAtasan');
            const modalAtasanTitle = document.getElementById('modalAtasanTitle');
            const formAtasan = document.getElementById('formAtasan');
            const atasanTableBody = document.getElementById('atasanTableBody');
            const searchInput = document.getElementById('searchInput');
            const atasanNipInput = document.getElementById('atasan_nip');
            const atasanNipError = document.getElementById('atasanNipError');
            let currentEditId = null;

            let dataAtasan = JSON.parse(localStorage.getItem('dataAtasan')) || [];
            
            function saveData() {
                localStorage.setItem('dataAtasan', JSON.stringify(dataAtasan));
            }

            function renderTable(data) {
                atasanTableBody.innerHTML = '';
                if (data.length === 0) {
                    atasanTableBody.innerHTML = `<tr><td colspan="4" class="text-center p-4 text-gray-500">Tidak ada data ditemukan.</td></tr>`;
                    return;
                }
                data.forEach(atasan => {
                    const row = `
                        <tr data-id="${atasan.id}">
                            <td class="p-4">
                                <p class="font-semibold text-gray-800">${atasan.nama}</p>
                                <p class="text-xs text-gray-500 md:hidden">NIP: ${atasan.nip}</p>
                            </td>
                            <td class="p-4 text-sm text-gray-600 hidden md:table-cell">${atasan.nip}</td>
                            <td class="p-4 text-sm text-gray-600 hidden lg:table-cell">${atasan.jabatan}</td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button data-id="${atasan.id}" title="Edit Data" class="btn-edit text-gray-500 hover:text-blue-600 p-2 rounded-md hover:bg-blue-100"><i class="bi bi-pencil-fill"></i></button>
                                    <button data-id="${atasan.id}" title="Hapus Data" class="btn-hapus text-gray-500 hover:text-red-600 p-2 rounded-md hover:bg-red-100"><i class="bi bi-trash-fill"></i></button>
                                </div>
                            </td>
                        </tr>
                    `;
                    atasanTableBody.insertAdjacentHTML('beforeend', row);
                });
            }

            function openModalAtasan(title, data = {}) { 
                modalAtasanTitle.textContent = title;
                currentEditId = data.id || null;
                document.getElementById('atasan_nama').value = data.nama || '';
                document.getElementById('atasan_nip').value = data.nip || '';
                document.getElementById('atasan_jabatan').value = data.jabatan || '';
                atasanNipError.classList.add('hidden'); 
                modalAtasan.classList.remove('hidden');
                setTimeout(() => modalAtasan.classList.add('show'), 10);
            }

            function closeModalAtasan() { 
                modalAtasan.classList.remove('show');
                setTimeout(() => {
                    modalAtasan.classList.add('hidden');
                    formAtasan.reset();
                    currentEditId = null;
                }, 300);
            }
            
            btnTambahAtasan.addEventListener('click', () => openModalAtasan('Tambah Data Atasan'));

            atasanTableBody.addEventListener('click', function(e) {
                const button = e.target.closest('button');
                if (!button) return;

                const id = parseInt(button.closest('tr').dataset.id);
                const atasan = dataAtasan.find(item => item.id === id);

                if (button.classList.contains('btn-edit')) {
                    openModalAtasan('Edit Data Atasan', atasan);
                }

                if (button.classList.contains('btn-hapus')) {
                    const dataAsn = JSON.parse(localStorage.getItem('dataAsn')) || [];
                    const isUsed = dataAsn.some(asn => asn.atasan1 === atasan.nip || asn.atasan2 === atasan.nip);
                    if (isUsed) {
                        showAlert(`Tidak dapat menghapus ${atasan.nama} karena datanya masih digunakan sebagai atasan oleh ASN lain.`);
                        return;
                    }

                    showConfirm(`Anda yakin ingin menghapus data atasan ${atasan.nama}?`, (result) => {
                        if (result) {
                            dataAtasan = dataAtasan.filter(item => item.id !== id);
                            saveData();
                            renderTable(dataAtasan);
                            showAlert('Data atasan berhasil dihapus!');
                            addActivityLog(`Menghapus data atasan: ${atasan.nama}`);
                        }
                    });
                }
            });

            closeModalAtasanButton.addEventListener('click', closeModalAtasan); 
            batalAtasan.addEventListener('click', closeModalAtasan);
            
            formAtasan.addEventListener('submit', function(e) {
                e.preventDefault();
                const newNip = atasanNipInput.value;
                const isNipDuplicate = dataAtasan.some(item => item.nip === newNip && item.id !== currentEditId);

                if (isNipDuplicate) {
                    atasanNipError.classList.remove('hidden');
                    return; 
                }
                atasanNipError.classList.add('hidden');

                const formData = {
                    nama: document.getElementById('atasan_nama').value,
                    nip: newNip, 
                    jabatan: document.getElementById('atasan_jabatan').value,
                };

                if (currentEditId) {
                    const index = dataAtasan.findIndex(item => item.id === currentEditId);
                    dataAtasan[index] = { ...dataAtasan[index], ...formData };
                    showAlert('Data atasan berhasil diperbarui!');
                    addActivityLog(`Memperbarui data atasan: ${formData.nama}`);
                } else {
                    formData.id = dataAtasan.length > 0 ? Math.max(...dataAtasan.map(item => item.id)) + 1 : 1;
                    dataAtasan.push(formData);
                    showAlert('Data atasan baru berhasil ditambahkan!');
                    addActivityLog(`Menambahkan data atasan baru: ${formData.nama}`);
                }
                
                saveData();
                renderTable(dataAtasan);
                closeModalAtasan();
            });

            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const filteredData = dataAtasan.filter(atasan => 
                    atasan.nama.toLowerCase().includes(searchTerm) || 
                    atasan.nip.toLowerCase().includes(searchTerm)
                );
                renderTable(filteredData);
            });

            renderTable(dataAtasan);
        });
    </script>
</body>
</html>
