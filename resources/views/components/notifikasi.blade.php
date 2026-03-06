<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. Popup Sukses
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{!! session('success') !!}',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded-2xl shadow-xl border border-slate-100',
                    title: 'text-gray-800 font-bold'
                }
            });
        @endif

        // 2. Popup Error (Validasi Form)
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                text: '{!! $errors->first() !!}',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-2xl shadow-xl border border-slate-100',
                    title: 'text-gray-800 font-bold',
                    // Tombol error pakai warna merah
                    confirmButton: 'bg-red-500 hover:bg-red-600 text-white rounded-lg px-6 py-2.5 font-bold shadow-md focus:outline-none transition-colors'
                }
            });
        @endif

        // 3. Popup Error Kustom (Akses Ditolak)
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Akses Ditolak!',
                text: '{!! session('error') !!}',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-2xl shadow-xl border border-slate-100',
                    title: 'text-gray-800 font-bold',
                    // Tombol error pakai warna merah
                    confirmButton: 'bg-red-500 hover:bg-red-600 text-white rounded-lg px-6 py-2.5 font-bold shadow-md focus:outline-none transition-colors'
                }
            });
        @endif

        // =================================================================
        // FITUR BARU: POPUP PERTANYAAN (KONFIRMASI SWEETALERT)
        // =================================================================

        // A. PENCEGAT FORM (Untuk Setujui, Tolak, Hapus)
        const confirmForms = document.querySelectorAll('.form-confirm');
        confirmForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); 
                
                const title = this.getAttribute('data-title') || 'Apakah Anda Yakin?';
                const text = this.getAttribute('data-text') || 'Tindakan ini tidak dapat dibatalkan!';
                const icon = this.getAttribute('data-icon') || 'warning';
                
                // Ambil warna khusus (misal merah untuk hapus), default Hijau
                const btnClass = this.getAttribute('data-btn-confirm') || 'bg-hijau-500 hover:bg-hijau-600 text-white';

                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true, 
                    customClass: {
                        popup: 'rounded-2xl shadow-xl border border-slate-100',
                        title: 'text-gray-800 font-bold',
                        // Suntikkan warna background (btnClass) ke sini
                        confirmButton: `rounded-lg px-6 py-2.5 font-bold shadow-md ml-3 focus:outline-none transition-colors ${btnClass}`,
                        cancelButton: 'rounded-lg px-6 py-2.5 font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 focus:outline-none transition-colors'
                    },
                    buttonsStyling: false 
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit(); 
                    }
                });
            });
        });

        // B. PENCEGAT LINK (Untuk Logout, Batal, dll)
        const confirmLinks = document.querySelectorAll('.link-confirm');
        confirmLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault(); 
                
                const href = this.getAttribute('href');
                const title = this.getAttribute('data-title') || 'Konfirmasi Tindakan';
                const text = this.getAttribute('data-text') || 'Apakah Anda yakin ingin melanjutkan?';
                const icon = this.getAttribute('data-icon') || 'question';
                
                // Ambil warna khusus (misal merah untuk logout), default Hijau
                const btnClass = this.getAttribute('data-btn-confirm') || 'bg-hijau-500 hover:bg-hijau-600 text-white';

                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-2xl shadow-xl border border-slate-100',
                        title: 'text-gray-800 font-bold',
                        // Suntikkan warna background (btnClass) ke sini
                        confirmButton: `rounded-lg px-6 py-2.5 font-bold shadow-md ml-3 focus:outline-none transition-colors ${btnClass}`,
                        cancelButton: 'rounded-lg px-6 py-2.5 font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 focus:outline-none transition-colors'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href; 
                    }
                });
            });
        });
        
    });
</script>