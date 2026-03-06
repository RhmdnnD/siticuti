<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        'hijau': { '50': '#f0fdf4', '100': '#dcfce7', '200': '#bbf7d0', '300': '#86efac', '400': '#4ade80', '500': '#22c55e', '600': '#16a34a', '700': '#15803d' },
                        'slate': { '50': '#f8fafc', '100': '#f1f5f9', '200': '#e2e8f0' }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans bg-slate-50">
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

    <div class="relative min-h-screen lg:flex">
        <aside id="sidebar" class="bg-white w-64 flex-col fixed inset-y-0 left-0 transform -translate-x-full transition-transform duration-300 ease-in-out z-50 lg:relative lg:translate-x-0 lg:flex border-r border-slate-200">
            <div class="h-20 flex items-center px-6">
                <div class="flex items-center space-x-3">
                    <div class="bg-hijau-500 text-white p-2.5 rounded-lg shadow-sm"><i class="bi bi-shield-check text-xl"></i></div>
                    <div><h1 class="text-lg font-bold text-gray-800">ADMIN PANEL</h1><p class="text-xs text-gray-500">SITI CUTI</p></div>
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
                <a href="{{ url('/logout') }}" class="flex items-center justify-center w-full px-4 py-2.5 text-red-500 bg-red-50 hover:bg-red-100 rounded-lg font-semibold transition-colors"><i class="bi bi-box-arrow-right mr-3"></i> Logout</a>
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

            <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Log Aktivitas Sistem</h1>
                    <p class="text-gray-500 mt-1">Pantau seluruh riwayat aktivitas yang terjadi di dalam SITI CUTI.</p>
                </div>
                
                <form action="{{ url('/admin/log/clear') }}" method="POST" class="w-full md:w-auto" onsubmit="return confirm('Peringatan: Tindakan ini akan MENGHAPUS SEMUA riwayat log aktivitas secara permanen. Anda yakin?');">
                    @csrf
                    <button type="submit" class="w-full bg-red-50 text-red-600 font-bold py-2.5 px-5 rounded-lg shadow-sm hover:bg-red-100 flex items-center justify-center space-x-2 transition-colors">
                        <i class="bi bi-trash3-fill"></i><span>Bersihkan Semua Log</span>
                    </button>
                </form>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-hijau-50 border border-hijau-200 text-hijau-700 rounded-lg font-semibold flex items-center">
                    <i class="bi bi-check-circle-fill mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center"><i class="bi bi-gear-fill text-slate-400 mr-2"></i> Pengaturan Penyimpanan Log</h3>
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <p class="text-sm text-gray-600 font-medium w-48">Hapus otomatis log yang lebih tua dari:</p>
                    <div class="flex flex-wrap gap-3">
                        <label class="cursor-pointer relative">
                            <input type="radio" name="autoDelete" value="1" class="peer sr-only">
                            <div class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-gray-600 peer-checked:bg-hijau-50 peer-checked:text-hijau-700 peer-checked:border-hijau-500 hover:bg-slate-100 transition-colors">
                                1 Bulan
                            </div>
                        </label>
                        <label class="cursor-pointer relative">
                            <input type="radio" name="autoDelete" value="3" class="peer sr-only">
                            <div class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-gray-600 peer-checked:bg-hijau-50 peer-checked:text-hijau-700 peer-checked:border-hijau-500 hover:bg-slate-100 transition-colors">
                                3 Bulan
                            </div>
                        </label>
                        <label class="cursor-pointer relative">
                            <input type="radio" name="autoDelete" value="6" class="peer sr-only">
                            <div class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-gray-600 peer-checked:bg-hijau-50 peer-checked:text-hijau-700 peer-checked:border-hijau-500 hover:bg-slate-100 transition-colors">
                                6 Bulan
                            </div>
                        </label>
                        <label class="cursor-pointer relative">
                            <input type="radio" name="autoDelete" value="never" class="peer sr-only">
                            <div class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-gray-600 peer-checked:bg-hijau-50 peer-checked:text-hijau-700 peer-checked:border-hijau-500 hover:bg-slate-100 transition-colors">
                                Jangan Hapus
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="p-4 font-semibold text-gray-600 text-sm">Waktu</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm">Pengguna</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm">Peran</th>
                                <th class="p-4 font-semibold text-gray-600 text-sm">Aktivitas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($logs as $log)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4 text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }} <br> 
                                        <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }} WIB</span>
                                    </td>
                                    <td class="p-4">
                                        <p class="font-bold text-gray-800">{{ $log->user_name }}</p>
                                    </td>
                                    <td class="p-4">
                                        @if(strtolower($log->role) == 'admin')
                                            <span class="px-2.5 py-1 bg-purple-100 text-purple-700 text-xs font-bold rounded-md uppercase tracking-wider">Admin</span>
                                        @else
                                            <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-md uppercase tracking-wider">ASN</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-sm text-gray-700">
                                        {{ $log->aksi }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12">
                                        <div class="text-gray-300 mb-3"><i class="bi bi-clock-history text-5xl"></i></div>
                                        <p class="text-gray-500 font-medium">Belum ada aktivitas yang terekam.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar Toggle
            const menuToggle = document.getElementById('menu-toggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            function toggleMenu() {
                sidebar.classList.toggle('-translate-x-full');
                sidebarOverlay.classList.toggle('hidden');
            }

            if (menuToggle) menuToggle.addEventListener('click', toggleMenu);
            if (sidebarOverlay) sidebarOverlay.addEventListener('click', toggleMenu);

            // --- LOGIKA PENGHAPUSAN OTOMATIS (BERJALAN DI LATAR BELAKANG) ---
            const autoDeleteOptions = document.querySelectorAll('input[name="autoDelete"]');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Fungsi untuk mengirim sinyal pembersihan ke Server
            function jalankanPembersihanOtomatis(months) {
                if (months === 'never' || !months) return; 

                fetch('{{ url("/admin/log/autoclean") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ months: months })
                })
                .then(response => response.json())
                .then(data => console.log('Auto-clean status:', data.message))
                .catch(error => console.error('Error auto-clean:', error));
            }

            // Saat halaman pertama kali dimuat
            function initAutoDeleteSetting() {
                const savedValue = localStorage.getItem('logAutoDeleteMonths') || '3'; // Default 3 bulan
                const optionToSelect = document.querySelector(`input[name="autoDelete"][value="${savedValue}"]`);
                if (optionToSelect) optionToSelect.checked = true;

                // Langsung perintahkan server untuk sweeping log diam-diam!
                jalankanPembersihanOtomatis(savedValue);
            }

            // Saat Admin mengubah pengaturan Radio Button
            autoDeleteOptions.forEach(radio => {
                radio.addEventListener('change', function(event) {
                    const selectedValue = event.target.value;
                    localStorage.setItem('logAutoDeleteMonths', selectedValue);
                    
                    const labelText = event.target.nextElementSibling.textContent.trim();
                    
                    // Segera kirim ke server dan refresh tabel agar log lamanya langsung menghilang dari layar
                    if (selectedValue !== 'never') {
                        fetch('{{ url("/admin/log/autoclean") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({ months: selectedValue })
                        }).then(() => {
                            alert(`Sistem telah otomatis menghapus log yang lebih tua dari ${labelText}.`);
                            window.location.reload(); // Refresh layar agar bersih
                        });
                    } else {
                        alert('Pengaturan disimpan. Log tidak akan dihapus otomatis.');
                    }
                });
            });
            
            initAutoDeleteSetting(); // Eksekusi saat masuk
        });
    </script>
</body>
</html>