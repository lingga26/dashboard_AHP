<!-- Sidebar -->
<nav class="sidebar">
    <div class="sidebar-brand">
        <!-- Poin 6: Branding Kecamatan Pondokgede[cite: 4] -->
        <a href="index.php" style="text-decoration: none; color: white;">
            <div class="mb-2 text-center">
                <img src="assets/images/logo.png" alt="Logo Kecamatan Pondok Gede" style="width: 60px; height: auto;">
            </div>
            <h4><?= APP_NAME ?></h4>
            <small>Metode AHP</small><br>
            <small>Kecamatan <?= KECAMATAN ?></small>
        </a>
    </div>
         
    <div class="nav flex-column py-3">
        <?php 
        // Mengambil nama file saat ini untuk menentukan class 'active'[cite: 1]
        $currentPage = basename($_SERVER['PHP_SELF']); 
        ?>

        <!-- Link Dashboard (Poin 1)[cite: 4] -->
        <a class="nav-link <?= ($currentPage == 'index.php') ? 'active' : '' ?>" href="index.php">
            <span><i class="fas fa-chart-line"></i></span>
            Dashboard
        </a>

        <!-- Link Data Warga (Poin 2 & 3)[cite: 4] -->
        <a class="nav-link <?= ($currentPage == 'warga.php' || $currentPage == 'warga_detail.php') ? 'active' : '' ?>" href="warga.php">
            <span><i class="fas fa-users"></i></span>
            Data Warga
        </a>

        <!-- Link Input Penilaian[cite: 1] -->
        <a class="nav-link <?= ($currentPage == 'penilaian.php') ? 'active' : '' ?>" href="penilaian.php">
            <span><i class="fas fa-edit"></i></span>
            Input Penilaian
        </a>

        <!-- Link Hasil Perhitungan (Poin 4 & 7)[cite: 4] -->
        <a class="nav-link <?= ($currentPage == 'hasil.php') ? 'active' : '' ?>" href="hasil.php">
            <span><i class="fas fa-file-invoice"></i></span>
            Hasil Perhitungan
        </a>

        <!-- Link Statistik (Poin 5)[cite: 4] -->
        <a class="nav-link <?= ($currentPage == 'statistik.php') ? 'active' : '' ?>" href="statistik.php">
            <span><i class="fas fa-chart-pie"></i></span>
            Statistik
        </a>

        <!-- Link Kriteria AHP[cite: 1] -->
        <a class="nav-link <?= ($currentPage == 'kriteria.php') ? 'active' : '' ?>" href="kriteria.php">
            <span><i class="fas fa-cogs"></i></span>
            Kriteria AHP
        </a>

        <hr class="mx-3 text-white-50">

        <!-- Tombol Logout[cite: 4] -->
        <a class="nav-link text-warning" href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
            <span><i class="fas fa-sign-out-alt"></i></span>
            Keluar
        </a>
    </div>
         
    <div class="mt-auto p-3 text-center">
        <small class="text-white-50">
            v<?= APP_VERSION ?><br>
            © <?= date('Y') ?> Sistem AHP
        </small>
    </div>
</nav>

<!-- Main Content Wrapper (Pembuka yang akan ditutup di footer.php) -->
<div class="main-content">