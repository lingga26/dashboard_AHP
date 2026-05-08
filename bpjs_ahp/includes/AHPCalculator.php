<?php
/**
 * =========================================================
 * AHP CALCULATOR CLASS
 * Metode AHP untuk Penentuan Prioritas BPJS PBI
 * =========================================================
 */

require_once 'database.php';

class AHPCalculator {
    
    private $kriteria = [];
    private $bobotGlobal = [];
    
    public function __construct() {
        // Load kriteria dan bobot global
        $this->bobotGlobal = $GLOBALS['AHP_BOBOT_GLOBAL'];
        
        $data = db()->fetchAll("SELECT * FROM kriteria ORDER BY id_kriteria");
        foreach ($data as $row) {
            $this->kriteria[$row['id_kriteria']] = $row;
        }
    }
    
    /**
     * Konversi nilai KERT (Pendapatan) ke bobot
     */
    public function konversiKERT($pendapatan) {
        if ($pendapatan >= 0 && $pendapatan <= 500000) {
            return ['bobot' => 0.419, 'label' => 'Sangat Prioritas'];
        } elseif ($pendapatan > 500000 && $pendapatan <= 1000000) {
            return ['bobot' => 0.263, 'label' => 'Prioritas'];
        } elseif ($pendapatan > 1000000 && $pendapatan <= 2000000) {
            return ['bobot' => 0.161, 'label' => 'Cukup Prioritas'];
        } elseif ($pendapatan > 2000000 && $pendapatan <= 3000000) {
            return ['bobot' => 0.097, 'label' => 'Kurang Prioritas'];
        } else {
            return ['bobot' => 0.060, 'label' => 'Tidak Prioritas'];
        }
    }
    
    /**
     * Konversi nilai BTK (Jumlah Anggota) ke bobot
     */
    public function konversiBTK($jmlAnggota) {
        if ($jmlAnggota >= 5) {
            return ['bobot' => 0.419, 'label' => 'Sangat Prioritas'];
        } elseif ($jmlAnggota == 4) {
            return ['bobot' => 0.263, 'label' => 'Prioritas'];
        } elseif ($jmlAnggota == 3) {
            return ['bobot' => 0.161, 'label' => 'Cukup Prioritas'];
        } elseif ($jmlAnggota == 2) {
            return ['bobot' => 0.097, 'label' => 'Kurang Prioritas'];
        } else {
            return ['bobot' => 0.060, 'label' => 'Tidak Prioritas'];
        }
    }
    
    /**
     * Konversi nilai KJK (Status BPJS) ke bobot
     * 0=Belum, 1=Nonaktif>6bln, 2=Nonaktif3-6bln, 3=Nonaktif<3bln, 4=Aktif
     */
    public function konversiKJK($statusBPJS) {
        switch ($statusBPJS) {
            case 0:
                return ['bobot' => 0.419, 'label' => 'Sangat Prioritas'];
            case 1:
                return ['bobot' => 0.263, 'label' => 'Prioritas'];
            case 2:
                return ['bobot' => 0.161, 'label' => 'Cukup Prioritas'];
            case 3:
                return ['bobot' => 0.097, 'label' => 'Kurang Prioritas'];
            default:
                return ['bobot' => 0.060, 'label' => 'Tidak Prioritas'];
        }
    }
    
    /**
     * Konversi nilai KNT (Tahun Domisili) ke bobot
     */
    public function konversiKNT($tahunDomisili) {
        if ($tahunDomisili >= 5) {
            return ['bobot' => 0.419, 'label' => 'Sangat Prioritas'];
        } elseif ($tahunDomisili >= 3) {
            return ['bobot' => 0.263, 'label' => 'Prioritas'];
        } elseif ($tahunDomisili >= 1) {
            return ['bobot' => 0.161, 'label' => 'Cukup Prioritas'];
        } elseif ($tahunDomisili > 0) {
            return ['bobot' => 0.097, 'label' => 'Kurang Prioritas'];
        } else {
            return ['bobot' => 0.060, 'label' => 'Tidak Prioritas'];
        }
    }
    
