<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - SITI CUTI</title>
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
                <a href="{{ url('/pengajuan') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-journal-plus mr-3"></i> Ajukan Cuti</a>
                <a href="{{ url('/profil') }}" class="flex items-center px-4 py-2.5 text-white bg-hijau-500 rounded-lg font-semibold"><i class="bi bi-person-fill mr-3"></i> Profil Saya</a>
            </nav>
            <div class="p-4 mt-auto">
                <a href="{{ url('/logout') }}" class="link-confirm flex items-center justify-center w-full px-4 py-2.5 text-red-500 bg-red-50 hover:bg-red-100 rounded-lg font-semibold" data-title="Keluar dari Sistem?" data-text="Anda harus login kembali untuk masuk." data-icon="warning">
                    <i class="bi bi-box-arrow-right mr-3"></i> Logout
                </a>
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
                <h1 class="text-3xl font-bold text-gray-800">Profil Saya</h1>
                <p class="text-gray-500 mt-1">Kelola informasi data diri dan keamanan akun Anda.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">
                
                <div class="lg:col-span-1">
                    <div class="h-full bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col text-center">
                        <div class="w-32 h-32 rounded-full bg-gradient-to-br from-hijau-100 to-hijau-200 text-hijau-600 flex items-center justify-center font-bold text-5xl mx-auto mb-4 border-4 border-white shadow-lg shrink-0">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                        <p class="text-sm text-gray-500 font-mono mt-1">{{ $user->nip }}</p>
                        <div>
                            <span class="inline-block mt-3 px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-full border border-blue-100">{{ $user->jabatan }}</span>
                        </div>
                        
                        <div class="mt-auto pt-8 border-t border-slate-100 text-left space-y-3">
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase">Total Masa Kerja</p>
                                <p class="text-sm font-medium text-gray-800">{{ $user->masa_kerja_total }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase">Pangkat / Golongan</p>
                                <p class="text-sm font-medium text-gray-800">{{ $user->pangkat_gol ?? '-' }}</p>
                            </div>
                            <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-100 mt-4">
                                <p class="text-xs text-yellow-700 leading-relaxed"><i class="bi bi-info-circle-fill mr-1"></i> Data Kepegawaian hanya dapat diubah oleh Administrator.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="h-full bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8 flex flex-col">
                        <h3 class="text-lg font-bold text-gray-800 mb-6 pb-4 border-b border-slate-100 shrink-0">Edit Informasi Kontak & Keamanan</h3>
                        
                        <form id="formProfil" action="{{ url('/profil/update') }}" method="POST" class="flex-1 flex flex-col">
                            @csrf
                            <div class="space-y-6">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">NIP (Nomor Induk Pegawai)</label>
                                        <input type="text" value="{{ $user->nip }}" class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-lg text-gray-500 cursor-not-allowed" readonly>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Username Login</label>
                                        <input type="text" value="{{ $user->username }}" class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-lg text-gray-500 cursor-not-allowed" readonly>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Handphone / WhatsApp</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400"><i class="bi bi-telephone-fill"></i></span>
                                        <input type="tel" name="telepon" value="{{ $user->telepon }}" class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400 focus:border-transparent transition-all" placeholder="Contoh: 08123456789">
                                    </div>
                                </div>

                                <div class="p-5 border border-slate-100 bg-slate-50 rounded-xl space-y-4">
                                    <h4 class="text-sm font-bold text-gray-700 flex items-center"><i class="bi bi-key-fill text-hijau-500 mr-2"></i> Ganti Password (Opsional)</h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Password Baru</label>
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="bi bi-shield-lock-fill"></i></span>
                                                <input type="password" name="password" id="password" class="w-full pl-10 pr-10 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400 text-sm" placeholder="Minimal 6 karakter">
                                                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 toggle-password" data-target="password">
                                                    <i class="bi bi-eye-slash-fill"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Konfirmasi Password Baru</label>
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="bi bi-shield-check-fill"></i></span>
                                                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full pl-10 pr-10 py-2 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400 text-sm" placeholder="Ulangi password">
                                                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 toggle-password" data-target="password_confirmation">
                                                    <i class="bi bi-eye-slash-fill"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-2 italic">* Kosongkan kedua kolom di atas jika tidak ingin mengubah password.</p>
                                </div>

                            </div>

                            <div class="mt-auto pt-8 flex justify-end">
                                <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-8 py-3 bg-hijau-500 text-white rounded-lg hover:bg-hijau-600 font-bold shadow-md transition-all flex items-center justify-center gap-2">
                                    <span id="btnText"><i class="bi bi-save-fill mr-1"></i> Simpan Perubahan</span>
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
        <a href="{{ url('/pengajuan') }}" class="flex flex-col items-center justify-center p-3 text-gray-600 hover:text-hijau-500 w-full text-center">
            <i class="bi bi-journal-plus text-xl"></i>
            <span class="text-xs mt-1">Ajukan Cuti</span>
        </a>
        <a href="{{ url('/profil') }}" class="flex flex-col items-center justify-center p-3 text-hijau-500 font-semibold w-full text-center">
            <i class="bi bi-person-fill text-xl"></i>
            <span class="text-xs mt-1">Profil</span>
        </a>
    </nav>

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

            // Fitur Intip Password (Mata)
            const togglePasswords = document.querySelectorAll('.toggle-password');
            togglePasswords.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const inputField = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (inputField.type === 'password') {
                        inputField.type = 'text';
                        icon.classList.remove('bi-eye-slash-fill');
                        icon.classList.add('bi-eye-fill', 'text-hijau-500');
                    } else {
                        inputField.type = 'password';
                        icon.classList.remove('bi-eye-fill', 'text-hijau-500');
                        icon.classList.add('bi-eye-slash-fill');
                    }
                });
            });

            // Efek Loading Saat Disubmit
            const formProfil = document.getElementById('formProfil');
            if (formProfil) {
                formProfil.addEventListener('submit', function() {
                    const btnSubmit = document.getElementById('btnSubmit');
                    const btnText = document.getElementById('btnText');
                    const btnSpinner = document.getElementById('btnSpinner');

                    // Ubah tombol jadi status loading
                    btnSubmit.disabled = true;
                    btnSubmit.classList.add('opacity-75', 'cursor-not-allowed');
                    btnText.innerHTML = 'Menyimpan...';
                    btnSpinner.classList.remove('hidden');
                });
            }
        });
    </script>
    @include('components.notifikasi')
</body>
</html>