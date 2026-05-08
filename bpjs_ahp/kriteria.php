<?php  
// Proteksi halaman dan sesi (Poin 6)[cite: 1, 4]
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/functions.php'; 

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

$pageTitle = 'Kriteria AHP'; 
// Ambil data kriteria dari database
$kriteria = db()->fetchAll("SELECT * FROM kriteria ORDER BY id_kriteria");
$subKriteria = db()->fetchAll("SELECT * FROM sub_kriteria ORDER BY id_kriteria, bobot DESC");

include 'templates/header.php'; 
include 'templates/sidebar.php'; 
?>

<div class="container-fluid">
    <h2 class="h3 mb-4"> Referensi Nilai Sub Kriteria</h2>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kriteria</th>
                            <th>Sangat Prioritas (0.419)</th>
                            <th>Prioritas (0.263)</th>
                            <th>Cukup (0.161)</th>
                            <th>Kurang (0.097)</th>
                            <th>Tidak (0.060)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kriteria as $k): 
                            // Mengelompokkan sub kriteria agar tidak error presisi[cite: 1]
                            $subs = array_filter($subKriteria, fn($sk) => $sk['id_kriteria'] === $k['id_kriteria']);
                            $subMap = [];
                            foreach ($subs as $sk) {
                                // Gunakan casting ke string pada key array agar PHP tidak memaksa ke integer
                                $key = (string)$sk['bobot']; 
                                $subMap[$key] = $sk['deskripsi'];
                            }
                        ?>
                        <tr>
                            <td>
                                <strong><?= $k['id_kriteria'] ?></strong><br>
                                <small><?= $k['nama_kriteria'] ?></small>
                            </td>
                            <!-- Perbaikan Error Line 165-169: Akses menggunakan key string[cite: 1] -->
                            <td><?= $subMap['0.4190'] ?? $subMap['0.419'] ?? '-' ?></td>
                            <td><?= $subMap['0.2630'] ?? $subMap['0.263'] ?? '-' ?></td>
                            <td><?= $subMap['0.1610'] ?? $subMap['0.161'] ?? '-' ?></td>
                            <td><?= $subMap['0.0970'] ?? $subMap['0.097'] ?? '-' ?></td>
                            <td><?= $subMap['0.0600'] ?? $subMap['0.06'] ?? '-' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>