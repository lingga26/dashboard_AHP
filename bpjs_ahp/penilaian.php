<?php  
// =========================================================  
// INPUT PENILAIAN & WARGA BARU - BPJS PBI AHP SYSTEM  
// ========================================================= 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/functions.php'; 
require_once 'includes/AHPCalculator.php'; 

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

$pageTitle = 'Input Penilaian Baru'; 
$ahp = new AHPCalculator(); 
$hasilPerhitungan = null;

$nama_warga = '';
$nik_warga = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    $nama_warga = isset($_POST['nama_warga']) ? cleanInput($_POST['nama_warga']) : '';
    $nik_warga = isset($_POST['nik_warga']) ? cleanInput($_POST['nik_warga']) : '';
    
    $isPondokGede = (strpos($nik_warga, '327508') === 0);
    $alamat_warga = $isPondokGede ? "Kecamatan Pondokgede" : "Luar Pondokgede";

    $val_kert = isset($_POST['kert']) ? floatval($_POST['kert']) : 0;
    $val_btk  = isset($_POST['btk']) ? intval($_POST['btk']) : 0;
    $val_kjk  = isset($_POST['kjk']) ? intval($_POST['kjk']) : 0;
    $val_knt  = isset($_POST['knt']) ? intval($_POST['knt']) : 0;
    $val_kkak = isset($_POST['kkak']) ? intval($_POST['kkak']) : 0;

    if (!$isPondokGede) {
        $val_knt = 0; 
    }

    $dataInput = array(
        'kert' => $val_kert,
        'btk'  => $val_btk,
        'kjk'  => $val_kjk,
        'knt'  => $val_knt,
        'kkak' => $val_kkak
    );

    if ($action === 'hitung') {
        $hasilPerhitungan = $ahp->hitungAHP($dataInput);
    } elseif ($action === 'simpan') {
        if (strlen($nik_warga) !== 16) {
            setFlash('danger', 'Gagal! NIK harus berjumlah tepat 16 digit.');
        } else {
            try {
                // KITA HAPUS db()->beginTransaction(); AGAR TIDAK CRASH

                $cekWarga = db()->fetchOne("SELECT id_warga FROM warga WHERE nik = ?", [$nik_warga]);

                if ($cekWarga) {
                    $idWarga = $cekWarga['id_warga'];
                    db()->execute(
                        "UPDATE warga SET nama = ?, alamat = ? WHERE id_warga = ?", 
                        [$nama_warga, $alamat_warga, $idWarga]
                    );
                } else {
                    $sqlWarga = "INSERT INTO warga (nama, nik, alamat) VALUES (?, ?, ?)";
                    $idWarga = db()->insert($sqlWarga, [$nama_warga, $nik_warga, $alamat_warga]);
                    
                    if (!$idWarga || $idWarga === true) {
                        $newWarga = db()->fetchOne("SELECT id_warga FROM warga WHERE nik = ?", [$nik_warga]);
                        $idWarga = $newWarga['id_warga'];
                    }
                }

                $result = $ahp->simpanPenilaian($idWarga, $dataInput);
                
                if (isset($result['success']) && $result['success'] == true) {
                    $ahp->updateRanking();
                    // KITA HAPUS db()->commit();
                    setFlash('success', "Data warga ($alamat_warga) berhasil disimpan dan diranking!");
                    header("Location: hasil.php");
                    exit;
                } else {
                    // Jika fungsi simpanPenilaian error, kita tangkap pesan error aslinya dari class AHP
                    $msg = isset($result['message']) ? $result['message'] : "Gagal memasukkan data ke tabel penilaian.";
                    setFlash('danger', 'Gagal Simpan Penilaian: ' . $msg);
                }
            } catch (Exception $e) {
                // KITA HAPUS db()->rollback(); AGAR FATAL ERROR HILANG
                setFlash('danger', 'Error Query SQL: ' . $e->getMessage());
            }
        }
    }
}

include 'templates/header.php'; 
include 'templates/sidebar.php'; 
?>

