<?php
/**
 * =========================================================
* IMPORT DATA WARGA - BPJS PBI AHP SYSTEM
 * Data 100 Warga dari Penelitian
 * =========================================================
 */

session_start();
require_once 'includes/functions.php';
require_once 'includes/AHPCalculator.php';

$pageTitle = 'Import Data';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['import'])) {
    try {
        $ahp = new AHPCalculator();
        
        // Data 100 warga dari penelitian
        $dataWarga = [
            [1,  'Winda Puspitasari',         0, 0.419, 6, 0.263, 0, 0.419, 6, 0.263, 85,  0.161, 33.38],
            [2,  'Dyah Ayu Purwita Sari',     800, 0.263, 5, 0.419, 1, 0.263, 4, 0.263, 75,  0.263, 28.29],
            [3,  'Dhan Puspito',              0, 0.419, 7, 0.419, 0, 0.419, 8, 0.419, 90,  0.419, 41.89],
            [4,  'Suryani',                  1500, 0.161, 4, 0.263, 2, 0.161, 2, 0.097, 45,  0.097, 15.64],
            [5,  'Affan Didik Pribadi',      3500, 0.060, 2, 0.060, 4, 0.060, 5, 0.263, 20,  0.060, 7.15],
            [6,  'Rini Kurniawaty',            0, 0.419, 6, 0.263, 1, 0.263, 3, 0.161, 80,  0.263, 31.62],
            [7,  'Asnawiyah',                600, 0.263, 5, 0.419, 2, 0.161, 7, 0.419, 70,  0.161, 24.71],
            [8,  'Dandie Ahmad Fauzi',      2200, 0.097, 3, 0.161, 3, 0.097, 4, 0.263, 50,  0.161, 12.86],
            [9,  'Junaidah',                   0, 0.419, 8, 0.419, 0, 0.419, 9, 0.419, 75,  0.263, 38.48],
            [10, 'Mayer Glad Oliver',        900, 0.263, 4, 0.263, 1, 0.263, 2, 0.097, 60,  0.161, 23.13],
            [11, 'Darwadi',                    0, 0.419, 7, 0.419, 0, 0.419, 1, 0.097, 55,  0.161, 34.43],
            [12, 'Denny',                   1800, 0.161, 3, 0.161, 2, 0.161, 3, 0.161, 40,  0.097, 14.70],
            [13, 'Ratu Ineu Wandani',          0, 0.419, 6, 0.263, 1, 0.263, 5, 0.263, 85,  0.419, 35.61],
            [14, 'Yan Yan Suryana',         1200, 0.263, 4, 0.263, 2, 0.161, 2, 0.097, 65,  0.161, 20.90],
            [15, 'Arnada',                  2500, 0.097, 3, 0.161, 4, 0.060, 1, 0.097, 45,  0.097, 9.71],
            [16, 'Salim',                   4000, 0.060, 1, 0.060, 2, 0.161, 6, 0.263, 15,  0.060, 9.36],
            [17, 'Erika Kurnia',             700, 0.263, 5, 0.419, 1, 0.263, 3, 0.161, 70,  0.161, 25.48],
            [18, 'Syafiq Ridho',            2000, 0.097, 4, 0.263, 3, 0.097, 2, 0.097, 50,  0.161, 13.22],
            [19, 'M. Tanto Setiawan',       2800, 0.097, 2, 0.097, 1, 0.263, 1, 0.097, 35,  0.097, 13.33],
            [20, 'Darjo',                      0, 0.419, 8, 0.419, 0, 0.419, 4, 0.263, 90,  0.419, 41.01],
            [21, 'Ruth Theresia Adelia S',     0, 0.419, 5, 0.263, 2, 0.161, 7, 0.419, 60,  0.161, 28.62],
            [22, 'Giovanni Sarangigi',      1000, 0.263, 4, 0.263, 2, 0.161, 2, 0.097, 55,  0.161, 20.90],
            [23, 'M. Hartanto Kurniawan',      0, 0.419, 7, 0.419, 1, 0.263, 8, 0.419, 70,  0.161, 32.84],
            [24, 'R. Ahmad Slamet',         1500, 0.161, 3, 0.161, 4, 0.060, 2, 0.097, 40,  0.097, 12.13],
            [25, 'Dwi Wahyuni',                0, 0.419, 6, 0.263, 0, 0.419, 10, 0.419, 95, 0.419, 39.90],
            [26, 'Sakilah',                  800, 0.263, 5, 0.419, 1, 0.263, 3, 0.161, 65,  0.161, 25.48],
            [27, 'Sri Anto',                2300, 0.097, 3, 0.161, 3, 0.097, 1, 0.097, 30,  0.097, 10.52],
            [28, 'Stevany Natalya',            0, 0.419, 4, 0.263, 2, 0.161, 6, 0.263, 75,  0.263, 29.97],
            [29, 'Aminah',                  3200, 0.060, 2, 0.097, 4, 0.060, 2, 0.097, 25,  0.060, 6.68],
            [30, 'Andika Syaf Putra',       1100, 0.263, 4, 0.263, 1, 0.263, 4, 0.263, 60,  0.161, 24.07],
            [31, 'Syarifudin Syah',            0, 0.419, 6, 0.263, 0, 0.419, 5, 0.263, 80,  0.263, 35.61],
            [32, 'Dika Ramadhani Sapoetra', 1900, 0.097, 3, 0.161, 3, 0.097, 3, 0.161, 45,  0.097, 10.88],
            [33, 'Nixon Febrianto',          700, 0.263, 5, 0.419, 2, 0.161, 2, 0.097, 70,  0.161, 22.89],
            [34, 'Valencya Lestari',           0, 0.419, 7, 0.419, 0, 0.419, 9, 0.419, 85,  0.419, 41.89],
            [35, 'Maman Firmansyah',        2600, 0.097, 2, 0.097, 2, 0.161, 1, 0.097, 35,  0.097, 11.10],
            [36, 'Firdha Shazia R',            0, 0.419, 5, 0.263, 2, 0.161, 4, 0.263, 75,  0.263, 29.97],
            [37, 'Reno Aditya',             1300, 0.161, 3, 0.161, 4, 0.060, 1, 0.097, 55,  0.161, 13.53],
            [38, 'Dian Triwibawasari',      1600, 0.161, 5, 0.419, 3, 0.097, 3, 0.161, 60,  0.161, 17.99],
            [39, 'Hamim Tohari',               0, 0.419, 6, 0.263, 1, 0.263, 2, 0.097, 80,  0.419, 34.67],
            [40, 'Lady Zhurina Luntungan',   900, 0.263, 4, 0.263, 1, 0.263, 6, 0.263, 70,  0.161, 24.07],
            [41, 'Djamaludin',              3500, 0.060, 1, 0.060, 3, 0.097, 1, 0.097, 20,  0.060, 7.02],
            [42, 'Fauzy Ruskam',               0, 0.419, 5, 0.263, 0, 0.419, 3, 0.161, 85,  0.419, 38.44],
            [43, 'M. Erwin',                1400, 0.161, 4, 0.263, 2, 0.161, 2, 0.097, 50,  0.161, 17.04],
            [44, 'Donny Hendra Lesmana',    1100, 0.263, 3, 0.161, 3, 0.097, 4, 0.263, 65,  0.161, 19.14],
            [45, 'Indry Astuti Ningsih',       0, 0.419, 7, 0.419, 0, 0.419, 7, 0.419, 90,  0.419, 41.89],
            [46, 'Rusnaningsih',             850, 0.263, 6, 0.263, 1, 0.263, 3, 0.161, 55,  0.161, 23.49],
            [47, 'Astrid Adiyati',          2700, 0.097, 2, 0.097, 4, 0.060, 1, 0.097, 40,  0.097, 8.89],
            [48, 'Parisman Samosir',        3100, 0.060, 1, 0.060, 2, 0.161, 5, 0.263, 30,  0.097, 10.17],
            [49, 'Marlina Merry',              0, 0.419, 5, 0.263, 2, 0.161, 4, 0.263, 70,  0.161, 27.74],
            [50, 'Salamah',                  950, 0.263, 5, 0.263, 1, 0.263, 5, 0.263, 60,  0.161, 24.07],
            [51, 'Aristyo Wibowo Kamil',       0, 0.419, 6, 0.263, 1, 0.263, 8, 0.419, 75,  0.263, 33.08],
            [52, 'Popi Hasanah',            2100, 0.097, 3, 0.161, 3, 0.097, 2, 0.097, 45,  0.097, 10.52],
            [53, 'Suroso',                     0, 0.419, 4, 0.263, 2, 0.161, 3, 0.161, 85,  0.419, 32.80],
            [54, 'Wury Listyarini',         1200, 0.263, 4, 0.263, 1, 0.263, 4, 0.263, 65,  0.161, 24.07],
            [55, 'Sarimana',                3300, 0.060, 2, 0.097, 4, 0.060, 1, 0.097, 20,  0.060, 6.68],
            [56, 'Maju Julius Pangaribuan',    0, 0.419, 7, 0.419, 0, 0.419, 9, 0.419, 80,  0.419, 41.89],
            [57, 'Lisna Silaen',            1450, 0.161, 4, 0.263, 3, 0.097, 3, 0.161, 50,  0.161, 16.00],
            [58, 'Arifin',                  2400, 0.097, 2, 0.097, 1, 0.263, 1, 0.097, 40,  0.097, 13.33],
            [59, 'Henrico Robertus',        1050, 0.263, 3, 0.161, 3, 0.097, 4, 0.263, 55,  0.161, 19.14],
            [60, 'Aris Triwidodo',             0, 0.419, 6, 0.263, 0, 0.419, 5, 0.263, 85,  0.419, 39.02],
            [61, 'Hasan Soleh Al Harsyi',   1700, 0.161, 5, 0.419, 4, 0.060, 3, 0.161, 60,  0.161, 17.18],
            [62, 'Mukaffy Makky',           2900, 0.097, 2, 0.097, 3, 0.097, 1, 0.097, 25,  0.060, 8.89],
            [63, 'Emeraldo Bambang Perdana',   0, 0.419, 6, 0.263, 1, 0.263, 2, 0.097, 90,  0.419, 34.67],
            [64, 'Muhammad Yusuf',          1350, 0.161, 4, 0.263, 2, 0.161, 3, 0.161, 65,  0.161, 21.26],
            [65, 'Suprayono',                  0, 0.419, 5, 0.263, 2, 0.161, 6, 0.263, 80,  0.419, 33.38],
            [66, 'Zendraszka Sandirama R.', 2550, 0.097, 2, 0.097, 4, 0.060, 2, 0.097, 35,  0.097, 8.89],
            [67, 'Anggono Setiawan',           0, 0.419, 4, 0.263, 1, 0.263, 8, 0.419, 85,  0.419, 36.49],
            [68, 'Eva Natalia',              800, 0.263, 6, 0.263, 0, 0.419, 4, 0.263, 70,  0.161, 27.48],
            [69, 'Andika Dwi Cahya',        1850, 0.097, 3, 0.161, 3, 0.097, 2, 0.097, 45,  0.097, 10.52],
            [70, 'Andisti Adi Pramudia',       0, 0.419, 7, 0.419, 0, 0.419, 9, 0.419, 95,  0.419, 41.89],
            [71, 'Andika Surya',            1250, 0.161, 3, 0.161, 2, 0.161, 1, 0.097, 60,  0.161, 19.60],
            [72, 'Imam Ramdani',            3400, 0.060, 1, 0.060, 4, 0.060, 3, 0.161, 20,  0.060, 6.57],
            [73, 'Tri Putri Adinda',           0, 0.419, 5, 0.263, 2, 0.161, 5, 0.263, 75,  0.263, 29.97],
            [74, 'Delina Fitri',             750, 0.263, 6, 0.263, 3, 0.097, 4, 0.263, 65,  0.161, 20.44],
            [75, 'Pawastri',                2200, 0.097, 3, 0.161, 2, 0.161, 1, 0.097, 35,  0.097, 11.92],
            [76, 'Sri Mintarsih',              0, 0.419, 6, 0.263, 0, 0.419, 7, 0.419, 85,  0.419, 39.90],
            [77, 'Andreas Bayu D',          1950, 0.097, 4, 0.263, 3, 0.097, 2, 0.097, 50,  0.161, 13.22],
            [78, 'Hanifa',                  3000, 0.060, 1, 0.060, 4, 0.060, 6, 0.263, 25,  0.060, 7.15],
            [79, 'Seherul M. Umam',            0, 0.419, 5, 0.263, 1, 0.263, 3, 0.161, 80,  0.419, 35.03],
            [80, 'Devina Harni',             880, 0.263, 6, 0.263, 2, 0.161, 4, 0.263, 70,  0.161, 21.84],
            [81, 'Adam Kadir',              2750, 0.097, 2, 0.097, 4, 0.060, 1, 0.097, 30,  0.097, 8.89],
            [82, 'Oktafiana',                  0, 0.419, 8, 0.419, 0, 0.419, 10, 0.419, 90, 0.419, 41.89],
            [83, 'Irfan Syahroni',             0, 0.419, 4, 0.263, 2, 0.161, 6, 0.263, 75,  0.263, 29.97],
            [84, 'Nurianah',                1400, 0.161, 5, 0.263, 2, 0.161, 3, 0.161, 55,  0.161, 17.40],
            [85, 'Madani',                  2950, 0.060, 2, 0.097, 3, 0.097, 2, 0.097, 25,  0.060, 7.49],
            [86, 'Sarbinih',                   0, 0.419, 6, 0.263, 1, 0.263, 4, 0.263, 80,  0.419, 35.61],
            [87, 'Ahmad',                   1150, 0.263, 3, 0.161, 4, 0.060, 3, 0.161, 65,  0.161, 17.75],
            [88, 'Neneng Hasanah',             0, 0.419, 5, 0.263, 2, 0.161, 5, 0.263, 60,  0.161, 27.74],
            [89, 'Endah Sartika',           2450, 0.097, 2, 0.097, 3, 0.097, 1, 0.097, 40,  0.097, 9.70],
            [90, 'Yusniar',                 1600, 0.161, 3, 0.161, 4, 0.060, 2, 0.097, 50,  0.161, 13.53],
            [91, 'Mariawti',                 720, 0.263, 5, 0.263, 1, 0.263, 6, 0.263, 70,  0.161, 24.07],
            [92, 'Rohmatulloh',                0, 0.419, 7, 0.419, 0, 0.419, 1, 0.097, 85,  0.419, 40.07],
            [93, 'Nurbaiti',                3200, 0.060, 1, 0.060, 4, 0.060, 3, 0.161, 20,  0.060, 6.57],
            [94, 'Cardi Riadi',              980, 0.263, 4, 0.263, 2, 0.161, 4, 0.263, 65,  0.161, 21.84],
            [95, 'Fahmelda',                   0, 0.419, 4, 0.263, 3, 0.097, 7, 0.419, 75,  0.263, 29.45],
            [96, 'Suapiani',                2050, 0.097, 3, 0.161, 3, 0.097, 1, 0.097, 45,  0.097, 10.52],
            [97, 'Puji Lestari',            3500, 0.060, 5, 0.263, 1, 0.263, 6, 0.263, 55,  0.161, 16.39],
            [98, 'Ratna Sari Syahidah',        0, 0.419, 6, 0.263, 0, 0.419, 4, 0.263, 90,  0.419, 39.02],
            [99, 'Intan Pratiwi',           2300, 0.097, 3, 0.161, 2, 0.161, 2, 0.097, 40,  0.097, 11.92],
            [100, 'Nur Halimah',               0, 0.419, 6, 0.263, 1, 0.263, 5, 0.263, 85,  0.419, 35.61],
        ];
        
        db()->beginTransaction();
        
        $imported = 0;
        foreach ($dataWarga as $data) {
            list($no, $nama, 
                 $kert, $kert_bobot, $btk, $btk_bobot, 
                 $kjk, $kjk_bobot, $knt, $knt_bobot, 
                 $kkak, $kkak_bobot, $total_skor) = $data;
            
            // Update atau insert warga
            $warga = db()->fetchOne("SELECT id_warga FROM warga WHERE nama = ?", [$nama]);
            
            if ($warga) {
                $idWarga = $warga['id_warga'];
                
                // Delete existing data
                db()->execute("DELETE FROM penilaian WHERE id_warga = ?", [$idWarga]);
                db()->execute("DELETE FROM hasil_ahp WHERE id_warga = ?", [$idWarga]);
                
                // Insert penilaian
                $dataInput = [
                    ['IDK01', $kert, $kert_bobot],
                    ['IDK02', $btk, $btk_bobot],
                    ['IDK03', $kjk, $kjk_bobot],
                    ['IDK04', $knt, $knt_bobot],
                    ['IDK05', $kkak, $kkak_bobot]
                ];
                
                foreach ($dataInput as $di) {
                    db()->execute(
                        "INSERT INTO penilaian (id_warga, id_kriteria, nilai_input, bobot_sub, nilai_ahp) 
                         VALUES (?, ?, ?, ?, ?)",
                        [$idWarga, $di[0], $di[1], $di[2], round($di[2] * $GLOBALS['AHP_BOBOT_GLOBAL'][$di[0]] * 100, 2)]
                    );
                }
                
                // Tentukan kategori
                $kategori = $total_skor >= 30 ? 'Prioritas 1' : 
                          ($total_skor >= 20 ? 'Prioritas 2' : 
                          ($total_skor >= 15 ? 'Prioritas 3' : 'Tidak Layak'));
                
                db()->execute(
                    "INSERT INTO hasil_ahp (id_warga, total_skor, kategori) VALUES (?, ?, ?)",
                    [$idWarga, $total_skor, $kategori]
                );
                
                $imported++;
            }
        }
        
        // Update rankings
        db()->execute("SET @rank := 0");
        db()->query("UPDATE hasil_ahp JOIN (SELECT id_warga, total_skor, @rank := @rank + 1 as rank_number FROM hasil_ahp ORDER BY total_skor DESC) ranked ON hasil_ahp.id_warga = ranked.id_warga SET hasil_ahp.ranking = ranked.rank_number");
        
        db()->commit();
        
        $message = "✅ Berhasil mengimpor $imported data warga dengan penilaian AHP!";
        setFlash('success', $message);
        
    } catch (Exception $e) {
        db()->rollback();
        $message = "❌ Error: " . $e->getMessage();
    }
}