    /**
     * Konversi nilai KKAK (Skor Kesehatan) ke bobot
     */
    public function konversiKKAK($skorKesehatan) {
        if ($skorKesehatan >= 85) {
            return ['bobot' => 0.419, 'label' => 'Sangat Prioritas'];
        } elseif ($skorKesehatan >= 70) {
            return ['bobot' => 0.263, 'label' => 'Prioritas'];
        } elseif ($skorKesehatan >= 50) {
            return ['bobot' => 0.161, 'label' => 'Cukup Prioritas'];
        } elseif ($skorKesehatan >= 30) {
            return ['bobot' => 0.097, 'label' => 'Kurang Prioritas'];
        } else {
            return ['bobot' => 0.060, 'label' => 'Tidak Prioritas'];
        }
    }
    
    /**
     * Hitung nilai AHP untuk semua kriteria
     */
    public function hitungAHP($dataInput) {
        $hasil = [];
        $totalSkor = 0;
        
        // IDK01 - KERT
        $kert = $this->konversiKERT($dataInput['kert']);
        $nilaiKERT = $kert['bobot'] * $this->bobotGlobal['IDK01'] * 100;
        $hasil['IDK01'] = [
            'nilai_input' => $dataInput['kert'],
            'bobot_sub' => $kert['bobot'],
            'nilai_ahp' => round($nilaiKERT, 2),
            'label' => $kert['label']
        ];
        $totalSkor += $nilaiKERT;
        
        // IDK02 - BTK
        $btk = $this->konversiBTK($dataInput['btk']);
        $nilaiBTK = $btk['bobot'] * $this->bobotGlobal['IDK02'] * 100;
        $hasil['IDK02'] = [
            'nilai_input' => $dataInput['btk'],
            'bobot_sub' => $btk['bobot'],
            'nilai_ahp' => round($nilaiBTK, 2),
            'label' => $btk['label']
        ];
        $totalSkor += $nilaiBTK;
        
        // IDK03 - KJK
        $kjk = $this->konversiKJK($dataInput['kjk']);
        $nilaiKJK = $kjk['bobot'] * $this->bobotGlobal['IDK03'] * 100;
        $hasil['IDK03'] = [
            'nilai_input' => $dataInput['kjk'],
            'bobot_sub' => $kjk['bobot'],
            'nilai_ahp' => round($nilaiKJK, 2),
            'label' => $kjk['label']
        ];
        $totalSkor += $nilaiKJK;
        
        // IDK04 - KNT
        $knt = $this->konversiKNT($dataInput['knt']);
        $nilaiKNT = $knt['bobot'] * $this->bobotGlobal['IDK04'] * 100;
        $hasil['IDK04'] = [
            'nilai_input' => $dataInput['knt'],
            'bobot_sub' => $knt['bobot'],
            'nilai_ahp' => round($nilaiKNT, 2),
            'label' => $knt['label']
        ];
        $totalSkor += $nilaiKNT;
        
        // IDK05 - KKAK
        $kkak = $this->konversiKKAK($dataInput['kkak']);
        $nilaiKKAK = $kkak['bobot'] * $this->bobotGlobal['IDK05'] * 100;
        $hasil['IDK05'] = [
            'nilai_input' => $dataInput['kkak'],
            'bobot_sub' => $kkak['bobot'],
            'nilai_ahp' => round($nilaiKKAK, 2),
            'label' => $kkak['label']
        ];
        $totalSkor += $nilaiKKAK;
        
        $hasil['total_skor'] = round($totalSkor, 2);
        $hasil['kategori'] = $this->tentukanKategori($totalSkor);
        
        return $hasil;
    }
    
    /**
     * Tentukan kategori berdasarkan total skor
     */
    public function tentukanKategori($totalSkor) {
        $kategoriDef = $GLOBALS['AHP_KATEGORI'];
        
        foreach ($kategoriDef as $kat) {
            if ($totalSkor >= $kat['min']) {
                return $kat;
            }
        }
        
        return $kategoriDef[count($kategoriDef) - 1];
    }
    