<div class="container-fluid">
    <h2 class="h3 mb-4">Input Penilaian Warga Baru</h2>
    
    <?php $f = getFlash(); if($f): ?>
        <div class="alert alert-<?= $f['type'] ?> alert-dismissible fade show">
            <?= $f['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-primary">Identitas Calon Penerima</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Nama Lengkap *</label>
                                <input type="text" name="nama_warga" class="form-control" value="<?= htmlspecialchars($nama_warga) ?>" required placeholder="Masukkan nama">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">NIK Warga (16 Digit) *</label>
                                <input type="text" name="nik_warga" class="form-control" value="<?= htmlspecialchars($nik_warga) ?>" required placeholder="Masukkan NIK" maxlength="16">
                                <div class="form-text small text-info">Sistem mendeteksi domisili Pondokgede/Luar otomatis. Jika NIK sudah ada, data akan diupdate.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-primary">Parameter Kriteria AHP</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label small fw-bold">Pendapatan Bulanan (KERT)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="kert" class="form-control" value="<?= isset($_POST['kert']) ? $_POST['kert'] : '' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label small fw-bold">Jumlah Keluarga (BTK)</label>
                                <input type="number" name="btk" class="form-control" value="<?= isset($_POST['btk']) ? $_POST['btk'] : '' ?>" required min="1">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label small fw-bold">Status BPJS (KJK)</label>
                                <select name="kjk" class="form-select" required>
                                    <option value="0" <?= (isset($_POST['kjk']) && $_POST['kjk'] == '0') ? 'selected' : '' ?>>Belum punya BPJS</option>
                                    <option value="1" <?= (isset($_POST['kjk']) && $_POST['kjk'] == '1') ? 'selected' : '' ?>>Nonaktif > 6 Bulan</option>
                                    <option value="2" <?= (isset($_POST['kjk']) && $_POST['kjk'] == '2') ? 'selected' : '' ?>>Nonaktif 3-6 Bulan</option>
                                    <option value="3" <?= (isset($_POST['kjk']) && $_POST['kjk'] == '3') ? 'selected' : '' ?>>Nonaktif < 3 Bulan</option>
                                    <option value="4" <?= (isset($_POST['kjk']) && $_POST['kjk'] == '4') ? 'selected' : '' ?>>Aktif</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label small fw-bold">Lama Domisili (KNT)</label>
                                <div class="input-group">
                                    <input type="number" name="knt" class="form-control" value="<?= isset($_POST['knt']) ? $_POST['knt'] : '' ?>" required min="0">
                                    <span class="input-group-text">Tahun</span>
                                </div>
                            </div>
                            <div class="col-12 mb-4">
                                <label class="form-label small fw-bold">Skor Kesehatan (KKAK): <span id="valKKAK" class="text-primary fw-bold"><?= isset($_POST['kkak']) ? $_POST['kkak'] : 50 ?></span></label>
                                <input type="range" name="kkak" class="form-range" min="0" max="100" value="<?= isset($_POST['kkak']) ? $_POST['kkak'] : 50 ?>" oninput="document.getElementById('valKKAK').innerText = this.value">
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" name="action" value="hitung" class="btn btn-primary btn-lg flex-fill">
                                Hitung Skor AHP
                            </button>
                            <?php if ($hasilPerhitungan): ?>
                                <button type="submit" name="action" value="simpan" class="btn btn-success btn-lg flex-fill">
                                    Simpan & Urutkan
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <?php if ($hasilPerhitungan): ?>
                <div class="card shadow border-0 bg-primary text-white mb-4">
                    <div class="card-body text-center p-4">
                        <p class="small mb-1 opacity-75">Hasil Perhitungan Sementara</p>
                        <h1 class="display-3 fw-bold mb-0"><?= $hasilPerhitungan['total_skor'] ?></h1>
                        <hr class="my-3 opacity-25">
                        <div class="badge bg-white text-primary fs-6 mb-2"><?= $hasilPerhitungan['kategori']['kategori'] ?></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<?php include 'templates/footer.php'; ?>