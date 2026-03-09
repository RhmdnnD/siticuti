<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .swal2-popup.siticuti-popup {
        border-radius: 1.5rem !important; /* Membuat sudut lebih bulat (24px) */
        padding: 2rem 1.5rem 1.5rem 1.5rem !important; /* Padding seimbang */
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15) !important; /* Shadow lebih lembut */
    }
    .swal2-title.siticuti-title {
        font-size: 1.5rem !important; /* Ukuran judul lebih proporsional */
        color: #1e293b !important; /* Slate-800 */
        margin-bottom: 0.5rem !important;
    }
    .swal2-html-container.siticuti-text {
        font-size: 0.95rem !important;
        color: #64748b !important; /* Slate-500 */
        line-height: 1.5 !important;
    }
    .swal2-icon {
        margin-top: 0 !important;
        margin-bottom: 1.5rem !important;
        border-width: 3px !important; /* Border ikon lebih tipis */
    }
    /* Memperhalus tampilan tombol */
    .swal2-actions {
        margin-top: 1.5rem !important;
        gap: 0.75rem !important; /* Jarak antar tombol yang pas */
        width: 100% !important;
    }
    .siticuti-btn-confirm {
        flex: 1; /* Membuat tombol memenuhi ruang */
        margin: 0 !important;
    }
    .siticuti-btn-cancel {
        flex: 1; /* Membuat tombol memenuhi ruang */
        margin: 0 !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. Popup Sukses (Berhasil Simpan/Update)
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{!! session('success') !!}',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                customClass: {
                    popup: 'siticuti-popup border border-slate-100',
                    title: 'siticuti-title font-bold',
                    htmlContainer: 'siticuti-text font-medium'
                }
            });
        @endif

        // 2. Popup Error (Validasi Form)
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Periksa Kembali!',
                text: '{!! $errors->first() !!}',
                buttonsStyling: false,
                customClass: {
                    popup: 'siticuti-popup border border-slate-100',
                    title: 'siticuti-title font-bold',
                    htmlContainer: 'siticuti-text font-medium',
                    actions: 'w-full px-4',
                    confirmButton: 'siticuti-btn-confirm bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 rounded-xl px-6 py-3 font-semibold transition-colors w-full'
                }
            });
        @endif

        // 3. Popup Error Kustom (Akses Ditolak/Gagal)
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{!! session('error') !!}',
                buttonsStyling: false,
                customClass: {
                    popup: 'siticuti-popup border border-slate-100',
                    title: 'siticuti-title font-bold',
                    htmlContainer: 'siticuti-text font-medium',
                    actions: 'w-full px-4',
                    confirmButton: 'siticuti-btn-confirm bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 rounded-xl px-6 py-3 font-semibold transition-colors w-full'
                }
            });
        @endif

        // =================================================================
        // PENCEGAT AKSI FORM (Untuk Tolak, Setujui, dll)
        // =================================================================
        const confirmForms = document.querySelectorAll('.form-confirm');
        confirmForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); 
                
                const title = this.getAttribute('data-title') || 'Apakah Anda Yakin?';
                const text = this.getAttribute('data-text') || 'Tindakan ini tidak dapat dibatalkan.';
                const icon = this.getAttribute('data-icon') || 'warning';
                
                // Ambil warna khusus, ubah gaya Tailwind-nya menjadi lebih "App-like"
                const btnClass = this.getAttribute('data-btn-confirm') || 'bg-hijau-500 hover:bg-hijau-600 text-white';

                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true, 
                    customClass: {
                        popup: 'siticuti-popup border border-slate-100',
                        title: 'siticuti-title font-bold',
                        htmlContainer: 'siticuti-text font-medium',
                        actions: 'px-2',
                        confirmButton: `siticuti-btn-confirm rounded-xl px-4 py-3 font-bold shadow-sm focus:outline-none transition-colors ${btnClass}`,
                        cancelButton: 'siticuti-btn-cancel rounded-xl px-4 py-3 font-bold bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 focus:outline-none transition-colors'
                    },
                    buttonsStyling: false 
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit(); 
                    }
                });
            });
        });

        // =================================================================
        // PENCEGAT LINK (Untuk Logout, Hapus, Batal, dll)
        // =================================================================
        const confirmLinks = document.querySelectorAll('.link-confirm');
        confirmLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault(); 
                
                const href = this.getAttribute('href');
                const title = this.getAttribute('data-title') || 'Konfirmasi Tindakan';
                const text = this.getAttribute('data-text') || 'Apakah Anda yakin ingin melanjutkan?';
                const icon = this.getAttribute('data-icon') || 'question';
                
                const btnClass = this.getAttribute('data-btn-confirm') || 'bg-hijau-500 hover:bg-hijau-600 text-white';

                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'siticuti-popup border border-slate-100',
                        title: 'siticuti-title font-bold',
                        htmlContainer: 'siticuti-text font-medium',
                        actions: 'px-2',
                        confirmButton: `siticuti-btn-confirm rounded-xl px-4 py-3 font-bold shadow-sm focus:outline-none transition-colors ${btnClass}`,
                        cancelButton: 'siticuti-btn-cancel rounded-xl px-4 py-3 font-bold bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 focus:outline-none transition-colors'
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