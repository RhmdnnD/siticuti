/**
 * ===================================================================================
 * @file app.js
 * @description Skrip utama untuk aplikasi SITI CUTI (Sistem Cuti Online).
 * Berisi fungsi-fungsi inti, inisialisasi data, utilitas UI,
 * dan logika bisnis aplikasi.
 * @version 1.3.0
 * ===================================================================================
 */

'use strict';

// ===================================================================================
// #region INISIALISASI & EVENT LISTENER UTAMA
// ===================================================================================

/**
 * Event listener yang dijalankan saat konten DOM selesai dimuat.
 * Memulai semua fungsi inisialisasi aplikasi.
 */
document.addEventListener('DOMContentLoaded', () => {
    initializeData();
});

/**
 * @function initializeData
 * @description Memeriksa dan menginisialisasi data awal di localStorage jika belum ada.
 * Juga menjalankan fungsi pemeliharaan seperti reset cuti tahunan dan
 * pembersihan log otomatis.
 */
function initializeData() {
    // Cek jika data sudah diinisialisasi untuk mencegah penimpaan
    if (localStorage.getItem('dataInitialized')) {
        checkAndResetCutiTahunan();
        autoClearOldLogs();
        return;
    }
    
    // Data awal yang akan disimpan ke localStorage
    const initialData = {
        'dataAsn': [],
        'dataAtasan': [],
        'jenisCuti': [
            { "id": 1, "nama": "Cuti Tahunan", "wajibLampiran": false },
            { "id": 2, "nama": "Cuti Besar", "wajibLampiran": false },
            { "id": 3, "nama": "Cuti Sakit", "wajibLampiran": true },
            { "id": 4, "nama": "Cuti Melahirkan", "wajibLampiran": true },
            { "id": 5, "nama": "Cuti Karena Alasan Penting", "wajibLampiran": true },
            { "id": 6, "nama": "Cuti di Luar Tanggungan Negara", "wajibLampiran": false }
        ],
        'pengajuanCuti': [],
        'activityLogs': [],
        'hariLiburNasional': []
    };

    for (const key in initialData) {
        // Hanya set jika item belum ada
        if (localStorage.getItem(key) === null) {
            localStorage.setItem(key, JSON.stringify(initialData[key]));
        }
    }
    
    // Tandai bahwa inisialisasi data awal sudah selesai
    localStorage.setItem('dataInitialized', 'true');

    checkAndResetCutiTahunan();
    autoClearOldLogs(); // Menjalankan fungsi hapus log otomatis
}


// ===================================================================================
// #endregion
// ===================================================================================


// ===================================================================================
// #region FUNGSI UTILITAS UI (MODAL)
// ===================================================================================

const customAlertModal = document.getElementById('customAlertModal');
const customAlertTitle = document.getElementById('customAlertTitle');
const customAlertMessage = document.getElementById('customAlertMessage');
const customAlertOK = document.getElementById('customAlertOK');
const customAlertCancel = document.getElementById('customAlertCancel');
let onConfirmCallback = null;

/**
 * @function showAlert
 * @description Menampilkan modal pemberitahuan (alert) kustom.
 * @param {string} message - Pesan yang akan ditampilkan.
 * @param {function} [callback=null] - Fungsi yang akan dijalankan setelah modal ditutup.
 * @param {string} [title='Pemberitahuan'] - Judul modal.
 */
function showAlert(message, callback = null, title = 'Pemberitahuan') {
    if (!customAlertModal) return;
    customAlertTitle.textContent = title;
    customAlertMessage.innerHTML = message;
    customAlertCancel.classList.add('hidden');
    customAlertOK.textContent = 'OK';
    customAlertOK.onclick = () => {
        customAlertModal.classList.remove('show');
        if (callback) callback();
    };
    customAlertModal.classList.add('show');
}

/**
 * @function showConfirm
 * @description Menampilkan modal konfirmasi kustom.
 * @param {string} message - Pesan konfirmasi yang akan ditampilkan.
 * @param {function} callback - Fungsi yang akan dijalankan dengan hasil konfirmasi (true/false).
 * @param {string} [title='Konfirmasi'] - Judul modal.
 */
