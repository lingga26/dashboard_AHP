</div><!-- /.main-content -->

<footer>
    <div class="container">
        <p class="mb-1">
            <strong><?= APP_NAME ?></strong> - Kecamatan <?= KECAMATAN ?>
        </p>
        <p class="mb-0 text-muted">
            Sistem Pendukung Keputusan dengan Metode AHP (Analytic Hierarchy Process)
        </p>
    </div>
</footer>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Additional Scripts -->
<script>
// Initialize tooltips
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
    new bootstrap.Tooltip(el);
});

// Confirmation dialog
function confirmDelete(message = 'Apakah Anda yakin ingin menghapus data ini?') {
    return confirm(message);
}

// Format number inputs
function formatNumber(input) {
    input.value = input.value.replace(/[^0-9]/g, '');
}
</script>

</body>
</html>
