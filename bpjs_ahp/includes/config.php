<?php
/**
 * =========================================================
 * CONFIGURATION FILE - BPJS PBI AHP SYSTEM
 * Penentuan Prioritas BPJS PBI dengan Metode AHP
 * Kecamatan Pondokgede
 * =========================================================
 */
// Ganti baris 9 pada includes/config.php dengan kode ini:
// Ganti baris 9 pada includes/config.php dengan kode ini:

// Tambahkan pengecekan status agar tidak error jika sesi sudah jalan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bpjs_pbi_ahp');

define('BASE_URL', 'http://localhost/bpjs_pbi_ahp/');
define('APP_NAME', 'Sistem Penentuan Prioritas BPJS PBI');
define('APP_VERSION', '1.0.0');
define('KECAMATAN', 'Pondokgede');

// AHP Configuration
$GLOBALS['AHP_BOBOT_GLOBAL'] = [
    'IDK01' => 0.3782,  // KERT - Kondisi Ekonomi
    'IDK02' => 0.1277,  // BTK - Beban Tanggungan
    'IDK03' => 0.2187,  // KJK - Jaminan Kesehatan
    'IDK04' => 0.0566,  // KNT - NIK Tervalidasi
    'IDK05' => 0.2187   // KKAK - Kondisi Kesehatan
];

// Bobot Prioritas sesuai level
$GLOBALS['AHP_BOBOT_SUB'] = [
    'Sangat Prioritas'  => 0.419,
    'Prioritas'         => 0.263,
    'Cukup Prioritas'   => 0.161,
    'Kurang Prioritas'  => 0.097,
    'Tidak Prioritas'   => 0.060
];

// Threshold kategori hasil
$GLOBALS['AHP_KATEGORI'] = [
    ['min' => 30, 'kategori' => 'Prioritas 1', 'label' => '🟢 Prioritas 1 (Skor ≥ 30)', 'keterangan' => 'Segera diproses'],
    ['min' => 20, 'kategori' => 'Prioritas 2', 'label' => '🟡 Prioritas 2 (Skor 20-29)', 'keterangan' => 'Proses berikutnya'],
    ['min' => 15, 'kategori' => 'Prioritas 3', 'label' => '🟠 Prioritas 3 (Skor 15-19)', 'keterangan' => 'Daftar tunggu'],
    ['min' => 0, 'kategori' => 'Tidak Layak', 'label' => '🔴 Tidak Layak (Skor < 15)', 'keterangan' => 'Belum memenuhi syarat']
];

// Error reporting
try {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Set timezone
    date_default_timezone_set('Asia/Jakarta');
    
} catch (Exception $e) {
    die("Configuration Error: " . $e->getMessage());
}
?>