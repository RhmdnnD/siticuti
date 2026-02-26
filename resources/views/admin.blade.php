<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Sistem Cuti Online</title>
    
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
            justify-content: center; align-items: center;
            opacity: 0; visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .modal-backdrop.show, .custom-modal-backdrop.show { opacity: 1; visibility: visible; }
        .modal-content, .custom-modal-content {
            background-color: #fff; padding: 2rem; border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            width: 90%; max-width: 500px; transform: translateY(-20px);
            transition: transform 0.3s ease;
        }
        .modal-backdrop.show .modal-content, .custom-modal-backdrop.show .custom-modal-content { transform: translateY(0); }
        
        #modalDetail { z-index: 50; }
        #reportModal { z-index: 10000; }
        #calendarModal { z-index: 10000; }
        #customAlertModal { z-index: 10001; }

        .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.5rem; }
        .calendar-day { padding: 0.5rem; border-radius: 0.5rem; min-height: 80px; display: flex; flex-direction: column; align-items: center; text-align: center; font-size: 0.875rem; overflow: hidden; word-wrap: break-word; }
        .calendar-day.current-month { background-color: #f1f5f9; }
        .calendar-day.other-month { background-color: #e2e8f0; color: #94a3b8; }
        .calendar-day.has-leave { background-color: #dcfce7; border: 1px solid #4ade80; cursor: pointer; }
        .calendar-day .day-number { font-weight: 600; margin-bottom: 0.25rem; }
        .calendar-day .leave-names { font-size: 0.75rem; color: #16a34a; line-height: 1.2; }
        .calendar-day.today { background-color: #86efac; border: 2px solid #16a34a; color: #15803d; }
        #calendarModal .custom-modal-content { max-width: 700px; width: 95%; max-height: 90vh; overflow-y: auto; }
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
                    <div>
                        <h1 class="text-lg font-bold text-gray-800">ADMIN PANEL</h1>
                        <p class="text-xs text-gray-500">SITI</p>
                    </div>
                </div>
            </div>
            
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ url('/admin') }}" class="flex items-center px-4 py-2.5 text-white bg-hijau-500 rounded-lg font-semibold"><i class="bi bi-grid-1x2-fill mr-3"></i> Dashboard</a>
                <a href="{{ url('/manajemen_asn') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-people-fill mr-3"></i> Manajemen ASN</a>
                <a href="{{ url('/manajemen_cuti') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-calendar-range-fill mr-3"></i> Manajemen Jenis Cuti</a>
                <a href="{{ url('/manajemen_atasan') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-person-badge-fill mr-3"></i> Manajemen Atasan</a>
                <a href="{{ url('/manajemen_libur') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-calendar-x-fill mr-3"></i> Manajemen Hari Libur</a>
                <a href="{{ url('/log_aktivitas') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-file-earmark-text-fill mr-3"></i> Log Aktivitas</a>
            </nav>

            <div class="p-4 mt-auto">
                <a href="{{ url('/') }}" id="logoutButton" class="flex items-center justify-center w-full px-4 py-2.5 text-red-500 bg-red-50 hover:bg-red-100 rounded-lg font-semibold"><i class="bi bi-box-arrow-right mr-3"></i> Logout</a>
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

             <div id="reset-notif" class="hidden bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-lg">
                <p class="font-bold">Reset Cuti Tahunan Berhasil</p>
                <p>Sisa cuti untuk semua ASN telah diperbarui untuk tahun ini.</p>
            </div>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Selamat Datang, Admin!</h1>
                    <p class="text-gray-500 mt-1">Berikut adalah ringkasan pengajuan cuti terbaru.</p>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 mt-4 md:mt-0">
                    <button id="openReportModalBtn" class="bg-white border border-slate-200 text-gray-600 font-semibold px-4 py-2 rounded-lg hover:bg-slate-100 flex items-center justify-center">
                        <i class="bi bi-download mr-2"></i>
                        <span>Download Laporan</span>
                    </button>
                    <button id="openCalendarModalBtn" class="bg-hijau-500 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-hijau-600 flex items-center justify-center space-x-2"><i class="bi bi-calendar-check-fill"></i><span>Lihat Kalender Cuti</span></button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-500">Menunggu</h3>
                        <div class="bg-kuning-100 text-kuning-700 p-2.5 rounded-lg"><i class="bi bi-clock-history text-xl"></i></div>
                    </div>
                    <p id="totalMenunggu" class="text-4xl font-bold text-gray-800 mt-4">0</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-500">Disetujui</h3>
                        <div class="bg-hijau-100 text-hijau-600 p-2.5 rounded-lg"><i class="bi bi-check-circle-fill text-xl"></i></div>
                    </div>
                    <p id="totalDisetujui" class="text-4xl font-bold text-gray-800 mt-4">0</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-500">Ditolak</h3>
                        <div class="bg-merah-100 text-merah-700 p-2.5 rounded-lg"><i class="bi bi-x-circle-fill text-xl"></i></div>
                    </div>
                    <p id="totalDitolak" class="text-4xl font-bold text-gray-800 mt-4">0</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-500">Total Pengajuan</h3>
                        <div class="bg-blue-100 text-blue-600 p-2.5 rounded-lg"><i class="bi bi-journal-text text-xl"></i></div>
                    </div>
                    <p id="totalPengajuan" class="text-4xl font-bold text-gray-800 mt-4">0</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Daftar Pengajuan Cuti Menunggu Persetujuan</h3>
                <div class="overflow-x-auto">
                    <ul id="daftarPengajuan" class="divide-y divide-slate-200"></ul>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Detail Cuti -->
    <div id="modalDetail" class="hidden modal-backdrop">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 md:p-8 modal-content">
            <div class="flex items-center justify-between mb-6"><h3 class="text-2xl font-bold text-gray-800">Detail Pengajuan Cuti</h3><button id="closeModalDetail" class="text-gray-400 hover:text-gray-600 text-3xl">&times;</button></div>
            <div class="space-y-4">
                <div><p class="text-sm text-gray-500">Nama Pemohon</p><p id="detailNama" class="font-semibold text-gray-800"></p></div>
                <div><p class="text-sm text-gray-500">NIP</p><p id="detailNip" class="font-semibold text-gray-800"></p></div>
                <div><p class="text-sm text-gray-500">Jabatan</p><p id="detailJabatan" class="font-semibold text-gray-800"></p></div>
                <div><p class="text-sm text-gray-500">Jenis Cuti</p><p id="detailJenis" class="font-semibold text-gray-800"></p></div>
                <div><p class="text-sm text-gray-500">Tanggal & Durasi</p><p id="detailTanggal" class="font-semibold text-gray-800"></p></div>
                <div><p class="text-sm text-gray-500">Alasan</p><p id="detailAlasan" class="text-gray-800 bg-slate-50 p-3 rounded-md"></p></div>
                <div><p class="text-sm text-gray-500">Alamat Selama Cuti</p><p id="detailAlamat" class="text-gray-800 bg-slate-50 p-3 rounded-md"></p></div>
                <div id="detailLampiranContainer" class="hidden"><p class="text-sm text-gray-500">Lampiran</p><a id="detailLampiranLink" href="#" target="_blank" download="lampiran.txt" class="text-hijau-600 hover:underline font-semibold">Lihat Dokumen</a></div>
            </div>
            <div class="flex justify-end mt-8"><button id="tutupModalDetail" class="px-6 py-2 bg-slate-200 text-gray-800 rounded-lg hover:bg-slate-300 font-semibold">Tutup</button></div>
        </div>
    </div>

    <!-- Modal Laporan -->
    <div id="reportModal" class="hidden custom-modal-backdrop">
        <div class="custom-modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Download Laporan Cuti</h3>
                <button id="closeReportModal" class="text-gray-400 hover:text-gray-600 text-3xl">&times;</button>
            </div>
            <div class="space-y-4">
                <div>
                    <label for="reportMonth" class="block text-sm font-medium text-gray-700 mb-1">Pilih Bulan dan Tahun</label>
                    <input type="month" id="reportMonth" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400">
                </div>
            </div>
            <div class="flex justify-end mt-8 gap-3">
                <button id="cancelReport" type="button" class="px-6 py-2 bg-slate-200 text-gray-800 rounded-lg hover:bg-slate-300 font-semibold">Batal</button>
                <button id="downloadReportBtn" class="px-6 py-2 bg-hijau-500 text-white rounded-lg hover:bg-hijau-600 font-semibold flex items-center gap-2">
                    <i class="bi bi-file-earmark-arrow-down-fill"></i> Download
                </button>
            </div>
        </div>
    </div>

    <!-- Custom Alert/Confirm Modal -->
    <div id="customAlertModal" class="custom-modal-backdrop">
        <div class="custom-modal-content"><h4 id="customAlertTitle" class="text-lg font-bold text-gray-800 mb-4"></h4><p id="customAlertMessage" class="text-gray-700 mb-6"></p><div class="flex justify-end space-x-3"><button id="customAlertCancel" class="px-4 py-2 bg-slate-200 text-gray-700 rounded-lg hover:bg-slate-300 hidden"></button><button id="customAlertOK" class="px-4 py-2 bg-hijau-500 text-white rounded-lg hover:bg-hijau-600"></button></div></div>
    </div>

    <!-- Calendar Modal -->
    <div id="calendarModal" class="custom-modal-backdrop">
        <div class="custom-modal-content">
            <div class="flex items-center justify-between mb-6"><h3 class="text-2xl font-bold text-gray-800">Kalender Cuti ASN</h3><button id="closeCalendarModal" class="text-gray-400 hover:text-gray-600 text-3xl">&times;</button></div>
            <div class="flex items-center justify-between mb-4"><button id="prevMonth" class="px-3 py-1.5 bg-slate-100 text-gray-700 rounded-lg hover:bg-slate-200 font-semibold"><i class="bi bi-chevron-left"></i> Sebelumnya</button><h4 id="currentMonthYear" class="text-lg font-bold text-gray-800"></h4><button id="nextMonth" class="px-3 py-1.5 bg-slate-100 text-gray-700 rounded-lg hover:bg-slate-200 font-semibold">Berikutnya <i class="bi bi-chevron-right"></i></button></div>
            <div class="calendar-grid text-gray-700 font-semibold text-center"><div>Min</div><div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div></div>
            <div id="calendarDays" class="calendar-grid"></div>
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

            // --- Existing Logic ---
            const loggedInUser = JSON.parse(localStorage.getItem('loggedInUser'));
            if (!loggedInUser || loggedInUser.role !== 'admin') {
                window.location.href = '{{ url('/') }}';
                return;
            }

            document.getElementById('logoutButton').addEventListener('click', function() {
                addActivityLog(`Logout sebagai Admin`);
                localStorage.removeItem('loggedInUser');
                window.location.href = '{{ url('/') }}';
            });
            
            let pengajuanCuti = JSON.parse(localStorage.getItem('pengajuanCuti')) || [];
            let dataAsn = JSON.parse(localStorage.getItem('dataAsn')) || [];

            function updateStatsAndRender() {
                pengajuanCuti = JSON.parse(localStorage.getItem('pengajuanCuti')) || [];
                dataAsn = JSON.parse(localStorage.getItem('dataAsn')) || [];

                document.getElementById('totalPengajuan').textContent = pengajuanCuti.length;
                document.getElementById('totalDisetujui').textContent = pengajuanCuti.filter(p => p.status === 'Disetujui').length;
                document.getElementById('totalDitolak').textContent = pengajuanCuti.filter(p => p.status === 'Ditolak').length;
                
                const pengajuanMenunggu = pengajuanCuti.filter(p => p.status === 'Menunggu');
                document.getElementById('totalMenunggu').textContent = pengajuanMenunggu.length;

                const daftarPengajuanEl = document.getElementById('daftarPengajuan');
                daftarPengajuanEl.innerHTML = '';

                if (pengajuanMenunggu.length === 0) {
                    daftarPengajuanEl.innerHTML = `<li class="text-center p-4 text-gray-500">Tidak ada pengajuan cuti yang menunggu persetujuan.</li>`;
                    return;
                }

                pengajuanMenunggu.forEach(p => {
                    const item = `
                        <li class="flex flex-col sm:flex-row items-start sm:items-center justify-between py-4 px-2">
                            <div class="flex items-center gap-4 mb-3 sm:mb-0">
                                <img src="https://placehold.co/40x40/22c55e/FFFFFF?text=${p.nama.charAt(0)}" alt="User" class="w-10 h-10 rounded-full">
                                <div>
                                    <p class="font-semibold text-gray-800">${p.nama}</p>
                                    <p class="text-sm text-gray-500">${p.jenis} &bull; ${p.durasi} Hari</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 self-end sm:self-center">
                                <button data-id="${p.id}" title="Lihat Detail" class="btn-detail p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200"><i class="bi bi-eye-fill"></i></button>
                                <button data-id="${p.id}" title="Tolak Pengajuan" class="btn-tolak px-3 py-1.5 rounded-lg bg-merah-100 text-merah-700 hover:bg-red-200 font-semibold flex items-center gap-1.5"><i class="bi bi-x-lg"></i> <span class="hidden sm:inline">Tolak</span></button>
                                <button data-id="${p.id}" title="Setujui Pengajuan" class="btn-setujui px-3 py-1.5 rounded-lg bg-hijau-100 text-hijau-700 hover:bg-hijau-200 font-semibold flex items-center gap-1.5"><i class="bi bi-check-lg"></i> <span class="hidden sm:inline">Setujui</span></button>
                            </div>
                        </li>
                    `;
                    daftarPengajuanEl.innerHTML += item;
                });
            }
            
            const modalDetail = document.getElementById('modalDetail');
            const closeModalDetail = document.getElementById('closeModalDetail');
            const tutupModalDetail = document.getElementById('tutupModalDetail');
            const detailLampiranContainer = document.getElementById('detailLampiranContainer');
            const detailLampiranLink = document.getElementById('detailLampiranLink');

            function openDetailModal(cuti) {
                document.getElementById('detailNama').textContent = cuti.nama;
                document.getElementById('detailNip').textContent = cuti.nip || 'N/A';
                document.getElementById('detailJabatan').textContent = cuti.jabatan || 'N/A';
                document.getElementById('detailJenis').textContent = cuti.jenis;
                document.getElementById('detailTanggal').textContent = `${cuti.tanggal} (${cuti.durasi} hari)`;
                document.getElementById('detailAlasan').textContent = cuti.alasan || 'Tidak ada alasan diberikan.';
                document.getElementById('detailAlamat').textContent = cuti.alamatCuti || 'Tidak ada alamat diberikan.';
                
                if (cuti.lampiran && cuti.lampiran.data) {
                    detailLampiranLink.href = cuti.lampiran.data;
                    detailLampiranLink.download = cuti.lampiran.nama || 'lampiran';
                    detailLampiranContainer.classList.remove('hidden');
                } else {
                    detailLampiranContainer.classList.add('hidden');
                }

                modalDetail.classList.add('show');
                modalDetail.classList.remove('hidden');
            }

            function closeDetailModal() {
                modalDetail.classList.remove('show');
                setTimeout(() => modalDetail.classList.add('hidden'), 300);
            }

            closeModalDetail.addEventListener('click', closeDetailModal);
            tutupModalDetail.addEventListener('click', closeDetailModal);

            daftarPengajuan.addEventListener('click', function(e) {
                const target = e.target.closest('button');
                if (!target) return;

                const cutiId = parseInt(target.dataset.id);
                const cutiIndex = pengajuanCuti.findIndex(c => c.id === cutiId);
                if (cutiIndex === -1) return;
                
                const cutiData = pengajuanCuti[cutiIndex];
                const asnIndex = dataAsn.findIndex(a => a.id === cutiData.asnId);

                if (target.classList.contains('btn-detail')) {
                    openDetailModal(cutiData);
                }

                if (target.classList.contains('btn-setujui')) {
                    showConfirm('Anda yakin ingin menyetujui pengajuan ini?', (result) => {
                        if (result) {
                            pengajuanCuti[cutiIndex].status = 'Disetujui';
                            if (cutiData.jenis === 'Cuti Tahunan' && asnIndex !== -1) {
                                dataAsn[asnIndex].sisaCuti.diambil += cutiData.durasi;
                                localStorage.setItem('dataAsn', JSON.stringify(dataAsn));
                            }
                            localStorage.setItem('pengajuanCuti', JSON.stringify(pengajuanCuti));
                            updateStatsAndRender();
                            showAlert('Pengajuan berhasil disetujui!');
                            addActivityLog(`Menyetujui pengajuan cuti ${cutiData.jenis} untuk ${cutiData.nama}`);
                        }
                    });
                }

                if (target.classList.contains('btn-tolak')) {
                     showConfirm('Anda yakin ingin menolak pengajuan ini?', (result) => {
                        if (result) {
                            pengajuanCuti[cutiIndex].status = 'Ditolak';
                            localStorage.setItem('pengajuanCuti', JSON.stringify(pengajuanCuti));
                            updateStatsAndRender();
                            showAlert('Pengajuan berhasil ditolak!');
                            addActivityLog(`Menolak pengajuan cuti ${cutiData.jenis} untuk ${cutiData.nama}`);
                        }
                    });
                }
            });

            // --- Calendar Logic ---
            const calendarModal = document.getElementById('calendarModal');
            const openCalendarModalBtn = document.getElementById('openCalendarModalBtn');
            const closeCalendarModalBtn = document.getElementById('closeCalendarModal');
            const calendarDaysEl = document.getElementById('calendarDays');
            const currentMonthYearEl = document.getElementById('currentMonthYear');
            const prevMonthBtn = document.getElementById('prevMonth');
            const nextMonthBtn = document.getElementById('nextMonth');
            let currentDate = new Date();

            function renderCalendar(date) {
                calendarDaysEl.innerHTML = '';
                currentMonthYearEl.textContent = date.toLocaleString('id-ID', { month: 'long', year: 'numeric' });
                const year = date.getFullYear();
                const month = date.getMonth();
                const firstDayOfMonth = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const daysInPrevMonth = new Date(year, month, 0).getDate();
                const totalCells = Math.ceil((firstDayOfMonth + daysInMonth) / 7) * 7;

                for (let i = 0; i < totalCells; i++) {
                    const dayEl = document.createElement('div');
                    dayEl.className = 'calendar-day';
                    const dayNumberEl = document.createElement('span');
                    dayNumberEl.className = 'day-number';
                    dayEl.appendChild(dayNumberEl);
                    const leaveNamesEl = document.createElement('div');
                    leaveNamesEl.className = 'leave-names';
                    dayEl.appendChild(leaveNamesEl);

                    let displayDate;
                    if (i < firstDayOfMonth) {
                        displayDate = new Date(year, month - 1, daysInPrevMonth - firstDayOfMonth + i + 1);
                        dayEl.classList.add('other-month');
                    } else if (i >= firstDayOfMonth + daysInMonth) {
                        displayDate = new Date(year, month + 1, i - firstDayOfMonth - daysInMonth + 1);
                        dayEl.classList.add('other-month');
                    } else {
                        displayDate = new Date(year, month, i - firstDayOfMonth + 1);
                        dayEl.classList.add('current-month');
                    }
                    dayNumberEl.textContent = displayDate.getDate();

                    const today = new Date();
                    if (displayDate.toDateString() === today.toDateString()) {
                        dayEl.classList.add('today');
                    }

                    const leavesOnThisDay = getLeavesForDate(displayDate);
                    if (leavesOnThisDay.length > 0) {
                        dayEl.classList.add('has-leave');
                        const names = leavesOnThisDay.map(l => l.nama.split(' ')[0]);
                        leaveNamesEl.textContent = names.length > 2 ? `${names.slice(0,2).join(', ')}...` : names.join(', ');
                        dayEl.addEventListener('click', () => showDayLeaveDetails(displayDate, leavesOnThisDay));
                    }
                    calendarDaysEl.appendChild(dayEl);
                }
            }

            function getLeavesForDate(date) {
                const currentLeaves = JSON.parse(localStorage.getItem('pengajuanCuti')) || [];
                const checkDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
                return currentLeaves.filter(leave => {
                    if (leave.status !== 'Disetujui') return false;
                    const [startStr, endStr] = leave.tanggal.split(' - ');
                    const leaveStart = new Date(new Date(startStr).setHours(0,0,0,0));
                    const leaveEnd = new Date(new Date(endStr).setHours(0,0,0,0));
                    return checkDate >= leaveStart && checkDate <= leaveEnd;
                });
            }

            function showDayLeaveDetails(date, leaves) {
                let detailsHtml = `<p class="text-gray-700 mb-4">ASN yang Cuti pada ${date.toLocaleDateString('id-ID')}:</p><ul class="list-disc list-inside text-gray-800">${leaves.map(l => `<li>${l.nama} (${l.jenis})</li>`).join('')}</ul>`;
                showAlert(detailsHtml, null, 'Detail Cuti Harian');
            }

            openCalendarModalBtn.addEventListener('click', () => {
                calendarModal.classList.add('show');
                renderCalendar(currentDate);
            });
            closeCalendarModalBtn.addEventListener('click', () => calendarModal.classList.remove('show'));
            prevMonthBtn.addEventListener('click', () => { currentDate.setMonth(currentDate.getMonth() - 1); renderCalendar(currentDate); });
            nextMonthBtn.addEventListener('click', () => { currentDate.setMonth(currentDate.getMonth() + 1); renderCalendar(currentDate); });
            calendarModal.addEventListener('click', (e) => { if (e.target === calendarModal) calendarModal.classList.remove('show'); });

            // --- Report Logic ---
            const reportModal = document.getElementById('reportModal');
            const openReportModalBtn = document.getElementById('openReportModalBtn');
            const closeReportModalBtn = document.getElementById('closeReportModal');
            const cancelReportBtn = document.getElementById('cancelReport');
            const downloadReportBtn = document.getElementById('downloadReportBtn');
            const reportMonthInput = document.getElementById('reportMonth');

            const now = new Date();
            reportMonthInput.value = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;

            function openReportModal() {
                reportModal.classList.remove('hidden');
                setTimeout(() => reportModal.classList.add('show'), 10);
            }
            function closeReportModal() {
                reportModal.classList.remove('show');
                setTimeout(() => reportModal.classList.add('hidden'), 300);
            }
            
            function sanitizeCsvField(field, isNip = false) {
                if (field === null || field === undefined) {
                    return '""';
                }
                let sanitized = field.toString();
                if (isNip) {
                    sanitized = "\t" + sanitized;
                }
                sanitized = sanitized.replace(/(\r\n|\n|\r)/gm, " ");
                sanitized = sanitized.replace(/"/g, '""');
                return `"${sanitized}"`;
            }

            openReportModalBtn.addEventListener('click', openReportModal);
            closeReportModalBtn.addEventListener('click', closeReportModal);
            cancelReportBtn.addEventListener('click', closeReportModal);

            downloadReportBtn.addEventListener('click', () => {
                const selectedMonth = reportMonthInput.value;
                if (!selectedMonth) {
                    showAlert('Silakan pilih bulan dan tahun terlebih dahulu.');
                    return;
                }
                
                const [year, month] = selectedMonth.split('-');
                const allCuti = JSON.parse(localStorage.getItem('pengajuanCuti')) || [];
                
                const filteredCuti = allCuti.filter(cuti => {
                    if (cuti.status !== 'Disetujui') return false;
                    const [startDateStr] = cuti.tanggal.split(' - ');
                    const startDate = new Date(startDateStr);
                    return startDate.getFullYear() == year && (startDate.getMonth() + 1) == month;
                });

                if (filteredCuti.length === 0) {
                    showAlert('Tidak ada data cuti yang disetujui pada bulan yang dipilih.');
                    return;
                }

                let csvContent = "\uFEFF"; 
                const headers = ["Nama", "NIP", "Jabatan", "Jenis Cuti", "Tanggal Mulai", "Tanggal Selesai", "Durasi (Hari)", "Alasan"];
                csvContent += headers.join(";") + "\r\n";

                filteredCuti.forEach(cuti => {
                    const [startDate, endDate] = cuti.tanggal.split(' - ');
                    const row = [
                        sanitizeCsvField(cuti.nama),
                        sanitizeCsvField(cuti.nip, true),
                        sanitizeCsvField(cuti.jabatan),
                        sanitizeCsvField(cuti.jenis),
                        sanitizeCsvField(startDate),
                        sanitizeCsvField(endDate),
                        sanitizeCsvField(cuti.durasi),
                        sanitizeCsvField(cuti.alasan)
                    ].join(";");
                    csvContent += row + "\r\n";
                });

                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement("a");
                const url = URL.createObjectURL(blob);
                link.setAttribute("href", url);
                link.setAttribute("download", `laporan_cuti_${year}_${month}.csv`);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                addActivityLog(`Mengunduh laporan cuti untuk bulan ${month}-${year}`);
                closeReportModal();
            });

            updateStatsAndRender();
        });
    </script>
</body>
</html>