    /**
     * Simpan perhitungan ke database
     */
    public function simpanPenilaian($idWarga, $dataInput) {
        try {
            db()->beginTransaction();
            
            $hasil = $this->hitungAHP($dataInput);
            
            // Hapus penilaian lama
            db()->execute("DELETE FROM penilaian WHERE id_warga = ?", [$idWarga]);
            
            // Insert penilaian baru
            foreach (['IDK01', 'IDK02', 'IDK03', 'IDK04', 'IDK05'] as $idKriteria) {
                db()->execute(
                    "INSERT INTO penilaian (id_warga, id_kriteria, nilai_input, bobot_sub, nilai_ahp) 
                     VALUES (?, ?, ?, ?, ?)",
                    [
                        $idWarga,
                        $idKriteria,
                        $hasil[$idKriteria]['nilai_input'],
                        $hasil[$idKriteria]['bobot_sub'],
                        $hasil[$idKriteria]['nilai_ahp']
                    ]
                );
            }
            
            // Simpan hasil AHP
            db()->execute("DELETE FROM hasil_ahp WHERE id_warga = ?", [$idWarga]);
            db()->execute(
                "INSERT INTO hasil_ahp (id_warga, total_skor, kategori) VALUES (?, ?, ?)",
                [$idWarga, $hasil['total_skor'], $hasil['kategori']['kategori']]
            );
            
            db()->commit();
            
            return [
                'success' => true,
                'id_warga' => $idWarga,
                'hasil' => $hasil
            ];
            
        } catch (Exception $e) {
            db()->rollback();
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Update ranking semua warga
     */
    public function updateRanking() {
        // Update ranking berdasarkan total_skor (descending)
        $sql = "
            SET @rank := 0;
            UPDATE hasil_ahp ha
            JOIN (
                SELECT id_warga, total_skor,
                       @rank := @rank + 1 as rank_number
                FROM hasil_ahp
                ORDER BY total_skor DESC
            ) ranked ON ha.id_warga = ranked.id_warga
            SET ha.ranking = ranked.rank_number;
        ";
        
        db()->execute("SET @rank := 0");
        db()->query("UPDATE hasil_ahp JOIN (SELECT id_warga, total_skor, @rank := @rank + 1 as rank_number FROM hasil_ahp ORDER BY total_skor DESC) ranked ON hasil_ahp.id_warga = ranked.id_warga SET hasil_ahp.ranking = ranked.rank_number");
        
        return true;
    }
    
    /**
     * Get hasil perhitungan dengan details
     */
    public function getHasilLengkap($idWarga = null) {
        $sql = "
            SELECT 
                w.id_warga,
                w.nama,
                w.nik,
                w.alamat,
                h.total_skor,
                h.kategori,
                h.ranking,
                p.id_kriteria,
                k.nama_kriteria,
                p.nilai_input,
                p.bobot_sub,
                p.nilai_ahp,
                sk.nama_sub
            FROM warga w
            JOIN hasil_ahp h ON w.id_warga = h.id_warga
            JOIN penilaian p ON w.id_warga = p.id_warga
            JOIN kriteria k ON p.id_kriteria = k.id_kriteria
            LEFT JOIN sub_kriteria sk ON k.id_kriteria = sk.id_kriteria 
                AND sk.bobot = p.bobot_sub
        ";
        
        $params = [];
        if ($idWarga) {
            $sql .= " WHERE w.id_warga = ?";
            $params[] = $idWarga;
        }
        
        $sql .= " ORDER BY h.ranking ASC, k.id_kriteria ASC";
        
        return db()->fetchAll($sql, $params);
    }
    
    /**
     * Get statistik per kategori
     */
    public function getStatistik() {
        $stats = [
            'total' => 0,
            'kategori' => [],
            'rata_rata' => 0
        ];
        
        $data = db()->fetchAll("
            SELECT kategori, COUNT(*) as jumlah, AVG(total_skor) as rata_skor
            FROM hasil_ahp
            GROUP BY kategori
            ORDER BY FIELD(kategori, 'Prioritas 1', 'Prioritas 2', 'Prioritas 3', 'Tidak Layak')
        ");
        
        foreach ($data as $row) {
            $stats['kategori'][$row['kategori']] = [
                'jumlah' => (int)$row['jumlah'],
                'rata_skor' => round($row['rata_skor'], 2)
            ];
            $stats['total'] += $row['jumlah'];
        }
        
        if ($stats['total'] > 0) {
            $avg = db()->fetchOne("SELECT AVG(total_skor) as avg FROM hasil_ahp");
            $stats['rata_rata'] = round($avg['avg'], 2);
        }
        
        return $stats;
    }
}
?>