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
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'hijau': { '50': '#f0fdf4', '100': '#dcfce7', '200': '#bbf7d0', '300': '#86efac', '400': '#4ade80', '500': '#22c55e', '600': '#16a34a', '700': '#15803d' },
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
                <a href="{{ url('/dashboard') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold">
                    <i class="bi bi-grid-1x2-fill mr-3"></i> Dashboard
                </a>
                <a href="{{ url('/pengajuan') }}" class="flex items-center px-4 py-2.5 text-white bg-hijau-500 rounded-lg font-semibold">
                    <i class="bi bi-journal-plus mr-3"></i> Ajukan Cuti
                </a>
                <a href="{{ url('/profil') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold">
                    <i class="bi bi-person-fill mr-3"></i> Profil Saya
                </a>
            </nav>

            <div class="p-4 mt-auto">
                <a href="{{ url('/') }}" class="flex items-center justify-center w-full px-4 py-2.5 text-red-500 bg-red-50 hover:bg-red-100 rounded-lg font-semibold">
                    <i class="bi bi-box-arrow-right mr-3"></i> Logout
                </a>
            </div>
        </aside>

        <main class="flex-1 p-4 md:p-8 overflow-y-auto pb-24 lg:pb-8">
            <!-- Mobile Header -->
            <header class="lg:hidden flex items-center justify-between mb-8">
                 <div class="flex items-center space-x-3">
                    <div class="bg-hijau-500 text-white p-2.5 rounded-lg shadow-sm"><i class="bi bi-calendar-heart-fill text-xl"></i></div>
                    <div><h1 class="text-lg font-bold text-gray-800">SITI CUTI</h1></div>
                </div>
                <button id="menu-toggle" class="text-2xl text-gray-700 p-2">
                    <i class="bi bi-list"></i>
                </button>
            </header>

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Formulir Pengajuan Cuti</h1>
                <p class="text-gray-500 mt-1">Silakan isi semua kolom yang diperlukan dengan data yang benar.</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
                <form id="formCuti">
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
                                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan / Alasan Cuti</label>
                                <textarea id="keterangan" name="keterangan" rows="3" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" placeholder="Contoh: Keperluan keluarga di luar kota" required></textarea>
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
                        <button type="button" id="batalButton" class="w-full md:w-auto px-6 py-3 bg-slate-100 text-gray-700 font-bold rounded-lg hover:bg-slate-200">
                            Batal
                        </button>
                        <button type="submit" class="w-full md:w-auto px-6 py-3 bg-hijau-500 text-white font-bold rounded-lg shadow-md hover:bg-hijau-600 flex items-center justify-center space-x-2">
                            <i class="bi bi-send-fill"></i>
                            <span>Kirim Pengajuan</span>
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- Bottom Navigation -->
    <nav class="lg:hidden fixed bottom-0 inset-x-0 bg-white border-t border-slate-200 shadow-t-lg flex justify-around">
        <a href="{{ url('/dashboard') }}" class="flex flex-col items-center justify-center p-3 text-gray-600 hover:text-hijau-500 w-full text-center">
            <i class="bi bi-grid-1x2-fill text-xl"></i>
            <span class="text-xs mt-1">Dashboard</span>
        </a>
        <a href="{{ url('/pengajuan') }}" class="flex flex-col items-center justify-center p-3 text-hijau-500 font-semibold w-full text-center">
            <i class="bi bi-journal-plus text-xl"></i>
            <span class="text-xs mt-1">Ajukan Cuti</span>
        </a>
        <a href="{{ url('/profil') }}" class="flex flex-col items-center justify-center p-3 text-gray-600 hover:text-hijau-500 w-full text-center">
            <i class="bi bi-person-fill text-xl"></i>
            <span class="text-xs mt-1">Profil</span>
        </a>
    </nav>

    <!-- Custom Alert/Confirm Modal -->
    <div id="customAlertModal" class="custom-modal-backdrop">
        <div class="custom-modal-content">
            <h4 id="customAlertTitle" class="text-lg font-bold text-gray-800 mb-4">Pemberitahuan</h4>
            <p id="customAlertMessage" class="text-gray-700 mb-6"></p>
            <div class="flex justify-end space-x-3">
                <button id="customAlertCancel" class="px-4 py-2 bg-slate-200 text-gray-700 rounded-lg hover:bg-slate-300 hidden">Batal</button>
                <button id="customAlertOK" class="px-4 py-2 bg-hijau-500 text-white rounded-lg hover:bg-hijau-600">OK</button>
            </div>
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
            if (!loggedInUser || loggedInUser.role !== 'asn') {
                window.location.href="{{ url('/') }}";
                return;
            }

            const sisaCutiData = loggedInUser.sisaCuti || { tahunIni: 12, tahunLalu: 0, diambil: 0 };
            const totalSisaCuti = (sisaCutiData.tahunIni + sisaCutiData.tahunLalu) - sisaCutiData.diambil;

            const jenisCutiSelect = document.getElementById('jenis_cuti');
            const sisaCutiInfo = document.getElementById('sisaCutiInfo');
            const sisaCutiText = document.getElementById('sisaCutiText');
            const tglMulaiInput = document.getElementById('tanggal_mulai');
            const tglSelesaiInput = document.getElementById('tanggal_selesai');
            const lampiranContainer = document.getElementById('lampiran-container');
            const lampiranInput = document.getElementById('lampiran');
            
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowString = tomorrow.toISOString().split('T')[0];
            tglMulaiInput.setAttribute('min', tomorrowString);
            tglSelesaiInput.setAttribute('min', tomorrowString);

            const jenisCutiData = JSON.parse(localStorage.getItem('jenisCuti')) || [];
            jenisCutiData.forEach(cuti => {
                const option = document.createElement('option');
                option.value = cuti.nama;
                option.dataset.wajibLampiran = cuti.wajibLampiran || false;
                option.textContent = cuti.nama;
                jenisCutiSelect.appendChild(option);
            });

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
                    lampiranInput.required = true;
                } else {
                    lampiranContainer.classList.add('hidden');
                    lampiranInput.required = false;
                }
            });

            function hitungHariKerja(tanggalMulai, tanggalSelesai) {
                const hariLiburData = JSON.parse(localStorage.getItem('hariLiburNasional')) || [];
                const daftarHariLibur = new Set(hariLiburData.map(libur => libur.tanggal));

                let jumlahHari = 0;
                let tanggalSekarang = new Date(tanggalMulai);
                let tanggalAkhir = new Date(tanggalSelesai);
                if (isNaN(tanggalSekarang) || isNaN(tanggalAkhir) || tanggalAkhir < tanggalSekarang) return 0;
                
                while (tanggalSekarang <= tanggalAkhir) {
                    const hari = tanggalSekarang.getDay();
                    const tanggalString = tanggalSekarang.toISOString().split('T')[0];
                    
                    if (hari >= 1 && hari <= 5 && !daftarHariLibur.has(tanggalString)) {
                        jumlahHari++;
                    }
                    tanggalSekarang.setDate(tanggalSekarang.getDate() + 1);
                }
                return jumlahHari;
            }

            document.getElementById('formCuti').addEventListener('submit', function(e) {
                e.preventDefault();

                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const selectedStartDate = new Date(tglMulaiInput.value);

                if (selectedStartDate < today) {
                    showAlert('Pengajuan cuti harus dilakukan minimal satu hari sebelum tanggal cuti.');
                    return;
                }

                const allCuti = JSON.parse(localStorage.getItem('pengajuanCuti')) || [];
                const userCuti = allCuti.filter(c => c.asnId === loggedInUser.id && c.status !== 'Ditolak');
                
                const newStartDate = new Date(tglMulaiInput.value);
                const newEndDate = new Date(tglSelesaiInput.value);

                const isOverlap = userCuti.some(cuti => {
                    const [existingStartStr, existingEndStr] = cuti.tanggal.split(' - ');
                    const existingStartDate = new Date(existingStartStr);
                    const existingEndDate = new Date(existingEndStr);
                    return newStartDate <= existingEndDate && newEndDate >= existingStartDate;
                });

                if (isOverlap) {
                    showAlert('Tanggal yang Anda ajukan tumpang tindih dengan pengajuan cuti yang sudah ada.');
                    return;
                }

                const jenisCuti = jenisCutiSelect.value;
                const jumlahHari = hitungHariKerja(tglMulaiInput.value, tglSelesaiInput.value);

                if (jumlahHari <= 0) {
                    showAlert('Durasi cuti tidak valid. Pastikan rentang tanggal mencakup hari kerja dan tidak jatuh pada hari libur.');
                    return;
                }

                if (jenisCuti === 'Cuti Tahunan' && jumlahHari > totalSisaCuti) {
                    showAlert(`Pengajuan gagal: Jumlah hari yang Anda ajukan (${jumlahHari} hari) melebihi sisa cuti tahunan Anda (${totalSisaCuti} hari).`);
                    return;
                }
                
                // Handle file upload
                const file = lampiranInput.files[0];
                if (lampiranInput.required && !file) {
                    showAlert('Anda harus melampirkan dokumen untuk jenis cuti ini.');
                    return;
                }
                
                if (file && file.size > 2 * 1024 * 1024) { // 2MB limit
                    showAlert('Ukuran file tidak boleh melebihi 2MB.');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    const lampiranData = {
                        data: event.target.result,
                        nama: file ? file.name : null,
                        tipe: file ? file.type : null
                    };

                    const newCuti = {
                        id: allCuti.length > 0 ? Math.max(...allCuti.map(c => c.id)) + 1 : 1,
                        asnId: loggedInUser.id,
                        nama: loggedInUser.nama,
                        nip: loggedInUser.nip,
                        jabatan: loggedInUser.jabatan,
                        jenis: jenisCuti,
                        durasi: jumlahHari,
                        tanggal: `${tglMulaiInput.value} - ${tglSelesaiInput.value}`,
                        alasan: document.getElementById('keterangan').value,
                        alamatCuti: document.getElementById('alamat_cuti').value,
                        status: 'Menunggu',
                        lampiran: lampiranData,
                        tanggalPengajuan: new Date().toISOString() // Menyimpan tanggal pengajuan
                    };
                    allCuti.push(newCuti);
                    localStorage.setItem('pengajuanCuti', JSON.stringify(allCuti));

                    showAlert('Pengajuan cuti berhasil dikirim!', () => {
                        addActivityLog(`Mengajukan cuti ${newCuti.jenis} selama ${newCuti.durasi} hari`);
                        window.location.href="{{ url('/dashboard') }}";
                    });
                };

                if (file) {
                    reader.readAsDataURL(file); // Convert file to Base64 string
                } else {
                    reader.onload({ target: { result: null } }); // Proceed without file
                }
            });

            document.getElementById('batalButton').addEventListener('click', () => {
                window.location.href="{{ url('/dashboard') }}";
            });
        });
    </script>

</body>
</html>
