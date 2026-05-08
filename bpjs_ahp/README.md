# Sistem Penentuan Prioritas BPJS PBI - Metode AHP

## 📋 Deskripsi

Sistem Pendukung Keputusan (SPK) untuk menentukan prioritas penerima BPJS PBI (Penerima Bantuan Iuran) di Kecamatan Pondokgede menggunakan metode AHP (Analytic Hierarchy Process).

## 🎯 Fitur Utama

- ✅ Manajemen Data Warga (CRUD)
- ✅ Input Penilaian 5 Kriteria AHP
- ✅ Perhitungan Otomatis dengan Metode AHP
- ✅ Ranking dan Kategorisasi Hasil
- ✅ Dashboard Statistik Interaktif
- ✅ Export Data

## 🗄️ Struktur Database

### Tabel Utama:
- **kriteria** - 5 kriteria penilaian dengan bobot global
- **sub_kriteria** - Level prioritas untuk setiap kriteria
- **warga** - Data masyarakat penerima
- **penilaian** - Nilai input dan bobot per kriteria
- **hasil_ahp** - Hasil perhitungan skor akhir

### 5 Kriteria AHP:

| ID | Kriteria | Bobot Global |
|----|----------|-------------|
| IDK01 | Kondisi Ekonomi Rumah Tangga (KERT) | 37.82% |
| IDK02 | Beban Tanggungan Keluarga (BTK) | 12.77% |
| IDK03 | Kepemilikan Jaminan Kesehatan (KJK) | 21.87% |
| IDK04 | Kepemilikan NIK Tervalidasi (KNT) | 5.66% |
| IDK05 | Kondisi Kesehatan Anggota Keluarga (KKAK) | 21.87% |

### Bobot Sub Kriteria:

| Level | Bobot |
|-------|-------|
| Sangat Prioritas | 0.419 |
| Prioritas | 0.263 |
| Cukup Prioritas | 0.161 |
| Kurang Prioritas | 0.097 |
| Tidak Prioritas | 0.060 |

## 📊 Kategori Hasil

| Kategori | Skor | Keterangan |
|----------|------|------------|
| 🟢 Prioritas 1 | ≥ 30 | Segera diproses |
| 🟡 Prioritas 2 | 20-29 | Proses berikutnya |
| 🟠 Prioritas 3 | 15-19 | Daftar tunggu |
| 🔴 Tidak Layak | < 15 | Belum memenuhi syarat |

## 🚀 Instalasi

### 1. Database Setup
```bash
# Buka phpMyAdmin atau MySQL CLI
mysql -u root -p

# Import database
source database.sql
```

### 2. Konfigurasi
Edit file `includes/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bpjs_pbi_ahp');
```

### 3. Import Data Contoh (100 Warga)
```
1. Buka browser: http://localhost/bpjs_pbi_ahp/import_data.php
2. Klik "Import Data Sekarang"
3. Data 100 warga dengan penilaian lengkap akan terisi
```

## 📁 Struktur Folder

```
bpjs_pbi_ahp/
├── includes/
│   ├── config.php          # Konfigurasi database
│   ├── database.php        # Koneksi database
│   ├── AHPCalculator.php   # Class perhitungan AHP
│   └── functions.php       # Helper functions
├── templates/
│   ├── header.php          # Header template
│   ├── sidebar.php         # Sidebar navigation
│   └── footer.php          # Footer template
├── index.php               # Dashboard
├── warga.php               # Data warga
├── penilaian.php           # Input penilaian
├── hasil.php              # Hasil perhitungan
├── statistik.php          # Statistik & analisis
├── kriteria.php           # Informasi kriteria
├── warga_detail.php       # Detail warga
├── import_data.php        # Import data 100 warga
└── database.sql           # Struktur database
```

## 🧮 Rumus Perhitungan AHP

```
Nilai AHP = Bobot Global × Bobot Sub Kriteria × 100

Total Skor = Σ (Nilai AHP seluruh kriteria)

Contoh:
KERT (0-500 ribu): 0.3782 × 0.419 × 100 = 15.85
BTK (≥5 orang):    0.1277 × 0.419 × 100 = 5.35
KJK (Belum punya): 0.2187 × 0.419 × 100 = 9.16
KNT (>5 tahun):    0.0566 × 0.419 × 100 = 2.37
KKAK (85-100):     0.2187 × 0.419 × 100 = 9.16
────────────────────────────────────────
Total Skor:        41.89 (Prioritas 1)
```

## 📱 Screenshot

### Dashboard
- Statistik实时
- Grafik distribusi kategori
- Daftar prioritas tertinggi

### Form Penilaian
- Input 5 kriteria
- Preview hasil perhitungan
- Validasi otomatis

### Hasil Perhitungan
- Tabel lengkap dengan ranking
- Filter dan pencarian
- Detail per kriteria

## 🔧 Spesifikasi Teknis

- PHP 7.4+
- MySQL 5.7+
- Bootstrap 5
- Chart.js untuk grafik

## 👨‍💻 Pengembang

Sistem ini dikembangkan untuk:
- **Kecamatan**: Pondokgede
- **Metode**: AHP (Analytic Hierarchy Process)
- **Tahun**: 2026

## 📄 Lisensi

Free for educational and research purposes.

---

**Catatan**: Data 100 warga yang disertakan merupakan data sampel dari penelitian. Untuk penggunaan nyata, sesuaikan dengan data sebenarnya."# ahp_metode" 
