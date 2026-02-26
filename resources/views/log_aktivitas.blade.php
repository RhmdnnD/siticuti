<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas - Admin Panel</title>
    
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
                        'slate': { '50': '#f8fafc', '100': '#f1f5f9', '200': '#e2e8f0' }
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
        /* Custom radio button style */
        .custom-radio:checked {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3ccircle cx='8' cy='8' r='3'/%3e%3c/svg%3e");
            border-color: #22c55e;
            background-color: #22c55e;
        }
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
                <a href="{{ url('/admin/asn') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-people-fill mr-3"></i> Manajemen ASN</a>
                <a href="{{ url('/admin/cuti') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-calendar-range-fill mr-3"></i> Manajemen Jenis Cuti</a>
                <a href="{{ url('/admin/atasan') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-person-badge-fill mr-3"></i> Manajemen Atasan</a>
                <a href="{{ url('/admin/libur') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-calendar-x-fill mr-3"></i> Manajemen Hari Libur</a>
                <a href="{{ url('/admin/log') }}" class="flex items-center px-4 py-2.5 text-white bg-hijau-500 rounded-lg font-semibold"><i class="bi bi-file-earmark-text-fill mr-3"></i> Log Aktivitas</a>
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
                <h1 class="text-3xl font-bold text-gray-800">Log Aktivitas</h1>
                <p class="text-gray-500 mt-1">Lihat dan kelola semua log aktivitas yang tercatat di sistem.</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
                <!-- Auto-delete settings -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8 border-b border-slate-200 pb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Penghapusan Log Otomatis</h3>
                        <p class="text-sm text-gray-500 mt-1">Hapus log secara otomatis setelah periode tertentu.</p>
                    </div>
                    <div id="autoDeleteOptions" class="flex flex-wrap items-center gap-x-6 gap-y-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="autoDelete" value="0" class="custom-radio text-hijau-500 focus:ring-hijau-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Jangan Hapus</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="autoDelete" value="1" class="custom-radio text-hijau-500 focus:ring-hijau-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">1 Bulan</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="autoDelete" value="3" class="custom-radio text-hijau-500 focus:ring-hijau-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">3 Bulan</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="autoDelete" value="6" class="custom-radio text-hijau-500 focus:ring-hijau-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">6 Bulan</span>
                        </label>
                    </div>
                </div>

                <!-- Log list and manual clear button -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                    <h3 class="text-xl font-bold text-gray-800">Daftar Log Aktivitas</h3>
                    <button id="bersihkanLogButton" class="w-full sm:w-auto px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 font-semibold flex items-center justify-center gap-2">
                        <i class="bi bi-eraser-fill"></i>
                        <span>Bersihkan Log Sekarang</span>
                    </button>
                </div>
                <ul id="activityLogList" class="divide-y divide-slate-100 max-h-[60vh] overflow-y-auto border-t border-slate-200">
                    <!-- Logs will be rendered here -->
                </ul>
            </div>
        </main>
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
            const loggedInUser = JSON.parse(localStorage.getItem('loggedInUser'));
            if (!loggedInUser || loggedInUser.role !== 'admin') {
                window.location.href = '{{ url('/') }}';
                return;
            }

            const activityLogList = document.getElementById('activityLogList');
            const bersihkanLogButton = document.getElementById('bersihkanLogButton');
            const autoDeleteOptions = document.querySelectorAll('input[name="autoDelete"]');

            function renderLogs() {
                let allLogs = JSON.parse(localStorage.getItem('activityLogs')) || [];
                activityLogList.innerHTML = '';

                const adminLogs = allLogs.filter(log => log.role === 'admin' || log.role === 'sistem');

                if (adminLogs.length === 0) {
                    activityLogList.innerHTML = `<li class="p-4 text-gray-500 text-center">Tidak ada log aktivitas admin.</li>`;
                    return;
                }

                adminLogs.forEach(log => {
                    const roleColor = log.role === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800';
                    const formattedTimestamp = new Date(log.timestamp).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' });
                    
                    const listItem = `
                        <li class="p-4 hover:bg-slate-50">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-gray-800">${log.action}</p>
                                    <p class="text-sm text-gray-500">Oleh: ${log.user} pada ${formattedTimestamp}</p>
                                </div>
                                <span class="text-xs font-bold px-2 py-1 rounded-full ${roleColor} ml-2 flex-shrink-0">${log.role}</span>
                            </div>
                        </li>
                    `;
                    activityLogList.innerHTML += listItem;
                });
            }

            bersihkanLogButton.addEventListener('click', function() {
                showConfirm('Apakah Anda yakin ingin menghapus SEMUA log aktivitas admin dan sistem?', (result) => {
                    if (result) {
                        let allLogs = JSON.parse(localStorage.getItem('activityLogs')) || [];
                        const otherLogs = allLogs.filter(log => log.role !== 'admin' && log.role !== 'sistem');
                        localStorage.setItem('activityLogs', JSON.stringify(otherLogs));
                        renderLogs();
                        showAlert('Semua log aktivitas admin dan sistem telah dibersihkan.');
                        addActivityLog('Membersihkan semua log aktivitas.');
                    }
                }, 'Konfirmasi Pembersihan Log');
            });

            // --- Auto-Delete Settings Logic ---
            function saveAutoDeleteSetting(event) {
                const selectedValue = event.target.value;
                localStorage.setItem('logAutoDeleteMonths', selectedValue);
                const selectedLabel = event.target.nextElementSibling.textContent;
                showAlert('Pengaturan penghapusan otomatis berhasil disimpan!');
                addActivityLog(`Mengubah pengaturan hapus log otomatis menjadi: ${selectedLabel.trim()}`);
            }

            function loadAutoDeleteSetting() {
                const savedValue = localStorage.getItem('logAutoDeleteMonths') || '3'; // Default to 3 Bulan
                const optionToSelect = document.querySelector(`input[name="autoDelete"][value="${savedValue}"]`);
                if (optionToSelect) {
                    optionToSelect.checked = true;
                }
            }

            autoDeleteOptions.forEach(radio => {
                radio.addEventListener('change', saveAutoDeleteSetting);
            });
            
            loadAutoDeleteSetting();
            renderLogs();
        });
    </script>
</body>
</html>
