<?php
// =========================================================  
// HASIL PERHITUNGAN AHP - BPJS PBI AHP SYSTEM  
// ========================================================= 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/functions.php';

// Proteksi Halaman: Wajib Login
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

$pageTitle = 'Hasil Perhitungan';

// Query hanya memanggil data yang diperlukan dan tetap diurutkan berdasarkan ranking
$query = "SELECT w.id_warga, w.nama, w.nik, h.ranking, h.kategori 
          FROM warga w 
          INNER JOIN hasil_ahp h ON w.id_warga = h.id_warga 
          ORDER BY h.ranking ASC";

$hasilAHP = db()->fetchAll($query);

include 'templates/header.php';
include 'templates/sidebar.php';
?>

<!-- CSS KHUSUS UNTUK CETAK PDF -->
<style>
    @media print {
        /* Sembunyikan elemen yang tidak perlu dicetak */
        .sidebar, .navbar, .btn, .alert, footer, .card-footer { 
            display: none !important; 
        }
        /* Bersihkan background agar hemat tinta dan rapi */
        body { 
            background-color: #fff !important; 
            padding: 0; margin: 0;
        }
        .card { 
            border: none !important; 
            box-shadow: none !important; 
        }
        .card-header { 
            background-color: transparent !important; 
            border-bottom: 2px solid #000 !important; 
            padding-left: 0 !important;
        }
        /* Ubah lencana (badge) menjadi teks biasa berdinding saat dicetak */
        .badge { 
            border: 1px solid #000 !important; 
            color: #000 !important; 
            background-color: transparent !important; 
        }
        /* Lebarkan tabel penuhi kertas */
        .container-fluid {
            width: 100% !important;
            padding: 0 !important;
        }
        /* Tampilkan judul laporan saat dicetak */
        .print-header {
            display: block !important;
            text-align: center;
            margin-bottom: 20px;
        }
    }
    
    /* Sembunyikan judul cetak saat dilihat di layar biasa */
    .print-header {
        display: none;
    }
</style>

<div class="container-fluid">
    
    <!-- Judul Dokumen (Hanya Muncul di PDF) -->
    <div class="print-header">
        <h2>LAPORAN PRIORITAS PENERIMA BPJS PBI</h2>
        <p>Kecamatan Pondokgede - Berdasarkan Perhitungan Metode AHP</p>
        <hr style="border: 1px solid #000;">
    </div>

    <!-- Header Halaman Normal -->
    <div class="d-flex justify-content-between align-items-center mb-4 hide-on-print">
        <h2 class="h3 mb-0">🏆 Hasil & Prioritas Penerima</h2>
        <a href="penilaian.php" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-2"></i>Nilai Warga Baru
        </a>
    </div>
    
    <?php $f = getFlash(); if($f): ?>
        <div class="alert alert-<?= $f['type'] ?> alert-dismissible fade show shadow-sm">
            <?= $f['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold text-primary">Daftar Warga Sesuai Prioritas</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 text-center align-middle border-bottom">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No / Peringkat</th>
                            <th class="text-start">Identitas Warga (Nama & NIK)</th>
                            <th>Status Prioritas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($hasilAHP)): ?>
                        <tr>
                            <td colspan="3" class="text-muted py-5">
                                Belum ada data warga yang dinilai. Silakan lakukan input penilaian terlebih dahulu.
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($hasilAHP as $row): ?>
                            <tr>
                                <td class="ps-4">
                                    <?php if ($row['ranking'] == 1): ?>
                                        <span class="badge bg-warning text-dark fs-5 shadow-sm">🥇 1</span>
                                    <?php elseif ($row['ranking'] == 2): ?>
                                        <span class="badge bg-secondary fs-6 shadow-sm">🥈 2</span>
                                    <?php elseif ($row['ranking'] == 3): ?>
                                        <span class="badge fs-6 shadow-sm" style="background-color: #cd7f32;">🥉 3</span>
                                    <?php else: ?>
                                        <span class="fw-bold fs-6 text-muted"><?= $row['ranking'] ?></span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="text-start">
                                    <div class="fw-bold text-dark fs-6"><?= htmlspecialchars($row['nama']) ?></div>
                                    <div class="text-muted small">NIK: <?= htmlspecialchars($row['nik']) ?></div>
                                </td>
                                
                                <td>
                                    <?php 
                                        $kategori = strtolower($row['kategori']);
                                        if (strpos($kategori, 'sangat prioritas') !== false || strpos($kategori, 'prioritas 1') !== false) {
                                            $badgeClass = 'bg-success'; 
                                        } elseif (strpos($kategori, 'tidak') !== false || strpos($kategori, 'prioritas 3') !== false) {
                                            $badgeClass = 'bg-danger'; 
                                        } else {
                                            $badgeClass = 'bg-info text-dark'; 
                                        }
                                    ?>
                                    <span class="badge <?= $badgeClass ?> px-3 py-2 fs-6 shadow-sm">
                                        <?= htmlspecialchars($row['kategori']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php if (!empty($hasilAHP)): ?>
        <div class="card-footer bg-light py-3 border-0 text-end">
            <!-- Tombol Cetak (Akan otomatis memanggil menu print/PDF bawaan Browser) -->
            <button onclick="window.print()" class="btn btn-secondary shadow-sm px-4">
                <i class="fas fa-file-pdf me-2"></i>Cetak Laporan / PDF
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'templates/footer.php'; ?>