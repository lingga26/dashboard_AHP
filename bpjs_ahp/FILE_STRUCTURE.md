# STRUKTUR FILE PROGRAM BPJS PBI - METODE AHP

## 📂 Root Directory (`bpjs_pbi_ahp/`)

| File | Fungsi |
|------|--------|
| `index.php` | Dashboard utama dengan statistik dan grafik |
| `warga.php` | Manajemen data warga (CRUD) |
| `penilaian.php` | Form input penilaian AHP |
| `hasil.php` | Tampil hasil perhitungan lengkap |
| `statistik.php` | Analisis dan visualisasi data |
| `kriteria.php` | Informasi kriteria dan bobot AHP |
| `warga_detail.php` | Detail lengkap per warga |
| `import_data.php` | Import 100 data warga dari penelitian |
| `database.sql` | Dump struktur database MySQL |
| `.htaccess` | Konfigurasi Apache |
| `README.md` | Dokumentasi penggunaan |

## 📂 includes/ (Backend Core)

| File | Fungsi |
|------|--------|
| `config.php` | Konfigurasi database dan AHP |
| `database.php` | Class koneksi database (PDO) |
| `AHPCalculator.php` | Class perhitungan AHP lengkap |
| `functions.php` | Helper functions dan utility |

## 📂 templates/ (UI Components)

| File | Fungsi |
|------|--------|
| `header.php` | Template header + CSS Bootstrap |
| `sidebar.php` | Navigasi sidebar |
| `footer.php` | Template footer + JS |

## 🗄️ Database (MySQL)

### Tabel: `kriteria`
```sql
IDK01 - KERT (Bobot: 37.82%)
IDK02 - BTK  (Bobot: 12.77%)
IDK03 - KJK  (Bobot: 21.87%)
IDK04 - KNT  (Bobot: 5.66%)
IDK05 - KKAK (Bobot: 21.87%)
```

### Tabel: `sub_kriteria`
- 5 level prioritas per kriteria
- Bobot: 0.419, 0.263, 0.161, 0.097, 0.060

### Tabel: `warga`
- Data 100 warga anggota

### Tabel: `penilaian`
- Nilai input dan bobot per kriteria

### Tabel: `hasil_ahp`
- Total skor, kategori, ranking

## 🚀 Cara Menjalankan

1. **Buat Database**
   ```sql
   -- Import database.sql ke phpMyAdmin/MySQL
   ```

2. **Konfigurasi**
   ```php
   // Edit includes/config.php
   DB_HOST = localhost
   DB_USER = root
   DB_PASS = (kosong)
   DB_NAME = bpjs_pbi_ahp
   ```

3. **Import Data**
   ```
   Buka: http://localhost/bpjs_pbi_ahp/import_data.php
   Klik: Import Data Sekarang
   ```

4. **Akses Aplikasi**
   ```
   Dashboard: http://localhost/bpjs_phi_ahp/index.php
   ```

## 📝 Data 100 Warga

Data lengkap dengan perhitungan AHP sudah dimasukkan:
- 35 warga: Prioritas 1 (Skor ≥ 30)
- 30 warga: Prioritas 2 (Skor 20-29)
- 15 warga: Prioritas 3 (Skor 15-19)
- 20 warga: Tidak Layak (Skor < 15)

## 🎨 Fitur Tampilan

- Dashboard statistik dengan Chart.js
- Form penilaian dengan preview hasil
- Tabel hasil dengan filter & sorting
- Detail perhitungan per warga
- Grafik distribusi kategori
- Responsive (Bootstrap 5)
- Export ready