function showConfirm(message, callback, title = 'Konfirmasi') {
    if (!customAlertModal) return;
    customAlertTitle.textContent = title;
    customAlertMessage.textContent = message;
    customAlertCancel.classList.remove('hidden');
    customAlertOK.textContent = 'Ya';
    customAlertCancel.textContent = 'Tidak';
    onConfirmCallback = callback;
    customAlertOK.onclick = () => {
        customAlertModal.classList.remove('show');
        if (onConfirmCallback) onConfirmCallback(true);
    };
    customAlertCancel.onclick = () => {
        customAlertModal.classList.remove('show');
        if (onConfirmCallback) onConfirmCallback(false);
    };
    customAlertModal.classList.add('show');
}

// ===================================================================================
// #endregion
// ===================================================================================


// ===================================================================================
// #region FUNGSI LOGIKA BISNIS
// ===================================================================================

/**
 * @function addActivityLog
 * @description Menambahkan entri log aktivitas baru ke localStorage.
 * @param {string} actionDescription - Deskripsi dari aktivitas yang dilakukan.
 */
function addActivityLog(actionDescription) {
    const loggedInUser = JSON.parse(localStorage.getItem('loggedInUser'));
    const userRole = loggedInUser ? loggedInUser.role : 'sistem';
    const userName = loggedInUser ? loggedInUser.nama : 'Sistem';

    let logs = JSON.parse(localStorage.getItem('activityLogs')) || [];

    const newLog = {
        timestamp: new Date().toISOString(), // PERBAIKAN: Menggunakan format ISO standar
        user: `${userName} (${userRole})`,
        action: actionDescription,
        role: userRole
    };
    logs.unshift(newLog);
    localStorage.setItem('activityLogs', JSON.stringify(logs));
}

/**
 * @function checkAndResetCutiTahunan
 * @description Memeriksa apakah tahun telah berganti dan mereset jatah cuti tahunan
 * untuk semua ASN jika diperlukan.
 */
function checkAndResetCutiTahunan() {
    const lastResetYear = parseInt(localStorage.getItem('lastResetYear') || '0');
    const currentYear = new Date().getFullYear();
    if (currentYear <= lastResetYear) return;

    let dataAsn = JSON.parse(localStorage.getItem('dataAsn')) || [];

    dataAsn.forEach(asn => {
        if (asn.sisaCuti) {
            const sisaTahunIni = asn.sisaCuti.tahunIni - asn.sisaCuti.diambil;
            let sisaTahunLaluBaru = sisaTahunIni >= 6 ? 6 : (sisaTahunIni > 0 ? sisaTahunIni : 0);
            asn.sisaCuti.tahunIni = 12;
            asn.sisaCuti.tahunLalu = sisaTahunLaluBaru;
            asn.sisaCuti.diambil = 0;
        }
    });
    
    localStorage.setItem('dataAsn', JSON.stringify(dataAsn));
    localStorage.setItem('lastResetYear', currentYear.toString());
    
    const resetNotifEl = document.getElementById('reset-notif');
    if (resetNotifEl) {
        resetNotifEl.classList.remove('hidden');
    }
    addActivityLog('Sistem: Reset cuti tahunan otomatis untuk semua ASN.');
}

/**
 * @function autoClearOldLogs
 * @description Menghapus log aktivitas lama secara otomatis berdasarkan pengaturan
 * yang disimpan di localStorage ('logAutoDeleteMonths').
 */
function autoClearOldLogs() {
    const monthsToKeep = parseInt(localStorage.getItem('logAutoDeleteMonths') || '3'); // Default 3 bulan
    if (monthsToKeep === 0) return; // 0 berarti jangan hapus otomatis

    let allLogs = JSON.parse(localStorage.getItem('activityLogs')) || [];
    if (allLogs.length === 0) return;

    const cutoffDate = new Date();
    cutoffDate.setMonth(cutoffDate.getMonth() - monthsToKeep);

    const filteredLogs = allLogs.filter(log => {
        // Pastikan timestamp ada dan valid sebelum membuat objek Date
        if (!log.timestamp || isNaN(new Date(log.timestamp).getTime())) {
            return false; // Abaikan log dengan timestamp tidak valid
        }
        return new Date(log.timestamp) >= cutoffDate;
    });

    if (filteredLogs.length < allLogs.length) {
        console.log(`Sistem: Menghapus ${allLogs.length - filteredLogs.length} log lama secara otomatis.`);
        localStorage.setItem('activityLogs', JSON.stringify(filteredLogs));
    }
}

// ===================================================================================
// #endregion
// ===================================================================================
