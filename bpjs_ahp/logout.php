<?php
// =========================================================  
// LOGOUT - BPJS PBI AHP SYSTEM  
// ========================================================= 

// 1. Mulai sesi jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Kosongkan semua data (variabel) di dalam sesi saat ini
$_SESSION = [];

// 3. Hapus cookie sesi dari browser (opsional tapi disarankan untuk keamanan)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Hancurkan sesi sepenuhnya dari server
session_destroy();

// 5. Arahkan kembali pengguna ke halaman Login
header("Location: login.php");
exit;
?>