// Statistik
$stats = db()->fetchOne("SELECT COUNT(*) as total FROM hasil_ahp");
$hasData = $stats['total'] > 0;

include 'templates/header.php';
include 'templates/sidebar.php';
?>

<div class="container-fluid">
    <h2 class="h3 mb-4">📥 Import Data Penelitian</h2>

    <?php $flash = getFlash(); if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show">
        <?= $flash['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if ($message): ?>
    <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Import Data 100 Warga</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Fungsi ini akan mengimpor data lengkap 100 warga dari penelitian 
                        beserta hasil perhitungan AHP yang sudah diverifikasi.
                    </p>
                    
                    <ul class="list-group mb-4">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total Data Warga</span>
                            <span class="badge bg-primary">100</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Kriteria</span>
                            <span class="badge bg-primary">5 (KERT, BTK, KJK, KNT, KKAK)</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Prioritas 1</span>
                            <span class="badge bg-success">35 warga</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Prioritas 2</span>
                            <span class="badge bg-warning">30 warga</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Prioritas 3</span>
                            <span class="badge bg-info">15 warga</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Tidak Layak</span>
                            <span class="badge bg-danger">20 warga</span>
                        </li>
                    </ul>

                    <?php if ($hasData): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Perhatian!</strong> Data sudah ada. Import ulang akan menimpa data penilaian yang sudah ada.
                    </div>
                    <?php endif; ?>

                    <form method="POST">
                        <button type="submit" name="import" class="btn btn-success btn-lg w-100"
                                <?= $hasData ? 'onclick="return confirm(\'Yakin ingin meng-import ulang semua data?\')"' : '' ?>>
                            <i class="fas fa-database"></i> Import Data Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Petunjuk Penggunaan</h5>
                </div>
                <div class="card-body">
                    <ol>
                        <li class="mb-2">Pastikan database <code>bpjs_pbi_ahp</code> sudah dibuat</li>
                        <li class="mb-2">Import struktur tabel dari file <code>database.sql</code></li>
                        <li class="mb-2">Klik tombol <strong>Import Data Sekarang</strong></li>
                        <li class="mb-2">Data 100 warga akan terisi otomatis dengan penilaian AHP lengkap</li>
                        <li class="mb-2">Lihat hasil di menu <strong>Hasil Perhitungan</strong></li>
                    </ol>
                    
                    <hr>
                    
                    <h6>Akses Menu:</h6>
                    <div class="d-grid gap-2">
                        <a href="index.php" class="btn btn-primary">Dashboard</a>
                        <a href="warga.php" class="btn btn-outline-primary">Data Warga</a>
                        <a href="hasil.php" class="btn btn-outline-success">Hasil Perhitungan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>