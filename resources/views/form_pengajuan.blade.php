<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengajuan Cuti - SITI CUTI</title>
    
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
                    <div class="bg-hijau-500 text-white p-2.5 rounded-lg shadow-sm">
                        <i class="bi bi-calendar-heart-fill text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-800">SITI CUTI</h1>
                        <p class="text-xs text-gray-500">DLH Tanjungpinang</p>
                    </div>
                </div>
            </div>
            
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ url('/dashboard') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-grid-1x2-fill mr-3"></i> Dashboard</a>
                <a href="{{ url('/pengajuan') }}" class="flex items-center px-4 py-2.5 text-white bg-hijau-500 rounded-lg font-semibold"><i class="bi bi-journal-plus mr-3"></i> Ajukan Cuti</a>
                <a href="{{ url('/profil') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-person-fill mr-3"></i> Profil Saya</a>
            </nav>

            <div class="p-4 mt-auto">
                <a href="{{ url('/logout') }}" class="flex items-center justify-center w-full px-4 py-2.5 text-red-500 bg-red-50 hover:bg-red-100 rounded-lg font-semibold"><i class="bi bi-box-arrow-right mr-3"></i> Logout</a>
            </div>
        </aside>

        <main class="flex-1 p-4 md:p-8 overflow-y-auto pb-24 lg:pb-8">
            <header class="lg:hidden flex items-center justify-between mb-8">
                 <div class="flex items-center space-x-3">
                    <div class="bg-hijau-500 text-white p-2.5 rounded-lg shadow-sm"><i class="bi bi-calendar-heart-fill text-xl"></i></div>
                    <div><h1 class="text-lg font-bold text-gray-800">SITI CUTI</h1></div>
                </div>
                <button id="menu-toggle" class="text-2xl text-gray-700 p-2"><i class="bi bi-list"></i></button>
            </header>

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Formulir Pengajuan Cuti</h1>
                <p class="text-gray-500 mt-1">Silakan isi semua kolom yang diperlukan dengan data yang benar.</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
                <form action="{{ url('/pengajuan') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-6">
                            <div>
                                <label for="jenis_cuti" class="block text-sm font-medium text-gray-700 mb-1">Jenis Cuti</label>
                                <select id="jenis_cuti" name="jenis_cuti" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" required>
                                    <option value="">Pilih Jenis Cuti</option>
                                </select>
                            </div>
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai Cuti</label>
                                <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" required>
                            </div>
                             <div>
                                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai Cuti</label>
                                <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" required>
                            </div>
                             <div id="sisaCutiInfo" class="hidden text-sm text-blue-600 bg-blue-50 p-4 rounded-lg">
                                Sisa cuti tahunan Anda: <span id="sisaCutiText" class="font-bold"></span> hari.
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label for="alasan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan / Alasan Cuti</label>
                                <textarea id="alasan" name="alasan" rows="3" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" placeholder="Contoh: Keperluan keluarga di luar kota" required></textarea>
                            </div>
                            <div>
                                <label for="alamat_cuti" class="block text-sm font-medium text-gray-700 mb-1">Alamat Selama Menjalankan Cuti</label>
                                <textarea id="alamat_cuti" name="alamat_cuti" rows="3" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" placeholder="Contoh: Jl. Merdeka No. 10, Jakarta" required></textarea>
                            </div>
                            <div id="lampiran-container" class="hidden">
                                <label for="lampiran" class="block text-sm font-medium text-gray-700 mb-1">Lampiran Dokumen</label>
                                <input type="file" id="lampiran" name="lampiran" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-hijau-50 file:text-hijau-700 hover:file:bg-hijau-100">
                                <p class="mt-1 text-xs text-gray-500">File: PDF, JPG, PNG. Maks: 2MB.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-200 flex flex-col md:flex-row items-center justify-end gap-4">
                        <a href="{{ url('/dashboard') }}" class="w-full md:w-auto px-6 py-3 bg-slate-100 text-gray-700 font-bold rounded-lg hover:bg-slate-200 text-center">
                            Batal
                        </a>
                        <button type="submit" class="w-full md:w-auto px-6 py-3 bg-hijau-500 text-white font-bold rounded-lg shadow-md hover:bg-hijau-600 flex items-center justify-center space-x-2">
                            <i class="bi bi-send-fill"></i>
                            <span>Kirim Pengajuan</span>
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <nav class="lg:hidden fixed bottom-0 inset-x-0 bg-white border-t border-slate-200 shadow-t-lg flex justify-around">
        <a href="{{ url('/dashboard') }}" class="flex flex-col items-center justify-center p-3 text-gray-600 hover:text-hijau-500 w-full text-center">
            <i class="bi bi-grid-1x2-fill text-xl"></i><span class="text-xs mt-1">Dashboard</span>
        </a>
        <a href="{{ url('/pengajuan') }}" class="flex flex-col items-center justify-center p-3 text-hijau-500 font-semibold w-full text-center">
            <i class="bi bi-journal-plus text-xl"></i><span class="text-xs mt-1">Ajukan Cuti</span>
        </a>
        <a href="{{ url('/profil') }}" class="flex flex-col items-center justify-center p-3 text-gray-600 hover:text-hijau-500 w-full text-center">
            <i class="bi bi-person-fill text-xl"></i><span class="text-xs mt-1">Profil</span>
        </a>
    </nav>

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

            // Jembatan Data Laravel ke JS (Agar sisa cuti bisa terbaca)
            const laravelUser = @json(auth()->user());
            if (laravelUser) {
                localStorage.setItem('loggedInUser', JSON.stringify({
                    id: laravelUser.id,
                    nama: laravelUser.name,
                    nip: laravelUser.nip,
                    role: laravelUser.role,
                    jabatan: laravelUser.jabatan,
                    sisaCuti: { 
                        tahunIni: laravelUser.sisa_cuti_tahun_ini, 
                        tahunLalu: laravelUser.sisa_cuti_tahun_lalu, 
                        diambil: laravelUser.cuti_diambil 
                    }
                }));
            }
            const loggedInUser = JSON.parse(localStorage.getItem('loggedInUser'));

            // Tampilkan Sisa Cuti
            const sisaCutiData = loggedInUser.sisaCuti || { tahunIni: 12, tahunLalu: 0, diambil: 0 };
            const totalSisaCuti = (sisaCutiData.tahunIni + sisaCutiData.tahunLalu) - sisaCutiData.diambil;

            const jenisCutiSelect = document.getElementById('jenis_cuti');
            const sisaCutiInfo = document.getElementById('sisaCutiInfo');
            const sisaCutiText = document.getElementById('sisaCutiText');
            const tglMulaiInput = document.getElementById('tanggal_mulai');
            const tglSelesaiInput = document.getElementById('tanggal_selesai');
            const lampiranContainer = document.getElementById('lampiran-container');
            const lampiranInput = document.getElementById('lampiran');
            
            // Batasi tanggal minimal besok
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowString = tomorrow.toISOString().split('T')[0];
            tglMulaiInput.setAttribute('min', tomorrowString);
            tglSelesaiInput.setAttribute('min', tomorrowString);

            // Ambil opsi jenis cuti dari localStorage (karena Anda belum memindahkannya ke database)
            const jenisCutiData = JSON.parse(localStorage.getItem('jenisCuti')) || [];
            jenisCutiData.forEach(cuti => {
                const option = document.createElement('option');
                option.value = cuti.nama;
                option.dataset.wajibLampiran = cuti.wajibLampiran || false;
                option.textContent = cuti.nama;
                jenisCutiSelect.appendChild(option);
            });

            // Logika UI saat ganti jenis cuti
            jenisCutiSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const wajibLampiran = selectedOption.dataset.wajibLampiran === 'true';

                if (this.value === 'Cuti Tahunan') {
                    sisaCutiText.textContent = totalSisaCuti;
                    sisaCutiInfo.classList.remove('hidden');
                } else {
                    sisaCutiInfo.classList.add('hidden');
                }

                if (wajibLampiran) {
                    lampiranContainer.classList.remove('hidden');
                } else {
                    lampiranContainer.classList.add('hidden');
                }
            });
            
            // Script simpan manual (e.preventDefault) DIHAPUS agar bisa dikirim langsung ke Controller Laravel
        });
    </script>
</body>
</html>