<?php
/**
 * =========================================================
 * UTILITY FUNCTIONS - BPJS PBI AHP SYSTEM
 * =========================================================
 */

require_once 'config.php';
require_once 'database.php';

/**
 * Format uang Indonesia
 */
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

/**
 * Format angka desimal
 */
function formatDesimal($angka, $digit = 2) {
    return number_format($angka, $digit, ',', '.');
}

/**
 * Flash message handler
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Get status BPJS label
 */
function getStatusBPJSLabel($kode) {
    $labels = [
        0 => 'Belum punya BPJS',
        1 => 'Nonaktif >6 bulan',
        2 => 'Nonaktif 3-6 bulan',
        3 => 'Nonaktif <3 bulan',
        4 => 'Aktif'
    ];
    return $labels[$kode] ?? 'Tidak diketahui';
}

/**
 * Get parameter kriteria dengan labels
 */
function getKriteriaParams() {
    return [
        'IDK01' => [
            'nama' => 'Kondisi Ekonomi (KERT)',
            'satuan' => 'Rp/bulan',
            'input_type' => 'number',
            'min' => 0,
            'max' => 10000000,
            'help' => 'Masukkan pendapatan bulanan rumah tangga'
        ],
        'IDK02' => [
            'nama' => 'Beban Tanggungan (BTK)',
            'satuan' => 'orang',
            'input_type' => 'number',
            'min' => 1,
            'max' => 15,
            'help' => 'Jumlah anggota keluarga yang ditanggung'
        ],
        'IDK03' => [
            'nama' => 'Jaminan Kesehatan (KJK)',
            'satuan' => '',
            'input_type' => 'select',
            'options' => [
                0 => 'Belum punya BPJS',
                1 => 'Nonaktif >6 bulan',
                2 => 'Nonaktif 3-6 bulan',
                3 => 'Nonaktif <3 bulan',
                4 => 'Aktif'
            ],
            'help' => 'Status kepesertaan BPJS'
        ],
        'IDK04' => [
            'nama' => 'NIK & Domisili (KNT)',
            'satuan' => 'tahun',
            'input_type' => 'number',
            'min' => 0,
            'max' => 50,
            'help' => 'Lama bertempat tinggal di Pondokgede'
        ],
        'IDK05' => [
            'nama' => 'Kondisi Kesehatan (KKAK)',
            'satuan' => 'skor (0-100)',
            'input_type' => 'number',
            'min' => 0,
            'max' => 100,
            'help' => 'Skor kondisi kesehatan anggota keluarga (0-100)'
        ]
    ];
}

/**
 * Safe get from array
 */
function safeGet($array, $key, $default = '') {
    return isset($array[$key]) ? $array[$key] : $default;
}

/**
 * Redirect dengan flash message
 */
function redirect($url, $flashType = null, $flashMsg = null) {
    if ($flashType && $flashMsg) {
        setFlash($flashType, $flashMsg);
    }
    header("Location: " . BASE_URL . $url);
    exit;
}

/**
 * Get color class untuk kategori
 */
function getKategoriColor($kategori) {
    $colors = [
        'Prioritas 1' => 'success',
        'Prioritas 2' => 'warning',
        'Prioritas 3' => 'info',
        'Tidak Layak' => 'danger'
    ];
    return $colors[$kategori] ?? 'secondary';
}

/**
 * Pagination helper
 */
function paginate($sql, $params = [], $perPage = 20, $currentPage = 1) {
    // Count total
    $countSql = preg_replace('/SELECT.*?FROM/i', 'SELECT COUNT(*) as total FROM', $sql, 1);
    $countSql = preg_replace('/ORDER BY.*/i', '', $countSql);
    
    $count = db()->fetchOne($countSql, $params);
    $totalItems = $count['total'];
    $totalPages = ceil($totalItems / $perPage);
    
    // Add LIMIT
    $offset = ($currentPage - 1) * $perPage;
    $sql .= " LIMIT $offset, $perPage";
    
    $items = db()->fetchAll($sql, $params);
    
    return [
        'items' => $items,
        'total' => $totalItems,
        'pages' => $totalPages,
        'current' => $currentPage,
        'per_page' => $perPage
    ];
}

/**
 * Render pagination buttons
 */
function renderPagination($pagination, $baseUrl) {
    if ($pagination['pages'] <= 1) return '';
    
    $html = '<nav><ul class="pagination justify-content-center">';
    
    // Previous
    $prevDisabled = $pagination['current'] <= 1 ? 'disabled' : '';
    $prevPage = $pagination['current'] - 1;
    $html .= "<li class='page-item $prevDisabled'><a class='page-link' href='$baseUrl&page=$prevPage'>←</a></li>";
    
    // Page numbers
    for ($i = 1; $i <= $pagination['pages']; $i++) {
        $active = $i === $pagination['current'] ? 'active' : '';
        $html .= "<li class='page-item $active'><a class='page-link' href='$baseUrl&page=$i'>$i</a></li>";
    }
    
    // Next
    $nextDisabled = $pagination['current'] >= $pagination['pages'] ? 'disabled' : '';
    $nextPage = $pagination['current'] + 1;
    $html .= "<li class='page-item $nextDisabled'><a class='page-link' href='$baseUrl&page=$nextPage'>→</a></li>";
    
    $html .= '</ul></nav>';
    
    return $html;
}

/**
 * Extract sub kriteria info
 */
function getSubKriteriaInfo($idKriteria, $bobot) {
    $data = db()->fetchOne(
        "SELECT * FROM sub_kriteria WHERE id_kriteria = ? AND bobot = ?",
        [$idKriteria, $bobot]
    );
    return $data;
}

/**
 * Export to CSV
 */
function exportCSV($filename, $data, $headers) {
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=$filename");
    
    $output = fopen('php://output', 'w');
    
    // BOM for UTF-8
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Headers
    fputcsv($output, $headers);
    
    // Data
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit;
}

/**
 * Get menu items
 */
function getMenuItems() {
    return [
        ['title' => 'Dashboard', 'url' => 'index.php', 'icon' => '📊'],
        ['title' => 'Data Warga', 'url' => 'warga.php', 'icon' => '👥'],
        ['title' => 'Input Penilaian', 'url' => 'penilaian.php', 'icon' => '📝'],
        ['title' => 'Hasil Perhitungan', 'url' => 'hasil.php', 'icon' => '📋'],
        ['title' => 'Statistik', 'url' => 'statistik.php', 'icon' => '📈'],
        ['title' => 'Kriteria AHP', 'url' => 'kriteria.php', 'icon' => '⚙️'],
    ];
}

/**
 * Get current page URL
 */
function currentPage() {
    return basename($_SERVER['SCRIPT_NAME']);
}

/**
 * Sanitize input
 */
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Validasi format NIK
 */
function validasiNIK($nik) {
    return preg_match('/^[0-9]{16}$/', $nik);
}

/**
 * Generate random ID
 */
function generateId($prefix = '') {
    return $prefix . date('Ymd') . rand(1000, 9999);
}
// Tambahkan di dalam includes/functions.php tanpa merubah yang lain
function validasiNIKPondokgede($nik) {
    // Syarat: 16 digit dan diawali 327508
    return preg_match('/^327508[0-9]{10}$/', $nik);
}
?>