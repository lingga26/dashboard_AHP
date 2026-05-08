<?php
// =========================================================  
// DATA WARGA - BPJS PBI AHP SYSTEM  
// ========================================================= 

// PERBAIKAN 1: Hilangkan error Notice session_start di baris 12
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/functions.php';

// Proteksi Halaman: Wajib Login
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

$pageTitle = 'Data Warga';

// PERBAIKAN 2: Tampilkan semua data warga tanpa terkecuali (LEFT JOIN/SELECT ALL)
// Ini memastikan warga yang baru didaftarkan tapi belum dinilai tetap muncul di tabel
$warga = db()->fetchAll("SELECT * FROM warga ORDER BY id_warga DESC");

include 'templates/header.php';
include 'templates/sidebar.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">👥 Data Warga</h2>
        <!-- Tombol ini mengarah ke form penilaian baru yang tadi kita buat -->
        <a href="penilaian.php" class="btn btn-primary shadow-sm">
            <i class="fas fa-user-plus me-2"></i>Tambah & Nilai Warga
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
            <h6 class="mb-0 fw-bold text-primary">Daftar Seluruh Warga Terdaftar</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Nama Lengkap</th>
                            <th>NIK</th>
                            <th>Domisili / Alamat</th>
                            <th>Status Penilaian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($warga)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada data warga yang terdaftar.</td>
                        </tr>
                        <?php else: ?>
                            <?php 
                            $no = 1; 
                            foreach ($warga as $row): 
                                // Cek apakah warga ini sudah memiliki skor di tabel hasil_ahp
                                $cekNilai = db()->fetchOne("SELECT id_warga FROM hasil_ahp WHERE id_warga = ?", [$row['id_warga']]);
                            ?>
                            <tr>
                                <td class="ps-4"><?= $no++ ?></td>
                                <td class="fw-bold text-dark"><?= htmlspecialchars($row['nama']) ?></td>
                                <td><code><?= htmlspecialchars($row['nik']) ?></code></td>
                                <td><?= htmlspecialchars($row['alamat']) ?></td>
                                <td>
                                    <?php if($cekNilai): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success"><i class="fas fa-check me-1"></i>Sudah Dinilai</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning"><i class="fas fa-clock me-1"></i>Menunggu Penilaian</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>