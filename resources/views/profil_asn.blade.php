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
                <a href="{{ url('/logout') }}" id="logoutButton" class="flex items-center justify-center w-full px-4 py-2.5 text-red-500 bg-red-50 hover:bg-red-100 rounded-lg font-semibold"><i class="bi bi-box-arrow-right mr-3"></i> Logout</a>
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
                <h1 class="text-3xl font-bold text-gray-800">Profil Saya</h1>
                <p class="text-gray-500 mt-1">Kelola informasi pribadi dan akun Anda.</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
                <form id="formProfil">
                    <div class="flex flex-col md:flex-row gap-8 items-start">
                        <div class="w-full md:w-1/3 text-center">
                             <img id="profileImage" src="https://placehold.co/128x128/e2e8f0/64748b?text=Foto" alt="Foto Profil" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                             <input type="file" id="uploadFoto" class="hidden" accept="image/*">
                             <label for="uploadFoto" class="cursor-pointer bg-hijau-500 text-white px-4 py-2 rounded-lg hover:bg-hijau-600 text-sm font-semibold">Ubah Foto</label>
                             <button type="button" id="hapusFoto" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 text-sm font-semibold ml-2">Hapus Foto</button>
                        </div>
                        <div class="w-full md:w-2/3 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div><label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label><input type="text" id="nama_lengkap" class="w-full px-4 py-3 bg-slate-100 border border-slate-300 rounded-lg" readonly></div>
                            <div><label for="nip" class="block text-sm font-medium text-gray-700 mb-1">NIP</label><input type="text" id="nip" class="w-full px-4 py-3 bg-slate-100 border border-slate-300 rounded-lg" readonly></div>
                            <div><label for="jabatan" class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label><input type="text" id="jabatan" class="w-full px-4 py-3 bg-slate-100 border border-slate-300 rounded-lg" readonly></div>
                            <div><label for="pangkat_gol" class="block text-sm font-medium text-gray-700 mb-1">Pangkat / Golongan</label><input type="text" id="pangkat_gol" class="w-full px-4 py-3 bg-slate-100 border border-slate-300 rounded-lg" readonly></div>
                            <div><label for="telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label><input type="tel" id="telepon" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" placeholder="Masukkan nomor telepon Anda"></div>
                            <div><label for="masa_kerja" class="block text-sm font-medium text-gray-700 mb-1">Masa Kerja</label><input type="text" id="masa_kerja" class="w-full px-4 py-3 bg-slate-100 border border-slate-300 rounded-lg" readonly></div>
                            <div><label for="atasan1" class="block text-sm font-medium text-gray-700 mb-1">Atasan Langsung 1</label><input type="text" id="atasan1" class="w-full px-4 py-3 bg-slate-100 border border-slate-300 rounded-lg" readonly></div>
                            <div><label for="atasan2" class="block text-sm font-medium text-gray-700 mb-1">Atasan Langsung 2</label><input type="text" id="atasan2" class="w-full px-4 py-3 bg-slate-100 border border-slate-300 rounded-lg" readonly></div>
                            <div><label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label><input type="text" id="username" class="w-full px-4 py-3 bg-slate-100 border border-slate-300 rounded-lg" readonly></div>
                            <div class="md:col-span-2"><label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label><input type="password" id="password" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400" placeholder="Kosongkan jika tidak ingin mengubah"></div>
                        </div>
                    </div>
                    <div class="mt-8 pt-6 border-t border-slate-200 flex justify-end">
                        <button type="submit" class="w-full md:w-auto px-6 py-3 bg-hijau-500 text-white font-bold rounded-lg shadow-md hover:bg-hijau-600">Simpan Perubahan</button>
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
        <a href="{{ url('/pengajuan') }}" class="flex flex-col items-center justify-center p-3 text-gray-600 hover:text-hijau-500 w-full text-center">
            <i class="bi bi-journal-plus text-xl"></i>
            <span class="text-xs mt-1">Ajukan Cuti</span>
        </a>
        <a href="{{ url('/profil') }}" class="flex flex-col items-center justify-center p-3 text-hijau-500 font-semibold w-full text-center">
            <i class="bi bi-person-fill text-xl"></i>
            <span class="text-xs mt-1">Profil</span>
        </a>
    </nav>

    <div id="customAlertModal" class="custom-modal-backdrop">
        <div class="custom-modal-content"><h4 id="customAlertTitle" class="text-lg font-bold text-gray-800 mb-4"></h4><p id="customAlertMessage" class="text-gray-700 mb-6"></p><div class="flex justify-end space-x-3"><button id="customAlertCancel" class="px-4 py-2 bg-slate-200 text-gray-700 rounded-lg hover:bg-slate-300 hidden"></button><button id="customAlertOK" class="px-4 py-2 bg-hijau-500 text-white rounded-lg hover:bg-hijau-600"></button></div></div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            const dataAtasan = JSON.parse(localStorage.getItem('dataAtasan')) || [];
            const atasan1 = dataAtasan.find(a => a.nip === loggedInUser.atasan1);
            const atasan2 = dataAtasan.find(a => a.nip === loggedInUser.atasan2);

            const profileImage = document.getElementById('profileImage');
            const uploadFotoInput = document.getElementById('uploadFoto');
            const hapusFotoBtn = document.getElementById('hapusFoto');
            let newProfilePic = loggedInUser.profilePicture || null;

            function hitungMasaKerja(tmtString, masaKerjaAwal) {
                if (!tmtString) return 'Data TMT tidak ada';
                const tmt = new Date(tmtString);
                const targetDate = new Date('2025-08-01');

                if (isNaN(tmt.getTime())) return 'Data TMT tidak valid';

                const initialYears = masaKerjaAwal ? parseInt(masaKerjaAwal.tahun, 10) || 0 : 0;
                const initialMonths = masaKerjaAwal ? parseInt(masaKerjaAwal.bulan, 10) || 0 : 0;

                // Calculate total months difference, ignoring day part
                let diffTotalMonths = (targetDate.getFullYear() - tmt.getFullYear()) * 12 + (targetDate.getMonth() - tmt.getMonth());

                const initialTotalMonths = (initialYears * 12) + initialMonths;
                
                // Add 1 month to align with Excel's DATEDIF behavior for this specific context.
                const finalTotalMonths = initialTotalMonths + diffTotalMonths + 1;

                const finalYears = Math.floor(finalTotalMonths / 12);
                const finalMonths = finalTotalMonths % 12;

                return `${finalYears} tahun, ${finalMonths} bulan`;
            }


            document.getElementById('nama_lengkap').value = loggedInUser.nama;
            document.getElementById('nip').value = loggedInUser.nip;
            document.getElementById('jabatan').value = loggedInUser.jabatan;
            document.getElementById('pangkat_gol').value = loggedInUser.pangkat_gol || '-';
            document.getElementById('telepon').value = loggedInUser.telepon || '';
            document.getElementById('masa_kerja').value = hitungMasaKerja(loggedInUser.tmtPangkat, loggedInUser.masaKerjaAwal);
            document.getElementById('atasan1').value = atasan1 ? atasan1.nama : '-';
            document.getElementById('atasan2').value = atasan2 ? atasan2.nama : '-';
            document.getElementById('username').value = loggedInUser.username;
            if(newProfilePic) {
                profileImage.src = newProfilePic;
            }

            uploadFotoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        newProfilePic = event.target.result;
                        profileImage.src = newProfilePic;
                    }
                    reader.readAsDataURL(file);
                }
            });

            hapusFotoBtn.addEventListener('click', function() {
                showConfirm('Anda yakin ingin menghapus foto profil?', (result) => {
                    if (result) {
                        newProfilePic = null;
                        profileImage.src = 'https://placehold.co/128x128/e2e8f0/64748b?text=Foto';
                        showAlert('Foto profil berhasil dihapus!');
                    }
                });
            });

            document.getElementById('formProfil').addEventListener('submit', function(e) {
                e.preventDefault();
                const allAsn = JSON.parse(localStorage.getItem('dataAsn')) || [];
                const userIndex = allAsn.findIndex(u => u.id === loggedInUser.id);
                
                if (userIndex !== -1) {
                    const newPassword = document.getElementById('password').value;
                    const newTelepon = document.getElementById('telepon').value;
                    let logMessage = 'Memperbarui profil';

                    if (newPassword) {
                        allAsn[userIndex].password = newPassword;
                        logMessage += ' (termasuk password)';
                    }
                    allAsn[userIndex].telepon = newTelepon;
                    allAsn[userIndex].profilePicture = newProfilePic;
                    
                    localStorage.setItem('dataAsn', JSON.stringify(allAsn));
                    localStorage.setItem('loggedInUser', JSON.stringify(allAsn[userIndex]));
                    showAlert('Profil berhasil diperbarui!');
                    addActivityLog(logMessage);
                }
            });

            document.getElementById('logoutButton').addEventListener('click', function() {
                addActivityLog(`Logout`);
                localStorage.removeItem('loggedInUser');
            });
        });
    </script>
</body>
</html>
