<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SITI CUTI</title>
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
                        'hijau': { '500': '#22c55e', '600': '#16a34a' },
                    }
                }
            }
        }
    </script>
    <style>
        /* Styles for custom modal */
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
<body class="font-sans bg-slate-50 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-sm p-8 space-y-8 bg-white rounded-2xl shadow-lg">
        <div class="text-center">
            <div class="flex justify-center mb-4">
                <div class="bg-hijau-500 text-white p-4 rounded-full shadow-md">
                    <i class="bi bi-calendar-heart-fill text-4xl"></i>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">SITI</h1>
            <p class="text-gray-500">Sistem Pengajuan Cuti</p>
            <p class="text-gray-500">DLH Kota Tanjungpinang</p>
        </div>

        <form id="loginForm" class="space-y-6">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <div class="mt-1">
                    <input id="username" name="username" type="text" required class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400">
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" required class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-hijau-400">
                </div>
            </div>
            
            <div id="errorMessage" class="hidden text-red-500 text-sm text-center">Username atau password salah!</div>

            <div>
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-lg font-bold text-white bg-hijau-500 hover:bg-hijau-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-hijau-500">
                    Login
                </button>
            </div>
        </form>
    </div>

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
            // Bersihkan data sesi lama
            localStorage.removeItem('loggedInUser');
            
            const loginForm = document.getElementById('loginForm');
            const errorMessage = document.getElementById('errorMessage');

            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const username = document.getElementById('username').value;
                const password = document.getElementById('password').value;
                
                // Sembunyikan pesan error sebelumnya
                errorMessage.classList.add('hidden');

                // Panggil API Login PHP
                fetch('api/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        username: username,
                        password: password
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Simpan data user ke localStorage (Hanya untuk sesi browser, bukan database utama)
                        localStorage.setItem('loggedInUser', JSON.stringify(data.data));
                        
                        // Redirect sesuai role
                        if (data.data.role === 'admin') {
                            window.location.href = 'admin.html';
                        } else {
                            window.location.href = 'index.html';
                        }
                    } else {
                        // Tampilkan pesan error
                        errorMessage.textContent = data.message;
                        errorMessage.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    errorMessage.textContent = 'Terjadi kesalahan koneksi server.';
                    errorMessage.classList.remove('hidden');
                });
            });
        });
    </script>

</body>
</html>
