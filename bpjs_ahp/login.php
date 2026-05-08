<?php
require_once 'includes/functions.php';

// Debugging: Cek apakah session folder bisa ditulis (Opsional)
if (!is_writable(session_save_path())) {
    // Jika session error, ini salah satu penyebab gagal login
}

$error = "";

if (isset($_POST['login'])) {
    // Kita ambil input mentah dulu untuk perbandingan pasti
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    // LOGIKA LOGIN UTAMA[cite: 4]
    if ($user === 'admin' && $pass === 'pondokgede2026') {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_admin'] = 'admin';
        
        // Pastikan tidak ada spasi sebelum header
        header("Location: index.php");
        exit();
    } else {
        $error = "Username atau Password salah! (Input: $user)";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login | BPJS PBI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">
    <div class="card shadow-sm mx-auto" style="width: 350px; border-radius: 10px;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <h5 class="fw-bold">BPJS PBI AHP</h5>
                <small class="text-muted">Kecamatan Pondokgede</small>
            </div>
            <hr>
            
            <?php if ($error): ?>
                <div class="alert alert-danger small py-2"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label class="small fw-bold">Username</label>
                    <input type="text" name="user" class="form-control" autocomplete="off" required>
                </div>
                <div class="mb-4">
                    <label class="small fw-bold">Password</label>
                    <input type="password" name="pass" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">MASUK</button>
            </form>
        </div>
    </div>
</body>
</html>