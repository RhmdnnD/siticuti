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
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            width: 90%; max-width: 500px; transform: translateY(-20px);
            transition: transform 0.3s ease;
        }
        .modal-backdrop.show .modal-content, .custom-modal-backdrop.show .custom-modal-content { transform: translateY(0); }
        
        #modalDetail { z-index: 50; }
        #reportModal { z-index: 10000; }
        #calendarModal { z-index: 10000; }
        #calendarDetailModal { z-index: 10005; } /* Z-index lebih tinggi agar berada di atas kalender */

        .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.5rem; }
        .calendar-day { 
            padding: 0.5rem; border-radius: 0.5rem; min-height: 80px; max-height: 110px; 
            display: flex; flex-direction: column; align-items: center; text-align: center; 
            font-size: 0.875rem; overflow-y: auto; word-wrap: break-word; 
        }
        
        /* Scrollbar kustom untuk kotak kalender dan popup */
        .custom-scrollbar::-webkit-scrollbar, .calendar-day::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track, .calendar-day::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb, .calendar-day::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .calendar-day::-webkit-scrollbar-thumb { background: #86efac; } /* Scrollbar hijau khusus kalender */

        .calendar-day.current-month { background-color: #f1f5f9; }
        .calendar-day.other-month { background-color: #e2e8f0; color: #94a3b8; }
        .calendar-day.has-leave { background-color: #dcfce7; border: 1px solid #4ade80; cursor: pointer; transition: background-color 0.2s; }
        .calendar-day.has-leave:hover { background-color: #bbf7d0; }
        .calendar-day .day-number { font-weight: 600; margin-bottom: 0.25rem; }
        .calendar-day .leave-names { font-size: 0.75rem; color: #16a34a; line-height: 1.2; margin-bottom: 2px; }
        .calendar-day.today { background-color: #86efac; border: 2px solid #16a34a; color: #15803d; }
        #calendarModal .custom-modal-content { max-width: 700px; width: 95%; max-height: 90vh; overflow-y: hidden; display: flex; flex-direction: column; }
    </style>
</head>
<body class="font-sans bg-slate-50">
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

    <div class="relative min-h-screen lg:flex">
        <aside id="sidebar" class="bg-white w-64 flex-col fixed inset-y-0 left-0 transform -translate-x-full transition-transform duration-300 ease-in-out z-50 lg:relative lg:translate-x-0 lg:flex border-r border-slate-200">
            <div class="h-20 flex items-center px-6">
                <div class="flex items-center space-x-3">
                    <div class="bg-hijau-500 text-white p-2.5 rounded-lg shadow-sm"><i class="bi bi-shield-check text-xl"></i></div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-800">ADMIN PANEL</h1>
                        <p class="text-xs text-gray-500">SITI CUTI</p>
                    </div>
                </div>
            </div>
            
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ url('/admin') }}" class="flex items-center px-4 py-2.5 text-white bg-hijau-500 rounded-lg font-semibold"><i class="bi bi-grid-1x2-fill mr-3"></i> Dashboard</a>
                <a href="{{ url('/admin/asn') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-people-fill mr-3"></i> Manajemen ASN</a>
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

            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Selamat Datang, Admin!</h1>
                    <p class="text-gray-500 mt-1">Berikut adalah ringkasan pengajuan cuti terbaru.</p>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 mt-4 md:mt-0">
                    <button id="openReportModalBtn" class="bg-white border border-slate-200 text-gray-600 font-semibold px-4 py-2 rounded-lg hover:bg-slate-100 flex items-center justify-center shadow-sm transition-colors">
                        <i class="bi bi-download mr-2"></i><span>Download Laporan</span>
                    </button>
                    <button id="openCalendarModalBtn" class="bg-hijau-500 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:bg-hijau-600 flex items-center justify-center space-x-2 transition-colors"><i class="bi bi-calendar-check-fill"></i><span>Lihat Kalender Cuti</span></button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-500">Menunggu</h3>
                        <div class="bg-kuning-100 text-kuning-700 p-2.5 rounded-lg"><i class="bi bi-clock-history text-xl"></i></div>
                    </div>
                    <p class="text-4xl font-bold text-gray-800 mt-4">{{ $menunggu }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-500">Disetujui</h3>
                        <div class="bg-hijau-100 text-hijau-600 p-2.5 rounded-lg"><i class="bi bi-check-circle-fill text-xl"></i></div>
                    </div>
                    <p class="text-4xl font-bold text-gray-800 mt-4">{{ $disetujui }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-500">Ditolak</h3>
                        <div class="bg-merah-100 text-merah-700 p-2.5 rounded-lg"><i class="bi bi-x-circle-fill text-xl"></i></div>
                    </div>
                    <p class="text-4xl font-bold text-gray-800 mt-4">{{ $ditolak }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-500">Total Pengajuan</h3>
                        <div class="bg-blue-100 text-blue-600 p-2.5 rounded-lg"><i class="bi bi-journal-text text-xl"></i></div>
                    </div>
                    <p class="text-4xl font-bold text-gray-800 mt-4">{{ count($semuaCuti) }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Daftar Pengajuan Cuti Menunggu Persetujuan</h3>
                <div class="overflow-x-auto">
                    <ul class="divide-y divide-slate-200">
                        @forelse ($pengajuanMenunggu as $p)
                            <li class="flex flex-col sm:flex-row items-start sm:items-center justify-between py-4 px-2 hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-4 mb-3 sm:mb-0">
                                    <div class="w-10 h-10 rounded-full bg-hijau-100 text-hijau-600 flex items-center justify-center font-bold text-lg shrink-0">
                                        {{ substr($p->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $p->user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $p->jenis_cuti }} &bull; {{ $p->durasi }} Hari</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 self-end sm:self-center">
    
                                    <button type="button" onclick="bukaDetailModal({{ json_encode($p) }})" title="Lihat Detail" class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors"><i class="bi bi-eye-fill"></i></button>
                                    
                                    <form action="{{ url('/admin/cuti/'.$p->id.'/status') }}" method="POST" class="form-confirm inline" data-title="Tolak Pengajuan Cuti?" data-text="Pegawai akan menerima status penolakan ini." data-icon="warning" data-btn-confirm="bg-red-500 hover:bg-red-600 text-white">
                                        @csrf
                                        <input type="hidden" name="status" value="Ditolak">
                                        <button type="submit" title="Tolak Pengajuan" class="px-3 py-1.5 rounded-lg bg-merah-100 text-merah-700 hover:bg-red-200 font-semibold flex items-center gap-1.5 transition-colors"><i class="bi bi-x-lg"></i> <span class="hidden sm:inline">Tolak</span></button>
                                    </form>

                                    <form action="{{ url('/admin/cuti/'.$p->id.'/status') }}" method="POST" class="form-confirm inline" data-title="Setujui Pengajuan Cuti?" data-text="Cuti akan disetujui. (Sisa cuti otomatis berkurang khusus untuk Cuti Tahunan)" data-icon="question" data-btn-confirm="bg-hijau-500 hover:bg-hijau-600 text-white">
                                        @csrf
                                        <input type="hidden" name="status" value="Disetujui">
                                        <button type="submit" title="Setujui Pengajuan" class="px-3 py-1.5 rounded-lg bg-hijau-100 text-hijau-700 hover:bg-hijau-200 font-semibold flex items-center gap-1.5 transition-colors"><i class="bi bi-check-lg"></i> <span class="hidden sm:inline">Setujui</span></button>
                                    </form>
                                    
                                </div>
                            </li>
                        @empty
                            <li class="text-center p-8 text-gray-500">
                                <i class="bi bi-check2-circle text-5xl text-gray-300 mb-3 block"></i>
                                <span class="font-medium text-lg">Bagus!</span><br>Tidak ada antrean pengajuan cuti saat ini.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </main>
    </div>

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
                
                <div id="detailLampiranContainer" class="hidden"><p class="text-sm text-gray-500">Lampiran</p><a id="detailLampiranLink" href="#" target="_blank" class="text-hijau-600 hover:underline font-semibold flex items-center gap-1 mt-1"><i class="bi bi-file-earmark-text"></i> Lihat Dokumen</a></div>
            </div>
            <div class="flex justify-end mt-8"><button id="tutupModalDetail" class="px-6 py-2 bg-slate-200 text-gray-800 rounded-lg hover:bg-slate-300 font-semibold transition-colors">Tutup</button></div>
        </div>
    </div>

    <div id="reportModal" class="hidden custom-modal-backdrop">
        <div class="custom-modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800 flex items-center"><i class="bi bi-file-earmark-arrow-down-fill text-hijau-500 mr-2"></i> Ekspor Laporan</h3>
                <button id="closeReportModal" class="text-gray-400 hover:text-gray-600 text-3xl">&times;</button>
            </div>
            
            <form id="formReport" action="{{ url('/admin/laporan/export') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                        <select id="reportMonth" name="bulan" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" required>
                            <option value="all">Semua Bulan</option>
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                        <select id="reportYear" name="tahun" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" required>
                            <option value="all">Semua Tahun</option>
                            <option value="2026">2026</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end mt-8 gap-3">
                    <button type="button" id="cancelReport" class="px-6 py-2 bg-slate-100 text-gray-700 rounded-lg hover:bg-slate-200 font-semibold transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-hijau-500 text-white rounded-lg hover:bg-hijau-600 font-semibold flex items-center gap-2 transition-colors shadow-sm">
                        <i class="bi bi-download"></i> Download CSV
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="calendarModal" class="custom-modal-backdrop hidden">
        <div class="custom-modal-content flex-1">
            <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                <h3 class="text-2xl font-bold text-gray-800 flex items-center"><i class="bi bi-calendar3 text-hijau-500 mr-2"></i> Kalender Cuti</h3>
                <button id="closeCalendarModal" class="text-gray-400 hover:text-gray-600 text-3xl">&times;</button>
            </div>
            
            <div class="flex items-center justify-between mb-4">
                <button id="prevMonth" class="px-3 py-1.5 bg-slate-100 text-gray-700 rounded-lg hover:bg-slate-200 font-semibold transition-colors"><i class="bi bi-chevron-left"></i> Sebelumnya</button>
                <h4 id="currentMonthYear" class="text-lg font-bold text-gray-800 uppercase tracking-wide"></h4>
                <button id="nextMonth" class="px-3 py-1.5 bg-slate-100 text-gray-700 rounded-lg hover:bg-slate-200 font-semibold transition-colors">Berikutnya <i class="bi bi-chevron-right"></i></button>
            </div>
            
            <div class="calendar-grid text-gray-500 text-sm font-bold text-center mb-2">
                <div>MIN</div><div>SEN</div><div>SEL</div><div>RAB</div><div>KAM</div><div>JUM</div><div>SAB</div>
            </div>
            <div id="calendarDays" class="calendar-grid overflow-y-auto custom-scrollbar flex-1 pb-4"></div>
        </div>
    </div>

    <div id="calendarDetailModal" class="custom-modal-backdrop hidden">
        <div class="custom-modal-content" style="max-width: 450px;">
            <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="bi bi-calendar2-day-fill text-hijau-500 mr-2"></i> 
                    <span id="calendarDetailTitle">Detail Cuti</span>
                </h3>
                <button id="closeCalendarDetailModal" class="text-gray-400 hover:text-gray-600 text-3xl">&times;</button>
            </div>
            
            <div id="calendarDetailList" class="space-y-3 max-h-64 overflow-y-auto pr-2 custom-scrollbar">
                </div>
            
            <div class="mt-6 flex justify-end pt-4 border-t border-slate-100">
                <button id="btnTutupCalendarDetail" class="px-5 py-2 bg-slate-100 text-slate-700 font-semibold rounded-lg hover:bg-slate-200 transition-colors">Tutup</button>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar Mobile Toggle
            const menuToggle = document.getElementById('menu-toggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            function toggleMenu() {
                sidebar.classList.toggle('-translate-x-full');
                sidebarOverlay.classList.toggle('hidden');
            }

            if (menuToggle) menuToggle.addEventListener('click', toggleMenu);
            if (sidebarOverlay) sidebarOverlay.addEventListener('click', toggleMenu);
            
            // --- DATA DARI DATABASE ---
            const semuaCutiData = @json($semuaCuti ?? []);

            // --- MODAL DETAIL CUTI ---
            const modalDetail = document.getElementById('modalDetail');
            const closeModalDetail = document.getElementById('closeModalDetail');
            const tutupModalDetail = document.getElementById('tutupModalDetail');

            window.bukaDetailModal = function(cuti) {
                document.getElementById('detailNama').textContent = cuti.user.name;
                document.getElementById('detailNip').textContent = cuti.user.nip || '-';
                document.getElementById('detailJabatan').textContent = cuti.user.jabatan || '-';
                document.getElementById('detailJenis').textContent = cuti.jenis_cuti;
                document.getElementById('detailTanggal').textContent = `${cuti.tanggal_mulai} s/d ${cuti.tanggal_selesai} (${cuti.durasi} Hari)`;
                document.getElementById('detailAlasan').textContent = cuti.alasan || '-';
                document.getElementById('detailAlamat').textContent = cuti.alamat || '-';
                
                if (cuti.lampiran) {
                    document.getElementById('detailLampiranContainer').classList.remove('hidden');
                    document.getElementById('detailLampiranLink').href = '{{ asset("storage") }}/' + cuti.lampiran;
                } else {
                    document.getElementById('detailLampiranContainer').classList.add('hidden');
                }

                modalDetail.classList.add('show');
                modalDetail.classList.remove('hidden');
            };

            function closeDetailModal() {
                modalDetail.classList.remove('show');
                setTimeout(() => modalDetail.classList.add('hidden'), 300);
            }

            closeModalDetail.addEventListener('click', closeDetailModal);
            tutupModalDetail.addEventListener('click', closeDetailModal);

            // --- MODAL LAPORAN ---
            const reportModal = document.getElementById('reportModal');
            const openReportModalBtn = document.getElementById('openReportModalBtn');
            const closeReportModalBtn = document.getElementById('closeReportModal');
            const cancelReportBtn = document.getElementById('cancelReport');
            const formReport = document.getElementById('formReport');

            function openReportModal() {
                reportModal.classList.remove('hidden');
                setTimeout(() => reportModal.classList.add('show'), 10);
            }
            function closeReportModal() {
                reportModal.classList.remove('show');
                setTimeout(() => reportModal.classList.add('hidden'), 300);
            }

            openReportModalBtn.addEventListener('click', openReportModal);
            closeReportModalBtn.addEventListener('click', closeReportModal);
            cancelReportBtn.addEventListener('click', closeReportModal);

            formReport.addEventListener('submit', function(e) {
                e.preventDefault(); 
                
                const month = document.getElementById('reportMonth').value;
                const year = document.getElementById('reportYear').value;

                const filteredCuti = semuaCutiData.filter(cuti => {
                    const cutiDate = new Date(cuti.created_at);
                    const cMonth = String(cutiDate.getMonth() + 1).padStart(2, '0');
                    const cYear = String(cutiDate.getFullYear());
                    
                    const matchMonth = month === 'all' || cMonth === month;
                    const matchYear = year === 'all' || cYear === year;
                    return matchMonth && matchYear;
                });

                if (filteredCuti.length === 0) {
                    alert('Tidak ada pengajuan cuti pada periode yang dipilih.');
                    return;
                }

                const sanitizeCsvField = (field) => {
                    if (field === null || field === undefined) return '""';
                    const str = field.toString().replace(/"/g, '""');
                    return `"${str}"`;
                };

                let csvContent = "Nama Pegawai;NIP;Jabatan;Jenis Cuti;Tgl Mulai;Tgl Selesai;Durasi (Hari);Status;Alasan\r\n";

                filteredCuti.forEach(cuti => {
                    const userName = cuti.user ? cuti.user.name : '-';
                    const userNip = cuti.user ? cuti.user.nip : '-';
                    const userJab = cuti.user ? cuti.user.jabatan : '-';
                    
                    const row = [
                        sanitizeCsvField(userName),
                        sanitizeCsvField(userNip),
                        sanitizeCsvField(userJab),
                        sanitizeCsvField(cuti.jenis_cuti),
                        sanitizeCsvField(cuti.tanggal_mulai),
                        sanitizeCsvField(cuti.tanggal_selesai),
                        sanitizeCsvField(cuti.durasi),
                        sanitizeCsvField(cuti.status),
                        sanitizeCsvField(cuti.alasan)
                    ].join(";");
                    csvContent += row + "\r\n";
                });

                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement("a");
                const url = URL.createObjectURL(blob);
                link.setAttribute("href", url);
                link.setAttribute("download", `Laporan_Cuti_${month}_${year}.csv`);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                closeReportModal();
            });

            // --- FUNGSI KALENDER PINTAR & POPUP DETAIL ---
            const calendarModal = document.getElementById('calendarModal');
            const openCalendarModalBtn = document.getElementById('openCalendarModalBtn');
            const closeCalendarModalBtn = document.getElementById('closeCalendarModal');
            const calendarDays = document.getElementById('calendarDays');
            const currentMonthYear = document.getElementById('currentMonthYear');
            const prevMonthBtn = document.getElementById('prevMonth');
            const nextMonthBtn = document.getElementById('nextMonth');

            // Setup Modal Detail Tanggal
            const calendarDetailModal = document.getElementById('calendarDetailModal');
            const closeCalendarDetailModalBtn = document.getElementById('closeCalendarDetailModal');
            const btnTutupCalendarDetail = document.getElementById('btnTutupCalendarDetail');
            const calendarDetailTitle = document.getElementById('calendarDetailTitle');
            const calendarDetailList = document.getElementById('calendarDetailList');
            
            function closeCalendarDetail() {
                calendarDetailModal.classList.remove('show');
                setTimeout(() => calendarDetailModal.classList.add('hidden'), 300);
            }
            closeCalendarDetailModalBtn.addEventListener('click', closeCalendarDetail);
            btnTutupCalendarDetail.addEventListener('click', closeCalendarDetail);

            let currentCalDate = new Date();
            let currentMonth = currentCalDate.getMonth();
            let currentYear = currentCalDate.getFullYear();

            const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

            // Fungsi Membuka Popup Detail
            window.showCalendarDetail = function(day, month, year, leaves) {
                // Set Judul (Contoh: 15 Agustus 2026)
                calendarDetailTitle.textContent = `${day} ${monthNames[month]} ${year}`;
                
                // Kosongkan list sebelumnya
                calendarDetailList.innerHTML = '';
                
                leaves.forEach(leave => {
                    const fullName = leave.user ? leave.user.name : 'Seseorang';
                    const jenisCuti = leave.jenis_cuti;
                    const inisial = fullName.charAt(0).toUpperCase();
                    
                    // Card elegan untuk masing-masing orang
                    const html = `
                        <div class="flex items-center p-3 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100 transition-colors">
                            <div class="w-10 h-10 rounded-full bg-hijau-100 text-hijau-600 flex items-center justify-center font-bold text-lg shrink-0 mr-3 shadow-sm border border-hijau-200">
                                ${inisial}
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">${fullName}</p>
                                <p class="text-xs text-gray-500 mt-0.5"><span class="font-semibold text-hijau-600">${jenisCuti}</span> &bull; ${leave.durasi} Hari</p>
                            </div>
                        </div>
                    `;
                    calendarDetailList.insertAdjacentHTML('beforeend', html);
                });
                
                calendarDetailModal.classList.remove('hidden');
                setTimeout(() => calendarDetailModal.classList.add('show'), 10);
            };

            function renderCalendar(month, year) {
                calendarDays.innerHTML = '';
                currentMonthYear.textContent = `${monthNames[month]} ${year}`;

                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();

                const today = new Date();
                const isCurrentMonth = today.getMonth() === month && today.getFullYear() === year;

                // Loop kotak kosong sebelum tanggal 1
                for (let i = 0; i < firstDay; i++) {
                    const emptyDiv = document.createElement('div');
                    emptyDiv.className = 'calendar-day other-month';
                    calendarDays.appendChild(emptyDiv);
                }

                // Loop tanggal 1 sampai akhir bulan
                for (let i = 1; i <= daysInMonth; i++) {
                    const dayDiv = document.createElement('div');
                    dayDiv.className = 'calendar-day current-month';
                    
                    if (isCurrentMonth && i === today.getDate()) {
                        dayDiv.classList.add('today');
                    }

                    // Format tanggal YYYY-MM-DD
                    const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
                    
                    // Filter siapa saja yang cutinya "Disetujui" dan mencakup tanggal ini
                    const leavesOnThisDay = semuaCutiData.filter(cuti => {
                        if (cuti.status !== 'Disetujui') return false;
                        return dateString >= cuti.tanggal_mulai && dateString <= cuti.tanggal_selesai;
                    });

                    let contentHtml = `<div class="day-number">${i}</div>`;
                    
                    if (leavesOnThisDay.length > 0) {
                        dayDiv.classList.add('has-leave');
                        
                        // Menampilkan Nama Depan Pegawai yang Cuti
                        leavesOnThisDay.forEach(leave => {
                            const firstName = leave.user ? leave.user.name.split(' ')[0] : 'Seseorang';
                            contentHtml += `<div class="leave-names" title="${leave.jenis_cuti}">${firstName}</div>`;
                        });

                        // EVENT LISTENER KLIK (Akan membuka popup Detail)
                        dayDiv.addEventListener('click', function() {
                            showCalendarDetail(i, month, year, leavesOnThisDay);
                        });
                    }

                    dayDiv.innerHTML = contentHtml;
                    calendarDays.appendChild(dayDiv);
                }
            }

            openCalendarModalBtn.addEventListener('click', () => {
                renderCalendar(currentMonth, currentYear);
                calendarModal.classList.remove('hidden');
                setTimeout(() => calendarModal.classList.add('show'), 10);
            });

            closeCalendarModalBtn.addEventListener('click', () => {
                calendarModal.classList.remove('show');
                setTimeout(() => calendarModal.classList.add('hidden'), 300);
            });

            prevMonthBtn.addEventListener('click', () => {
                currentMonth--;
                if (currentMonth < 0) { currentMonth = 11; currentYear--; }
                renderCalendar(currentMonth, currentYear);
            });

            nextMonthBtn.addEventListener('click', () => {
                currentMonth++;
                if (currentMonth > 11) { currentMonth = 0; currentYear++; }
                renderCalendar(currentMonth, currentYear);
            });
        });
    </script>
    @include('components.notifikasi')
</body>
</html>