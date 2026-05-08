<?php
/**
 * =========================================================
 * DETAIL WARGA - BPJS PBI AHP SYSTEM
 * =========================================================
 */

session_start();
require_once 'includes/functions.php';
require_once 'includes/AHPCalculator.php';

$pageTitle = 'Detail Warga';
$idWarga = intval($_GET['id'] ?? 0);

if (!$idWarga) {
    setFlash('danger', 'ID Warga tidak valid');
    redirect('warga.php');
}

// Get warga data
$warga = db()->fetchOne("SELECT * FROM warga WHERE id_warga = ?", [$idWarga]);

if (!$warga) {
    setFlash('danger', 'Data warga tidak ditemukan');
    redirect('warga.php');
}

// Get penilaian detail
$penilaian = db()->fetchAll("
    SELECT p.*, k.nama_kriteria, k.bobot_global
    FROM penilaian p
    JOIN kriteria k ON p.id_kriteria = k.id_kriteria
    WHERE p.id_warga = ?
    ORDER BY k.id_kriteria
", [$idWarga]);

// Get hasil AHP
$hasil = db()->fetchOne("SELECT * FROM hasil_ahp WHERE id_warga = ?", [$idWarga]);

// Get sub kriteria labels
$subDetails = [];
foreach ($penilaian as $p) {
    $sk = db()->fetchOne(
        "SELECT * FROM sub_kriteria WHERE id_kriteria = ? AND bobot = ?",
        [$p['id_kriteria'], $p['bobot_sub']]
    );
    $subDetails[$p['id_kriteria']] = $sk;
}

include 'templates/header.php';
include 'templates/sidebar.php';
?>

<div class="container-fluid">
    <!-- Navigation -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="warga.php">Data Warga</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>

    <h2 class="h3 mb-4">👤 Detail Warga</h2>

    <div class="row g-4">
        <!-- Profile Card -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="display-1 mb-3">👤</div>
                    <h4><?= htmlspecialchars($warga['nama']) ?></h4>
                    <?php if ($hasil): ?>
                        <span class="badge bg-<?= getKategoriColor($hasil['kategori']) ?> fs-6 mb-2">
                            <?= $hasil['kategori'] ?>
                        </span>
                        <p class="text-muted">Ranking #<?= $hasil['ranking'] ?></p>
                    <?php endif; ?>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>NIK</span>
                        <span class="font-monospace"><?= $warga['nik'] ?? '-' ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>No. Telepon</span>
                        <span><?= $warga['no_telepon'] ?? '-' ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Alamat</span>
                        <span class="text-end"><?= $warga['alamat'] ? htmlspecialchars($warga['alamat']) : '-' ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Terdaftar</span>
                        <span><?= date('d M Y', strtotime($warga['created_at'])) ?></span>
                    </li>
                </ul>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="penilaian.php?id_warga=<?= $warga['id_warga'] ?>" class="btn btn-primary">
                            📝 <?= $hasil ? 'Update Penilaian' : 'Input Penilaian' ?>
                        </a>
                        <a href="warga.php" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>

            <?php if ($hasil): ?>
            <!-- Score Summary -->
            <div class="card shadow-sm mt-3 bg-light">
                <div class="card-body text-center">
                    <h6>Total Skor AHP</h6>
                    <h1 class="text-primary display-4"><?= $hasil['total_skor'] ?></h1>
                    <div class="progress mt-3" style="height: 25px;">
                        <div class="progress-bar bg-<?= getKategoriColor($hasil['kategori']) ?>" 
                             style="width: <?= min($hasil['total_skor'] / 50 * 100, 100) ?>%">
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Detail Penilaian -->
        <div class="col-lg-8">
            <?php if (!$hasil): ?>
            <div class="alert alert-warning">
                <h5>⚠️ Belum Ada Penilaian</h5>
                <p>Warga ini belum memiliki data penilaian AHP. Silakan lakukan penilaian untuk mendapatkan rekomendasi.</p>
                <a href="penilaian.php?id_warga=<?= $warga['id_warga'] ?>" class="btn btn-primary">
                    Input Penilaian Sekarang
                </a>
            </div>
            <?php else: ?>
            
            <!-- AHP Details -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">📊 Detail Perhitungan AHP</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Nilai Input</th>
                                    <th>Bobot Global</th>
                                    <th>Kategori</th>
                                    <th>Bobot Sub</th>
                                    <th class="text-end">Nilai AHP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total = 0;
                                foreach ($penilaian as $p): 
                                    $nilaiAHP = $p['bobot_sub'] * $p['bobot_global'] * 100;
                                    $total += $nilaiAHP;
                                    $sub = $subDetails[$p['id_kriteria']] ?? null;
                                ?>
                                <tr>
                                    <td>
                                        <strong><?= $p['id_kriteria'] ?></strong><br>
                                        <small><?= $p['nama_kriteria'] ?></small>
                                    </td>
                                    <td>
                                        <?php if ($p['id_kriteria'] === 'IDK01'): ?>
                                            <?= formatRupiah($p['nilai_input']) ?>/bulan
                                        <?php elseif ($p['id_kriteria'] === 'IDK02'): ?>
                                            <?= intval($p['nilai_input']) ?> orang
                                        <?php elseif ($p['id_kriteria'] === 'IDK03'): ?>
                                            <?= getStatusBPJSLabel($p['nilai_input']) ?>
                                        <?php elseif ($p['id_kriteria'] === 'IDK04'): ?>
                                            <?= intval($p['nilai_input']) ?> tahun
                                        <?php else: ?>
                                            <?= intval($p['nilai_input']) ?> (<?= $sub['nama_sub'] ?>)
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $p['bobot_global'] ?> (<?= round($p['bobot_global'] * 100, 2) ?>%)</td>
                                    <td>
                                        <span class="badge bg-<?= $p['bobot_sub'] == 0.419 ? 'success' : ($p['bobot_sub'] == 0.263 ? 'warning' : 'secondary') ?>">
                                            <?= $sub['nama_sub'] ?? '-' ?>
                                        </span>
                                    </td>
                                    <td><?= $p['bobot_sub'] ?></td>
                                    <td class="text-end fw-bold"><?= round($p['nilai_ahp'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-primary">
                                <tr>
                                    <th colspan="5" class="text-end">TOTAL SKOR:</th>
                                    <th class="text-end fs-5"><?= $hasil['total_skor'] ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recommendation -->
            <div class="card shadow-sm border-<?= getKategoriColor($hasil['kategori']) ?>">
                <div class="card-header bg-<?= getKategoriColor($hasil['kategori']) ?> text-white">
                    <h5 class="mb-0">✅ Rekomendasi</h5>
                </div>
                <div class="card-body">
                    <h4>Kategori: <?= $hasil['kategori'] ?></h4>
                    <p class="mb-0">
                        <?php
                        switch ($hasil['kategori']) {
                            case 'Prioritas 1':
                                echo "Warga ini masuk dalam <strong>kategori Prioritas 1</strong> dan direkomendasikan untuk segera diproses dalam program BPJS PBI. Skor yang tinggi menunjukkan kondisi ekonomi yang sangat membutuhkan bantuan.";
                                break;
                            case 'Prioritas 2':
                                echo "Warga ini masuk dalam <strong>kategori Prioritas 2</strong> dan dapat diproses setelah Prioritas 1. Kondisi ekonominya memenuhi syarat untuk bantuan BPJS PBI.";
                                break;
                            case 'Prioritas 3':
                                echo "Warga ini masuk dalam <strong>daftar tunggu (Prioritas 3)</strong>. Direkomendasikan untuk diproses setelah Prioritas 1 dan 2 selesai.";
                                break;
                            default:
                                echo "Sayangnya, warga ini <strong>belum memenuhi syarat</strong> untuk program BPJS PBI berdasarkan kriteria yang telah ditentukan.";
                        }
                        ?>
                    </p>
                </div>
            </div>

            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>