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
                    <div class="bg-hijau-500 text-white p-2.5 rounded-lg shadow-sm"><i class="bi bi-calendar-heart-fill text-xl"></i></div>
                    <div><h1 class="text-lg font-bold text-gray-800">SITI CUTI</h1><p class="text-xs text-gray-500">DLH Tanjungpinang</p></div>
                </div>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ url('/dashboard') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-grid-1x2-fill mr-3"></i> Dashboard</a>
                <a href="{{ url('/pengajuan') }}" class="flex items-center px-4 py-2.5 text-white bg-hijau-500 rounded-lg font-semibold"><i class="bi bi-journal-plus mr-3"></i> Ajukan Cuti</a>
                <a href="{{ url('/profil') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-person-fill mr-3"></i> Profil Saya</a>
            </nav>
            <div class="p-4 mt-auto">
                <a href="{{ url('/logout') }}" onclick="return confirm('Yakin ingin keluar?')" class="flex items-center justify-center w-full px-4 py-2.5 text-red-500 bg-red-50 hover:bg-red-100 rounded-lg font-semibold"><i class="bi bi-box-arrow-right mr-3"></i> Logout</a>
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

            <div class="max-w-4xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-800">Form Pengajuan Cuti</h1>
                    <p class="text-gray-500 mt-1">Silakan lengkapi data di bawah ini untuk mengajukan cuti.</p>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg font-semibold flex items-center">
                        <i class="bi bi-exclamation-triangle-fill mr-2"></i> {{ $errors->first() }}
                    </div>
                @endif

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 md:p-8">
                        <form id="formPengajuan" action="{{ url('/pengajuan') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Cuti <span class="text-red-500">*</span></label>
                                <select id="jenis_cuti" name="jenis_cuti" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-hijau-400" required>
                                    <option value="">-- Pilih Jenis Cuti --</option>
                                    @foreach($jenisCuti as $jc)
                                        <option value="{{ $jc->nama }}" data-wajib-lampiran="{{ $jc->wajib_lampiran ? 'true' : 'false' }}" {{ old('jenis_cuti') == $jc->nama ? 'selected' : '' }}>
                                            {{ $jc->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                
                                <div id="sisaCutiInfo" class="mt-3 p-3 bg-blue-50 border border-blue-100 rounded-lg text-sm text-blue-800 hidden flex items-center">
                                    <i class="bi bi-info-circle-fill mr-2 text-blue-500"></i>
                                    <span>Sisa cuti tahunan Anda: <strong id="sisaCutiText">{{ $totalSisaCuti }}</strong> Hari.</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-hijau-400" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-hijau-400" required>
                                </div>
                            </div>

                            <div id="durasiInfo" class="mb-6 p-3 bg-slate-100 rounded-lg text-sm text-gray-700 hidden font-medium text-center border border-slate-200">
                                Total lama cuti: <span id="durasiText" class="font-bold text-hijau-600 text-lg">0</span> Hari
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Cuti <span class="text-red-500">*</span></label>
                                <textarea name="alasan" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-hijau-400" placeholder="Jelaskan alasan pengajuan cuti Anda..." required>{{ old('alasan') }}</textarea>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Selama Cuti <span class="text-red-500">*</span></label>
                                <textarea name="alamat" rows="2" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-hijau-400" placeholder="Tuliskan alamat lengkap Anda selama menjalankan cuti..." required>{{ old('alamat') }}</textarea>
                            </div>

                            <div id="lampiran-container" class="mb-8 hidden p-4 border border-yellow-200 bg-yellow-50 rounded-lg">
                                <label class="block text-sm font-bold text-yellow-800 mb-2"><i class="bi bi-paperclip"></i> Lampiran Pendukung <span class="text-red-500">*</span></label>
                                <p class="text-xs text-yellow-700 mb-3">Jenis cuti ini mewajibkan Anda untuk mengunggah dokumen bukti (Misal: Surat Keterangan Dokter).</p>
                                <input type="file" id="lampiran" name="lampiran" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-100 file:text-yellow-700 hover:file:bg-yellow-200 transition-colors">
                                <p class="text-xs text-gray-400 mt-2">Format: PDF, JPG, PNG. Maks: 2MB.</p>
                            </div>

                            <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-200">
                                <a href="{{ url('/dashboard') }}" class="px-6 py-3 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 font-semibold transition-colors">Batal</a>
                                <button type="submit" id="btnSubmit" class="px-8 py-3 bg-hijau-500 text-white rounded-lg hover:bg-hijau-600 font-bold shadow-md transition-all flex items-center justify-center gap-2">
                                    <span id="btnText"><i class="bi bi-send-fill mr-1"></i> Kirim Pengajuan</span>
                                    <i id="btnSpinner" class="bi bi-arrow-repeat animate-spin hidden"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <nav class="lg:hidden fixed bottom-0 inset-x-0 bg-white border-t border-slate-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] flex justify-around z-40">
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

            // Variabel Form
            const formPengajuan = document.getElementById('formPengajuan');
            const jenisCutiSelect = document.getElementById('jenis_cuti');
            const sisaCutiInfo = document.getElementById('sisaCutiInfo');
            const tglMulaiInput = document.getElementById('tanggal_mulai');
            const tglSelesaiInput = document.getElementById('tanggal_selesai');
            const durasiInfo = document.getElementById('durasiInfo');
            const durasiText = document.getElementById('durasiText');
            const lampiranContainer = document.getElementById('lampiran-container');
            const lampiranInput = document.getElementById('lampiran');
            
            // 1. Batasi Tanggal Minimal (Besok)
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowString = tomorrow.toISOString().split('T')[0];
            tglMulaiInput.setAttribute('min', tomorrowString);
            
            tglMulaiInput.addEventListener('change', function() {
                // Tanggal selesai minimal sama dengan tanggal mulai
                tglSelesaiInput.setAttribute('min', this.value);
                if(tglSelesaiInput.value && tglSelesaiInput.value < this.value) {
                    tglSelesaiInput.value = this.value;
                }
                hitungDurasi();
            });

            tglSelesaiInput.addEventListener('change', hitungDurasi);

            // Ambil daftar libur dari Laravel Controller
            const daftarLibur = @json($daftarLibur ?? []);

            // 2. Fungsi Hitung Durasi Realtime (Smart Calculator)
            function hitungDurasi() {
                if (tglMulaiInput.value && tglSelesaiInput.value) {
                    const start = new Date(tglMulaiInput.value);
                    const end = new Date(tglSelesaiInput.value);
                    
                    // Jika tanggal selesai lebih kecil dari tanggal mulai
                    if (end < start) {
                        durasiInfo.classList.add('hidden');
                        return;
                    }

                    let durasi = 0;
                    let currentDate = new Date(start);

                    // Looping setiap hari dari tanggal mulai sampai selesai
                    while (currentDate <= end) {
                        const dayOfWeek = currentDate.getDay(); // 0 = Minggu, 6 = Sabtu
                        
                        // Format tanggal ke YYYY-MM-DD untuk dicocokkan dengan daftar libur
                        const year = currentDate.getFullYear();
                        const month = String(currentDate.getMonth() + 1).padStart(2, '0');
                        const day = String(currentDate.getDate()).padStart(2, '0');
                        const dateString = `${year}-${month}-${day}`;

                        // JIKA BUKAN Sabtu (6), BUKAN Minggu (0), DAN BUKAN Hari Libur Nasional
                        if (dayOfWeek !== 0 && dayOfWeek !== 6 && !daftarLibur.includes(dateString)) {
                            durasi++;
                        }

                        // Lanjut ke hari berikutnya
                        currentDate.setDate(currentDate.getDate() + 1);
                    }
                    
                    // Reset kelas tampilan
                    durasiInfo.className = 'mb-6 p-3 rounded-lg text-sm font-medium text-center border';
                    
                    // Jika user pilih full tanggal merah / weekend
                    if (durasi === 0) {
                        durasiInfo.classList.add('bg-red-50', 'text-red-600', 'border-red-200');
                        durasiInfo.innerHTML = `<i class="bi bi-exclamation-triangle-fill mr-1"></i> Rentang tanggal yang dipilih hanya berisi hari libur / akhir pekan.`;
                    } else {
                        durasiInfo.classList.add('bg-slate-100', 'text-gray-700', 'border-slate-200');
                        durasiInfo.innerHTML = `Total cuti kerja yang dihitung: <span id="durasiText" class="font-bold text-hijau-600 text-lg">${durasi}</span> Hari`;
                    }
                    
                    durasiInfo.classList.remove('hidden');
                } else {
                    durasiInfo.classList.add('hidden');
                }
            }

            // 3. Logika Pilih Jenis Cuti
            jenisCutiSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const wajibLampiran = selectedOption.dataset.wajibLampiran === 'true';

                // Tampilkan info Sisa Cuti jika "Cuti Tahunan"
                if (this.value === 'Cuti Tahunan') {
                    sisaCutiInfo.classList.remove('hidden');
                } else {
                    sisaCutiInfo.classList.add('hidden');
                }

                // Tampilkan kolom Lampiran jika wajib
                if (wajibLampiran) {
                    lampiranContainer.classList.remove('hidden');
                    lampiranInput.setAttribute('required', 'required');
                } else {
                    lampiranContainer.classList.add('hidden');
                    lampiranInput.removeAttribute('required');
                }
            });

            // Jalankan event change sekali untuk antisipasi nilai "old()" jika form gagal submit (validasi error)
            if(jenisCutiSelect.value) {
                jenisCutiSelect.dispatchEvent(new Event('change'));
            }

            // 4. Efek Loading Submit
            formPengajuan.addEventListener('submit', function() {
                const btnSubmit = document.getElementById('btnSubmit');
                const btnText = document.getElementById('btnText');
                const btnSpinner = document.getElementById('btnSpinner');

                btnSubmit.classList.add('opacity-75', 'cursor-not-allowed');
                btnText.innerHTML = 'Memproses...';
                btnSpinner.classList.remove('hidden');
            });
        });
    </script>
</body>
</html>