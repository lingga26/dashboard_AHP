<?php  
// =========================================================  
// STATISTIK - BPJS PBI AHP SYSTEM  
// ========================================================= 

// Perbaikan Poin 6: Gunakan pengecekan agar tidak muncul error session_start[cite: 1, 4]
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/functions.php'; 
require_once 'includes/AHPCalculator.php'; 

// Proteksi Halaman: Jika belum login, lempar ke login.php
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

$pageTitle = 'Statistik'; 
$ahp = new AHPCalculator(); 
$statistik = $ahp->getStatistik(); 

// Ambil data distribusi skor untuk grafik
$scoreDist = db()->fetchAll("
    SELECT 
        CASE 
            WHEN total_skor >= 35 THEN '35+'
            WHEN total_skor >= 30 THEN '30-34'
            WHEN total_skor >= 25 THEN '25-29'
            WHEN total_skor >= 20 THEN '20-24'
            WHEN total_skor >= 15 THEN '15-19'
            ELSE '< 15'
        END as range_skor,
        COUNT(*) as jumlah
    FROM hasil_ahp
    GROUP BY range_skor
    ORDER BY range_skor DESC
");

include 'templates/header.php'; 
include 'templates/sidebar.php'; 
?>

<div class="container-fluid">
    <h2 class="h3 mb-4"> Statistik & Analisis AHP</h2>
    
    <!-- Poin 5: Tampilkan statistik dalam bentuk persentase[cite: 4] -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3">
            <div class="card bg-primary text-white text-center p-3">
                <h1 class="display-4"><?= $statistik['total'] ?></h1>
                <p class="mb-0">Total Warga</p>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card bg-success text-white text-center p-3">
                <h1 class="display-4"><?= $statistik['rata_rata'] ?></h1>
                <p class="mb-0">Rata-rata Skor</p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card bg-info text-white text-center p-3">
                <?php 
                $rekomendasi = ($statistik['kategori']['Prioritas 1']['jumlah'] ?? 0) + ($statistik['kategori']['Prioritas 2']['jumlah'] ?? 0);
                $persen = $statistik['total'] > 0 ? round(($rekomendasi / $statistik['total']) * 100) : 0;
                ?>
                <h1 class="display-4"><?= $persen ?>%</h1>
                <p class="mb-0">Warga Layak Rekomendasi (Prioritas 1 & 2)</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Grafik Distribusi Kategori[cite: 1] -->
        <div class="col-lg-6">
            <div class="card shadow-sm p-3">
                <h5> Distribusi Kategori</h5>
                <canvas id="kategoriChart"></canvas>
            </div>
        </div>
        <!-- Grafik Distribusi Skor[cite: 1] -->
        <div class="col-lg-6">
            <div class="card shadow-sm p-3">
                <h5> Tren Distribusi Skor</h5>
                <canvas id="skorChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Integration[cite: 1] -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart Kategori
new Chart(document.getElementById('kategoriChart'), {
    type: 'doughnut',
    data: {
        labels: ['Prioritas 1', 'Prioritas 2', 'Prioritas 3', 'Tidak Layak'],
        datasets: [{
            data: [
                <?= $statistik['kategori']['Prioritas 1']['jumlah'] ?? 0 ?>,
                <?= $statistik['kategori']['Prioritas 2']['jumlah'] ?? 0 ?>,
                <?= $statistik['kategori']['Prioritas 3']['jumlah'] ?? 0 ?>,
                <?= $statistik['kategori']['Tidak Layak']['jumlah'] ?? 0 ?>
            ],
            backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545']
        }]
    }
});

// Chart Skor
new Chart(document.getElementById('skorChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($scoreDist, 'range_skor')) ?>,
        datasets: [{
            label: 'Jumlah Warga',
            data: <?= json_encode(array_column($scoreDist, 'jumlah')) ?>,
            borderColor: '#4e73df',
            tension: 0.3,
            fill: true,
            backgroundColor: 'rgba(78, 115, 223, 0.1)'
        }]
    }
});
</script>

<?php include 'templates/footer.php'; ?>