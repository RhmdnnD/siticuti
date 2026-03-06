<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Cuti Online</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

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
        
        #pdfPreviewModal .custom-modal-content {
            max-width: 800px;
            height: 90vh;
            padding: 1rem;
            display: flex;
            flex-direction: column;
        }
        #pdf-viewer {
            flex-grow: 1;
            border-radius: 0.5rem;
        }
    </style>
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
                <a href="{{ url('/dashboard') }}" class="flex items-center px-4 py-2.5 text-white bg-hijau-500 rounded-lg font-semibold"><i class="bi bi-grid-1x2-fill mr-3"></i> Dashboard</a>
                <a href="{{ url('/pengajuan') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-journal-plus mr-3"></i> Ajukan Cuti</a>
                <a href="{{ url('/profil') }}" class="flex items-center px-4 py-2.5 text-gray-600 hover:bg-slate-100 rounded-lg font-semibold"><i class="bi bi-person-fill mr-3"></i> Profil Saya</a>
            </nav>
            <div class="p-4 mt-auto">
                <a href="{{ url('/logout') }}" id="logoutButton" class="flex items-center justify-center w-full px-4 py-2.5 text-red-500 bg-red-50 hover:bg-red-100 rounded-lg font-semibold"><i class="bi bi-box-arrow-right mr-3"></i> Logout</a>
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

            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Selamat Datang, {{ explode(' ', $user->name)[0] }}!</h1>
                <p class="text-gray-500 mt-1">Berikut adalah ringkasan dan aksi cepat untuk manajemen cuti Anda.</p>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-hijau-50 border border-hijau-200 text-hijau-700 rounded-lg font-semibold flex items-center">
                    <i class="bi bi-check-circle-fill mr-2"></i> {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg font-semibold flex items-center">
                    <i class="bi bi-exclamation-triangle-fill mr-2"></i> {{ $errors->first() }}
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <div class="xl:col-span-2 space-y-8">
                    <div class="bg-gradient-to-br from-hijau-500 to-hijau-600 text-white p-8 rounded-2xl shadow-lg flex items-center justify-between relative overflow-hidden">
                        <div class="z-10">
                            <h3 class="font-semibold text-lg opacity-90">Sisa Cuti Tahunan Anda</h3>
                            <p class="text-6xl font-extrabold mt-2">{{ $totalSisaCuti }} <span class="text-3xl font-semibold">Hari</span></p>
                            <p class="opacity-80 mt-2">Dari total 12 hari hak cuti tahunan.</p>
                        </div>
                        <i class="bi bi-calendar2-check-fill text-9xl opacity-20 transform -rotate-12 absolute -right-6 -bottom-6"></i>
                    </div>
                    
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Riwayat Pengajuan Terbaru</h3>
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                            <ul id="riwayatCuti" class="divide-y divide-slate-100">
                                @forelse ($riwayatCuti as $cuti)
                                    <li class="flex items-center justify-between p-4 hover:bg-slate-50 transition-colors">
                                        <div class="flex items-center space-x-4">
                                            @php
                                                $statusClass = match($cuti->status) {
                                                    'Disetujui' => 'bg-hijau-100 text-hijau-800',
                                                    'Ditolak' => 'bg-red-100 text-red-800',
                                                    default => 'bg-yellow-100 text-yellow-800',
                                                };
                                                $iconClass = match($cuti->status) {
                                                    'Disetujui' => 'bi-check-circle-fill text-hijau-600',
                                                    'Ditolak' => 'bi-x-circle-fill text-red-600',
                                                    default => 'bi-clock-history text-yellow-600',
                                                };
                                            @endphp
                                            <div class="p-3 rounded-full {{ $statusClass }}">
                                                <i class="bi {{ $iconClass }}"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">{{ $cuti->jenis_cuti }}</p>
                                                <p class="text-sm text-gray-500">
                                                    {{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d M Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center gap-2">
                                            @if ($cuti->status === 'Menunggu')
                                                <span class="px-3 py-1 text-xs rounded-full {{ $statusClass }} font-bold hidden sm:inline-block">{{ $cuti->status }}</span>
                                                <a href="{{ url('/pengajuan/'.$cuti->id.'/batal') }}" onclick="return confirm('Anda yakin ingin membatalkan pengajuan ini?')" class="text-xs bg-red-100 text-red-700 px-3 py-1.5 rounded-full hover:bg-red-200 font-semibold flex items-center gap-1 transition-colors"><i class="bi bi-x-circle-fill"></i> Batalkan</a>
                                            @elseif ($cuti->status === 'Disetujui')
                                                <span class="px-3 py-1 text-xs rounded-full {{ $statusClass }} font-bold hidden sm:inline-block">{{ $cuti->status }}</span>
                                                <button data-id="{{ $cuti->id }}" class="btn-download text-xs bg-blue-100 text-blue-700 px-3 py-1.5 rounded-full hover:bg-blue-200 font-semibold flex items-center gap-1 transition-colors shadow-sm"><i class="bi bi-printer-fill"></i> Cetak PDF</button>
                                            @else
                                                <span class="px-3 py-1 text-xs rounded-full {{ $statusClass }} font-bold">{{ $cuti->status }}</span>
                                            @endif
                                        </div>
                                    </li>
                                @empty
                                    <li class="text-center p-8 text-gray-500">
                                        <i class="bi bi-folder2-open text-4xl mb-2 block text-gray-300"></i>
                                        Belum ada riwayat pengajuan cuti.
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="space-y-8">
                     <div>
                         <h3 class="text-xl font-bold text-gray-800 mb-4">Aksi Cepat</h3>
                         <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-4">
                            <a href="{{ url('/pengajuan') }}" class="flex items-center p-4 bg-hijau-500 text-white rounded-lg hover:bg-hijau-600 transition-all transform hover:scale-105 shadow-md">
                                <i class="bi bi-plus-circle-fill text-2xl"></i><span class="ml-4 font-bold">Buat Pengajuan Cuti Baru</span>
                            </a>
                         </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <nav class="lg:hidden fixed bottom-0 inset-x-0 bg-white border-t border-slate-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] flex justify-around z-40">
        <a href="{{ url('/dashboard') }}" class="flex flex-col items-center justify-center p-3 text-hijau-500 font-semibold w-full text-center">
            <i class="bi bi-grid-1x2-fill text-xl"></i>
            <span class="text-xs mt-1">Dashboard</span>
        </a>
        <a href="{{ url('/pengajuan') }}" class="flex flex-col items-center justify-center p-3 text-gray-600 hover:text-hijau-500 w-full text-center">
            <i class="bi bi-journal-plus text-xl"></i>
            <span class="text-xs mt-1">Ajukan Cuti</span>
        </a>
        <a href="{{ url('/profil') }}" class="flex flex-col items-center justify-center p-3 text-gray-600 hover:text-hijau-500 w-full text-center">
            <i class="bi bi-person-fill text-xl"></i>
            <span class="text-xs mt-1">Profil</span>
        </a>
    </nav>

    <div id="pdfPreviewModal" class="custom-modal-backdrop hidden">
        <div class="custom-modal-content">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-200 shrink-0">
                <h3 class="text-lg font-bold text-gray-800"><i class="bi bi-file-earmark-pdf-fill text-red-500 mr-2"></i> Pratinjau Dokumen Cuti</h3>
                <div class="flex items-center gap-3">
                    <button id="downloadFromPreview" class="px-4 py-2 bg-hijau-500 text-white rounded-lg hover:bg-hijau-600 font-semibold flex items-center gap-2 text-sm shadow-sm transition-colors">
                        <i class="bi bi-download"></i> Download File
                    </button>
                    <button id="closePdfPreview" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-gray-500 hover:bg-slate-200 hover:text-gray-800 transition-colors">&times;</button>
                </div>
            </div>
            <iframe id="pdf-viewer" class="w-full bg-slate-100 border border-slate-200 shadow-inner" frameborder="0"></iframe>
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

            // --- PENGAMBILAN DATA DATABASE LARAVEL ---
            const laravelUser = @json(auth()->user());
            const dataAtasan = @json($dataAtasan ?? []);
            const allJenisCuti = @json($jenisCuti ?? []);
            const allRiwayatCuti = @json($riwayatCuti ?? []);

            // Mensimulasikan loggedInUser persis seperti struktur localStorage lama agar PDF tidak rusak
            const loggedInUser = {
                id: laravelUser.id,
                nama: laravelUser.name, 
                nip: laravelUser.nip,
                role: laravelUser.role,
                jabatan: laravelUser.jabatan,
                pangkat_gol: laravelUser.pangkat_gol,
                telepon: laravelUser.telepon,
                atasan1: laravelUser.atasan1,
                atasan2: laravelUser.atasan2,
                sisaCuti: { 
                    tahunIni: laravelUser.sisa_cuti_tahun_ini, 
                    tahunLalu: laravelUser.sisa_cuti_tahun_lalu, 
                    diambil: laravelUser.cuti_diambil 
                },
                masaKerjaTotal: "{{ auth()->user()->masa_kerja_total }}"
            };

            const riwayatList = document.getElementById('riwayatCuti');
            const pdfPreviewModal = document.getElementById('pdfPreviewModal');
            const closePdfPreviewBtn = document.getElementById('closePdfPreview');
            const pdfViewer = document.getElementById('pdf-viewer');
            const downloadFromPreviewBtn = document.getElementById('downloadFromPreview');
            let pdfDocToDownload = null;
            let pdfFileNameToDownload = '';

            // Event listener klik tombol Download
            riwayatList.addEventListener('click', function(e) {
                const downloadBtn = e.target.closest('.btn-download');
                if (!downloadBtn) return;

                const cutiId = parseInt(downloadBtn.dataset.id);
                const rawCutiData = allRiwayatCuti.find(c => c.id === cutiId);
                
                if (rawCutiData) {
                    const { doc, fileName } = generatePdf(rawCutiData);
                    pdfDocToDownload = doc;
                    pdfFileNameToDownload = fileName;
                    
                    pdfViewer.src = doc.output('datauristring');
                    pdfPreviewModal.classList.remove('hidden');
                    setTimeout(() => pdfPreviewModal.classList.add('show'), 10);
                }
            });

            function closePreviewModal() {
                pdfPreviewModal.classList.remove('show');
                setTimeout(() => {
                    pdfPreviewModal.classList.add('hidden');
                    pdfViewer.src = 'about:blank';
                    pdfDocToDownload = null;
                    pdfFileNameToDownload = '';
                }, 300);
            }

            closePdfPreviewBtn.addEventListener('click', closePreviewModal);
            downloadFromPreviewBtn.addEventListener('click', () => {
                if (pdfDocToDownload && pdfFileNameToDownload) {
                    pdfDocToDownload.save(pdfFileNameToDownload);
                }
            });

            // =======================================================
            // GENERATE PDF (Susunan 100% Original Sesuai Permintaan)
            // =======================================================
            function generatePdf(rawCutiData) {
                const cutiData = {
                    ...rawCutiData,
                    tanggal: `${rawCutiData.tanggal_mulai} - ${rawCutiData.tanggal_selesai}`,
                    jenis: rawCutiData.jenis_cuti,
                    alamatCuti: rawCutiData.alamat,
                    tanggalPengajuan: rawCutiData.created_at
                };

                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('p', 'pt', 'a4');
                const atasan1 = dataAtasan.find(a => a.nip === loggedInUser.atasan1) || {};
                const atasan2 = dataAtasan.find(a => a.nip === loggedInUser.atasan2) || {};
                const masaKerja = loggedInUser.masaKerjaTotal;

                const now = new Date();
                const timestamp = `${now.getFullYear()}${(now.getMonth() + 1).toString().padStart(2, '0')}${now.getDate().toString().padStart(2, '0')}${now.getHours().toString().padStart(2, '0')}${now.getMinutes().toString().padStart(2, '0')}${now.getSeconds().toString().padStart(2, '0')}`;
                const [tglMulai, tglSelesai] = cutiData.tanggal.split(' - ');
                const tglMulaiFormatted = new Date(tglMulai).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).replace(/\./g, '');
                const fileName = `${timestamp} FOM CUTI ${loggedInUser.nama.split(' ')[0].toUpperCase()} ${tglMulaiFormatted}.pdf`;

                const pageW = doc.internal.pageSize.getWidth();
                const marginX = 40;
                const contentWidth = pageW - (2 * marginX);
                let currentY = 25;
                const rightAlignX = contentWidth + marginX;
                
                doc.setFont('times', 'normal');
                doc.setFontSize(10);

                const submissionDate = new Date(cutiData.tanggalPengajuan || Date.now());
                const formattedSubmissionDate = submissionDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                doc.text(`Tanjungpinang,  ${formattedSubmissionDate}`, rightAlignX - 185, currentY);
                currentY += 12;
                doc.text("Kepada", rightAlignX - 135, currentY);
                currentY += 12;
                doc.text("Yth. Kepala Dinas Lingkungan Hidup", rightAlignX - 190, currentY);
                currentY += 12;
                doc.text("di", rightAlignX - 125, currentY);
                currentY += 12;
                doc.text("Tanjungpinang", rightAlignX - 150, currentY);
                
                currentY += 25;
                doc.setFont('times', 'normal');
                doc.setFontSize(8);
                doc.text("FORMULIR PERMINTAAN DAN PEMBERIAN CUTI", doc.internal.pageSize.getWidth() / 2, currentY, { align: 'center' });
                currentY += 12;
                doc.text("PEGAWAI NEGERI SIPIL", doc.internal.pageSize.getWidth() / 2, currentY, { align: 'center' });

                currentY += 15;
                doc.setLineWidth(0.5);

                // --- SECTION I: DATA PEGAWAI ---
                const sectionI_Y = currentY;
                const rowHeightI = 12;
                doc.rect(marginX, sectionI_Y, contentWidth, 12 + (4 * rowHeightI));
                doc.text("I. DATA PEGAWAI", marginX + 2, sectionI_Y + 9);
                
                let tempY = sectionI_Y + 12;
                const col1X = marginX;
                const col2X = marginX + 80;
                const col3X = marginX + 215;
                const col4X = marginX + 280;
                
                // Row 1
                doc.line(col1X, tempY, rightAlignX, tempY);
                doc.text("Nama", col1X + 2, tempY + 9); doc.text(`${loggedInUser.nama || ''}`, col2X + 2, tempY + 9);
                doc.text("NIP", col3X + 2, tempY + 9); doc.text(`${loggedInUser.nip || ''}`, col4X + 2, tempY + 9);
                tempY += rowHeightI;
                // Row 2
                doc.line(col1X, tempY, rightAlignX, tempY);
                doc.text("Jabatan", col1X + 2, tempY + 9); doc.text(`${loggedInUser.jabatan || ''}`, col2X + 2, tempY + 9);
                doc.text("Masa Kerja", col3X + 2, tempY + 9); doc.text(`${masaKerja}`, col4X + 2, tempY + 9);
                tempY += rowHeightI;
                // Row 3
                doc.line(col1X, tempY, rightAlignX, tempY);
                doc.text("Pangkat/Gol", col1X + 2, tempY + 9); doc.text(`${loggedInUser.pangkat_gol || ''}`, col2X + 2, tempY + 9);
                tempY += rowHeightI;
                // Row 4
                doc.line(col1X, tempY, rightAlignX, tempY);
                doc.text("Unit Kerja", col1X + 2, tempY + 9); doc.text(`Dinas Lingkungan Hidup Kota Tanjungpinang`, col2X + 2, tempY + 9);
                
                // Vertical lines for Section I
                doc.line(col2X, sectionI_Y + 12, col2X, tempY + rowHeightI);
                doc.line(col3X, sectionI_Y + 12, col3X, tempY - rowHeightI);
                doc.line(col4X, sectionI_Y + 12, col4X, tempY - rowHeightI);
                currentY = tempY + rowHeightI;
                currentY += 10;

                // --- SECTION II: JENIS CUTI ---
                const rowHeight = 18;
                const rowCount = Math.ceil(allJenisCuti.length / 2); 
                const sectionII_Height = rowCount * rowHeight;
                
                doc.rect(marginX, currentY, contentWidth, sectionII_Height + 12); 
                doc.text("II. JENIS CUTI YANG DIAMBIL**", marginX + 2, currentY + 9);

                const sectionII_Y_content = currentY + 12;

                doc.line(marginX, sectionII_Y_content, rightAlignX, sectionII_Y_content);

                for (let r = 1; r < rowCount; r++) {
                    const yLine = sectionII_Y_content + r * rowHeight;
                    doc.line(marginX, yLine, rightAlignX , yLine);
                }

                const colJenisKiriEnd   = pageW / 3.2;
                const colCentangKiriEnd = pageW / 2;
                const colJenisKananEnd  = pageW / 2.35 + 190;
                doc.line(colJenisKiriEnd, sectionII_Y_content, colJenisKiriEnd, currentY + sectionII_Height + 12);
                doc.line(colCentangKiriEnd, sectionII_Y_content, colCentangKiriEnd, currentY + sectionII_Height + 12);
                doc.line(colJenisKananEnd, sectionII_Y_content, colJenisKananEnd, currentY + sectionII_Height + 12);

                const colCentangKiriCenter  = (colJenisKiriEnd + colCentangKiriEnd) / 2;
                const colCentangKananCenter = (colJenisKananEnd + rightAlignX) / 2;

                const opts = allJenisCuti.map(j => j.nama);

                const fontSize = 8;
                doc.setFontSize(fontSize);
                
                opts.forEach((j, i) => {
                    const rowIndex = Math.floor(i / 2);
                    const x = (i % 2 === 0) ? marginX + 2 : colCentangKiriEnd + 2;

                    const rowTop = sectionII_Y_content + rowIndex * rowHeight;
                    const yPos = rowTop + (rowHeight / 2) + (fontSize / 3);

                    doc.text(`${i + 1}. ${j}`, x, yPos);

                    if (cutiData.jenis === j) {
                        doc.setLineWidth(1.5);

                        const checkCenterX = (i % 2 === 0) ? colCentangKiriCenter : colCentangKananCenter;
                        const rowTop = sectionII_Y_content + rowIndex * rowHeight;
                        const rowMiddleY = rowTop + (rowHeight / 2);

                        const startX = checkCenterX - 4;
                        const startY = rowMiddleY - 2;

                        doc.line(startX, startY, startX + 4, startY + 4);
                        doc.line(startX + 3, startY + 4, startX + 12, startY - 2);

                        doc.setLineWidth(0.2);
                    }
                });

                currentY += sectionII_Height + 12 + 10;

                // --- SECTION III: ALASAN CUTI ---
                const alasanBoxHeight = 40;
                doc.rect(marginX, currentY, contentWidth, alasanBoxHeight);
                doc.text("III. ALASAN CUTI", marginX + 2, currentY + 9);

                const alasanContentY = currentY + 12;
                doc.line(marginX, alasanContentY, rightAlignX, alasanContentY);

                doc.setFontSize(fontSize);
                const alasanTextY = alasanContentY + (alasanBoxHeight - 12) / 2 + (fontSize / 3);

                doc.text(cutiData.alasan || '', marginX + 2, alasanTextY, { maxWidth: contentWidth - 4 });

                currentY += alasanBoxHeight + 10;

                // --- SECTION IV: LAMANYA CUTI ---
                const rowHeightIV = 25;
                doc.rect(marginX, currentY, contentWidth, rowHeightIV);
                doc.text("IV. LAMANYA CUTI", marginX + 2, currentY + 9);

                const lamanyaContentY = currentY + 12;
                doc.line(marginX, lamanyaContentY, rightAlignX, lamanyaContentY);

                const rowTop = currentY + 12;
                const rowBottom = currentY + rowHeightIV;

                const colX = [ 
                marginX,
                marginX + 80,
                marginX + 215,
                marginX + 280,
                marginX + 373,
                marginX + 403,
                rightAlignX
                ];

                for (let i = 1; i < colX.length - 1; i++) {
                doc.line(colX[i], rowTop, colX[i], rowBottom);
                }

                doc.setFontSize(fontSize);
                const yMiddle = rowTop + (rowHeightIV - 12) / 2 + (fontSize / 3);

                doc.text(`Selama (${cutiData.durasi})`, marginX + 2, currentY + 22);
                doc.setFontSize(8).text(`(hari)*`, marginX + 82, currentY + 22); // Disesuaikan tidak potong tahun
                doc.setFontSize(8);
                doc.text(`mulai tanggal`, marginX +  225, currentY + 22);
                doc.text(`${new Date(tglMulai).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})}`, marginX + 295, currentY + 22);
                doc.text(`s/d`, marginX +  383, currentY + 22);
                doc.text(`${new Date(tglSelesai).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})}`, marginX + 428, currentY + 22);
                currentY += 35;

                // --- SECTION V: CATATAN CUTI ---

                doc.rect(marginX, currentY, contentWidth, 72);
                const splitX = marginX + 215; 
                doc.line(splitX, currentY + 12, splitX, currentY + 72 );
                doc.line(splitX + 188, currentY + 12, splitX + 188, currentY + 72 );

                doc.text("V. CATATAN CUTI***", marginX + 2, currentY + 9);
                currentY += 12;
                doc.line(marginX, currentY, rightAlignX, currentY);
                doc.text("1. CUTI TAHUNAN", marginX + 2, currentY + 9);
                doc.line(marginX, currentY + 12, rightAlignX, currentY + 12);
                const otherX_V = marginX + 217;
                doc.text("2. CUTI BESAR", otherX_V, currentY + 9);
                doc.line(marginX, currentY + 24, rightAlignX, currentY + 24);
                doc.text("3. CUTI SAKIT", otherX_V, currentY + 21);
                doc.line(marginX, currentY + 36, rightAlignX, currentY + 36);
                doc.text("4. CUTI MELAHIRKAN", otherX_V, currentY + 33);
                doc.line(marginX, currentY + 48, rightAlignX, currentY + 48);
                doc.text("5. CUTI KARENA ALASAN PENTING", otherX_V, currentY + 45);
                doc.text("6. CUTI DILUAR TANGGUNGAN NEGARA", otherX_V, currentY + 56);
                
                const sisaN1 = loggedInUser.sisaCuti ? loggedInUser.sisaCuti.tahunLalu : 0;
                const sisaN = loggedInUser.sisaCuti ? loggedInUser.sisaCuti.tahunIni : 12;
                const ketN = cutiData.jenis === 'Cuti Tahunan' ? `Diambil ${cutiData.durasi} Hari` : '';
                const tblX = marginX, tblY = currentY + 12, tblW = 215, tblH = 48;
                doc.setFont('times', 'normal').text("Tahun", tblX + 2, tblY + 9).text("Sisa", tblX + 91, tblY + 9).text("Keterangan", tblX + 118, tblY + 9);
                doc.line(tblX + 80, tblY, tblX + 80, tblY + tblH);
                doc.line(tblX + 116, tblY, tblX + 116, tblY + tblH);
                const rowH_V = 12;
                const row1Y_V = tblY + 9 + rowH_V;
                const row2Y_V = row1Y_V + rowH_V;
                const row3Y_V = row2Y_V + rowH_V + 1;
                doc.setFont('times', 'normal').text("N-2", tblX + 2, row1Y_V).text("N-1", tblX + 2, row2Y_V).text(sisaN1.toString(), tblX + 95, row2Y_V);
                doc.text("N", tblX + 2, row3Y_V).text(sisaN.toString(), tblX + 94, row3Y_V).text(ketN, tblX + 118, row3Y_V);
                currentY += 70;
                
                // --- SECTION VI: ALAMAT & TTD PEMOHON ---
                const sectionHeight = 95;
                doc.rect(marginX, currentY, contentWidth, sectionHeight);

                doc.line(splitX + 65, currentY + 12, splitX + 65, currentY + sectionHeight);
                doc.line(splitX + 157, currentY + 12, splitX + 157, currentY + 23);

                doc.text("VI. ALAMAT SELAMA MENJALANKAN CUTI", marginX + 2, currentY + 9);
                currentY += 12;
                doc.line(marginX, currentY, rightAlignX, currentY);
                doc.line(marginX, currentY + 11, rightAlignX, currentY + 11);

                // === Area kiri (Alamat) ===
                const alamatBoxX = marginX;
                const alamatBoxY = currentY + 6;
                const alamatBoxRightX = splitX + 65;
                const alamatBoxW = alamatBoxRightX - alamatBoxX - 8;
                const alamatBoxH = sectionHeight - 11;
                const centerAlamatX = alamatBoxX + alamatBoxW / 2;

                const alamatText = (cutiData.alamatCuti || '').toString().trim() || '-';
                const lineHeight = fontSize * 1.15;
                const alamatLines = doc.splitTextToSize(alamatText, alamatBoxW - 10);

                const alamatTextHeight = alamatLines.length * lineHeight;
                const alamatStartY = alamatBoxY + (alamatBoxH / 2) - (alamatTextHeight / 2) + lineHeight * 0.8;

                alamatLines.forEach((line, idx) => {
                    const y = alamatStartY + idx * lineHeight;
                    doc.text(line, centerAlamatX, y, { align: "center" });
                });

                // === Kolom Telepon + Tanda Tangan ===
                doc.text("TELP", marginX + 318, currentY + 9);
                doc.text(`${loggedInUser.telepon || '-'}`, marginX + 417, currentY + 9);

                const rightBoxX = pageW / 2;
                const rightBoxW = contentWidth / 2;
                const rightBoxY = currentY + 11;
                const rightBoxH = sectionHeight - 11;

                const ttdCenterX = rightBoxX + rightBoxW / 2;
                const ttdCenterY = rightBoxY + rightBoxH / 1.5;

                doc.text("Hormat saya,", ttdCenterX, ttdCenterY - 40, { align: "center" });

                const namaPemohon = `(${loggedInUser.nama})`;
                doc.text(namaPemohon, ttdCenterX, ttdCenterY, { align: "center" });

                const nipPemohon = `NIP. ${loggedInUser.nip}`;
                doc.text(nipPemohon, ttdCenterX, ttdCenterY + 10, { align: "center" });

                currentY += sectionHeight;

                // --- SECTION VII  APPROVALS ATASAN (DIISI OLEH ATASAN 2 / Pejabat Berwenang - Sesuai Request) ---

                const sectionVIIHeight = 36;
                doc.rect(marginX, currentY, contentWidth, sectionVIIHeight);
                doc.line(splitX - 135, currentY + 12, splitX - 135, currentY + 36 );
                doc.line(splitX, currentY + 12, splitX, currentY + 36 );
                doc.line(splitX + 158, currentY + 12, splitX + 158, currentY + 36 );

                doc.text("VII. PERTIMBANGAN ATASAN LANGSUNG**", marginX + 2, currentY + 9);
                currentY += 12;
                doc.line(marginX, currentY, rightAlignX, currentY);
                doc.line(marginX, currentY + 12, rightAlignX, currentY + 12);

                doc.text("DISETUJUI", marginX + 2, currentY + 9);
                doc.text("PERUBAHAN", marginX + 82, currentY + 9);
                doc.text("DITANGGUHKAN****", marginX + 217, currentY + 9);
                doc.text("TIDAK DISETUJUI****", marginX + 375, currentY + 9);

                // --- Garis Batas Kotak Bawah (Sudah Ada) ---
                doc.line(marginX + 280, currentY + 96, rightAlignX, currentY + 96);
                doc.line(splitX + 65, currentY + 24, splitX + 65, currentY + 96);
                doc.line(rightAlignX, currentY + 24, rightAlignX, currentY + 96);

                // --- Hitung Area Kolom Kanan ---
                const kotakAtas = currentY + 32;
                const kotakBawah = currentY + 96;

                const kolomKananX1 = splitX + 65;      
                const kolomKananX2 = rightAlignX;      
                const sigCenterX = kolomKananX1 + (kolomKananX2 - kolomKananX1) / 2; 
                const sigCenterY = kotakAtas + (kotakBawah - kotakAtas) / 2 - 5;

                // --- Data Atasan (Menggunakan data Atasan 2 untuk ditaruh di kotak Atas) ---
                const jab1 = atasan2.jabatan ? atasan2.jabatan.toUpperCase() : "...................................";
                const name1 = atasan2.nama ? atasan2.nama.toUpperCase() : "...................................";
                const nip1 = atasan2.nip ? `NIP. ${atasan2.nip}` : "NIP. ..............................";

                // --- Jabatan Wrap (turun baris jika panjang) ---
                const jabLines = doc.splitTextToSize(jab1, 150);
                doc.text(jabLines, sigCenterX, sigCenterY - 25, { align: "center" });

                // --- Nama + Garis Bawah ---
                const formattedName1 = `(${name1})`;
                doc.setFont("times", "bold");
                doc.text(formattedName1, sigCenterX, sigCenterY + 20, { align: "center" });
                const nameW1 = doc.getTextWidth(formattedName1);
                doc.line(sigCenterX - nameW1 / 2, sigCenterY + 22, sigCenterX + nameW1 / 2, sigCenterY + 22);

                // --- NIP ---
                doc.setFont("times", "normal").text(nip1, sigCenterX, sigCenterY + 32, { align: "center" });

                currentY = currentY + 107;

                // --- SECTION VIII  APPROVALS ATASAN (DIISI OLEH ATASAN 1 / Atasan Langsung - Sesuai Request) ---

                const sectionVIIIHeight = 36;
                doc.rect(marginX, currentY, contentWidth, sectionVIIIHeight);
                doc.line(splitX - 135, currentY + 12, splitX - 135, currentY + 36 );
                doc.line(splitX, currentY + 12, splitX, currentY + 36 );
                doc.line(splitX + 158, currentY + 12, splitX + 158, currentY + 36 );

                doc.text("VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI**", marginX + 2, currentY + 9);
                currentY += 12;
                doc.line(marginX, currentY, rightAlignX, currentY);
                doc.line(marginX, currentY + 12, rightAlignX, currentY + 12);

                doc.text("DISETUJUI", marginX + 2, currentY + 9);
                doc.text("PERUBAHAN", marginX + 82, currentY + 9);
                doc.text("DITANGGUHKAN****", marginX + 217, currentY + 9);
                doc.text("TIDAK DISETUJUI****", marginX + 375, currentY + 9);

                // --- Garis Batas Kotak Bawah (Sudah Ada) ---
                doc.line(marginX + 280, currentY + 96, rightAlignX, currentY + 96);
                doc.line(splitX + 65, currentY + 24, splitX + 65, currentY + 96);
                doc.line(rightAlignX, currentY + 24, rightAlignX, currentY + 96);

                // --- Hitung Area Kolom Kanan ---
                const kotakAtas2 = currentY + 32;
                const kotakBawah2 = currentY + 96;

                const kolomKananX1_2 = splitX + 65;      
                const kolomKananX2_2 = rightAlignX;      
                const sigCenterX2 = kolomKananX1_2 + (kolomKananX2_2 - kolomKananX1_2) / 2; 
                const sigCenterY2 = kotakAtas2 + (kotakBawah2 - kotakAtas2) / 2 - 5;

                // --- Data Atasan 2 (Menggunakan data Atasan 1 untuk ditaruh di kotak Bawah) ---
                const jab2 = atasan1.jabatan ? atasan1.jabatan.toUpperCase() : "...................................";
                const name2 = atasan1.nama ? atasan1.nama.toUpperCase() : "...................................";
                const nip2 = atasan1.nip ? `NIP. ${atasan1.nip}` : "NIP. ..............................";

                // --- Jabatan Wrap (turun baris jika panjang) ---
                const jabLines2 = doc.splitTextToSize(jab2, 150);
                doc.text(jabLines2, sigCenterX2, sigCenterY2 - 25, { align: "center" });

                // --- Nama + Garis Bawah ---
                const formattedName2 = `(${name2})`;
                doc.setFont("times", "bold");
                doc.text(formattedName2, sigCenterX2, sigCenterY2 + 20, { align: "center" });
                const nameW2 = doc.getTextWidth(formattedName2);
                doc.line(sigCenterX2 - nameW2 / 2, sigCenterY2 + 22, sigCenterX2 + nameW2 / 2, sigCenterY2 + 22);

                // --- NIP ---
                doc.setFont("times", "normal").text(nip2, sigCenterX2, sigCenterY2 + 32, { align: "center" });

                currentY = currentY + 107;

                // --- FOOTER: NOTES ---
                doc.setFontSize(7);
                const noteY = currentY + 12;
                doc.text("Catatan:", marginX, noteY);
                doc.text("* Coret yang tidak perlu", marginX + 20, noteY + 10);
                doc.text("** Pilih salah satu dengan memberi tanda centang (V)", marginX + 20, noteY + 18);
                doc.text("*** Diisi oleh pejabat yang menangani bidang kepegawaian sebelum PNS mengajukan cuti", marginX + 20, noteY + 26);
                doc.text("**** diberi tanda centang (V) dan alasannya", marginX + 20, noteY + 34);
                doc.text("N    = Cuti Tahun Berjalan", marginX + 20, noteY + 42);

                return { doc, fileName };
            }
        });
    </script>
</body>
</html>