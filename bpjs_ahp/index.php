<?php
/**
 * =========================================================
 * DASHBOARD - BPJS PBI AHP SYSTEM
 * =========================================================
 */

// ... kode asli file Anda tetap di bawah ini ...
session_start();
require_once 'includes/functions.php';
require_once 'includes/AHPCalculator.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$ahp = new AHPCalculator();
$statistik = $ahp->getStatistik();
$pageTitle = 'Dashboard';

// Get recent warga
$recentWarga = db()->fetchAll("
    SELECT w.*, h.total_skor, h.kategori, h.ranking
    FROM warga w
    LEFT JOIN hasil_ahp h ON w.id_warga = h.id_warga
    ORDER BY w.created_at DESC
    LIMIT 5
");

// Get top priority
$topPriority = db()->fetchAll("
    SELECT w.*, h.total_skor, h.kategori, h.ranking
    FROM warga w
    JOIN hasil_ahp h ON w.id_warga = h.id_warga
    WHERE h.kategori = 'Prioritas 1'
    ORDER BY h.total_skor DESC
    LIMIT 5
");

include 'templates/header.php';
include 'templates/sidebar.php';
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0">📊 Dashboard</h2>
        <a href="penilaian.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Input Penilaian Baru
        </a>
    </div>

    <!-- Flash Message -->
    <?php $flash = getFlash(); if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show">
        <?= $flash['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Statistik Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Warga -->
        <div class="col-md-3">
            <div class="dashboard-card bg-primary-gradient">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-white-75">Total Warga</h6>
                        <h3 class="mb-0 text-white"><?= $statistik['total'] ?></h3>
                    </div>
                    <div class="icon-box bg-white bg-opacity-25">
                        👥
                    </div>
                </div>
            </div>
        </div>

        <!-- Prioritas 1 -->
        <div class="col-md-3">
            <div class="dashboard-card bg-success-gradient">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-white-75">Prioritas 1</h6>
                        <h3 class="mb-0 text-white">
                            <?= $statistik['kategori']['Prioritas 1']['jumlah'] ?? 0 ?>
                        </h3>
                        <small class="text-white-75">
                            <?= $statistik['kategori']['Prioritas 1']['rata_skor'] ?? 0 ?> rata-rata
                        </small>
                    </div>
                    <div class="icon-box bg-white bg-opacity-25">
                        🟢
                    </div>
                </div>
            </div>
        </div>

        <!-- Prioritas 2 -->
        <div class="col-md-3">
            <div class="dashboard-card bg-warning-gradient">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-dark-75">Prioritas 2</h6>
                        <h3 class="mb-0 text-dark">
                            <?= $statistik['kategori']['Prioritas 2']['jumlah'] ?? 0 ?>
                        </h3>
                        <small class="text-dark-75">
                            <?= $statistik['kategori']['Prioritas 2']['rata_skor'] ?? 0 ?> rata-rata
                        </small>
                    </div>
                    <div class="icon-box bg-dark bg-opacity-10">
                        🟡
                    </div>
                </div>
            </div>
        </div>

        <!-- Rata-rata Skor -->
        <div class="col-md-3">
            <div class="dashboard-card bg-info-gradient">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-white-75">Rata-rata Skor</h6>
                        <h3 class="mb-0 text-white"><?= $statistik['rata_rata'] ?></h3>
                    </div>
                    <div class="icon-box bg-white bg-opacity-25">
                        📈
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Chart Section -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">📊 Distribusi Kategori Hasil</h5>
                </div>
                <div class="card-body">
                    <canvas id="kategoriChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">📋 Ringkasan Statistik</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <?php foreach ($GLOBALS['AHP_KATEGORI'] as $kat): 
                            $jumlah = $statistik['kategori'][$kat['kategori']]['jumlah'] ?? 0;
                            $persen = $statistik['total'] > 0 ? round($jumlah / $statistik['total'] * 100, 1) : 0;
                        ?>
                        <tr>
                            <td><?= $kat['label'] ?></td>
                            <td class="text-end fw-bold"><?= $jumlah ?> <small class="text-muted">(<?= $persen ?>%)</small></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <div class="card-footer bg-light">
                    <div class="alert alert-info mb-0">
                        <strong>Total yang Direkomendasikan:</strong><br>
                        <?= ($statistik['kategori']['Prioritas 1']['jumlah'] ?? 0) + 
                           ($statistik['kategori']['Prioritas 2']['jumlah'] ?? 0) + 
                           ($statistik['kategori']['Prioritas 3']['jumlah'] ?? 0) ?> warga 
                        (<?= $statistik['total'] > 0 ? round((($statistik['kategori']['Prioritas 1']['jumlah'] ?? 0) + 
                           ($statistik['kategori']['Prioritas 2']['jumlah'] ?? 0) + 
                           ($statistik['kategori']['Prioritas 3']['jumlah'] ?? 0)) / $statistik['total'] * 100, 1) : 0 ?>%)
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Priority Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">🌟 5 Warga Prioritas Tertinggi</h5>
                    <a href="hasil.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 text-center">
                        <thead class="table-light">
                            <tr>
                                <th>Ranking</th>
                                <th>Nama</th>
                                <th>Total Skor</th>
                                <th>Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topPriority as $w): ?>
                            <tr>
                                <td class="fw-bold">#<?= $w['ranking'] ?></td>
                                <td class="text-start"><?= htmlspecialchars($w['nama']) ?></td>
                                <td class="fw-bold text-primary"><?= $w['total_skor'] ?></td>
                                <td>
                                    <span class="badge bg-<?= getKategoriColor($w['kategori']) ?>">
                                        <?= $w['kategori'] ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="warga_detail.php?id=<?= $w['id_warga'] ?>" class="btn btn-sm btn-info">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const kategoriData = {
    labels: ['Prioritas 1', 'Prioritas 2', 'Prioritas 3', 'Tidak Layak'],
    datasets: [{
        data: [
            <?= $statistik['kategori']['Prioritas 1']['jumlah'] ?? 0 ?>,
            <?= $statistik['kategori']['Prioritas 2']['jumlah'] ?? 0 ?>,
            <?= $statistik['kategori']['Prioritas 3']['jumlah'] ?? 0 ?>,
            <?= $statistik['kategori']['Tidak Layak']['jumlah'] ?? 0 ?>
        ],
        backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545'],
        borderWidth: 0
    }]
};

new Chart(document.getElementById('kategoriChart'), {
    type: 'doughnut',
    data: kategoriData,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

<?php include 'templates/footer.php'; ?>