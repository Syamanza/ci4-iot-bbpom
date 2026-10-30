-- =========================================================================
-- DATABASE SCHEMA: MODUL RENLAK (RENCANA PELAKSANAAN / KEGIATAN LABORATORIUM)
-- Digitalisasi Lab BBPOM di Bandung (Tahun 2026 - 2027)
-- =========================================================================

CREATE TABLE IF NOT EXISTS `renlak_activities` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nomor_urut` VARCHAR(20) DEFAULT '1',
    `kategori` VARCHAR(150) NOT NULL DEFAULT 'Perencanaan',
    `nama_kegiatan` VARCHAR(255) NOT NULL,
    `rencana_kegiatan` TEXT DEFAULT NULL,
    `realisasi` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `target` DECIMAL(10,2) NOT NULL DEFAULT 1.00,
    `satuan` VARCHAR(60) NOT NULL DEFAULT 'Dokumen',
    `persentase_capaian` DECIMAL(6,2) NOT NULL DEFAULT 0.00,
    `tahun_mulai` INT NOT NULL DEFAULT 2026,
    `tahun_selesai` INT NOT NULL DEFAULT 2027,
    `status_kegiatan` VARCHAR(50) NOT NULL DEFAULT 'Belum Dimulai',
    `pic_penanggung_jawab` VARCHAR(120) DEFAULT 'Tim Digitalisasi Lab',
    `catatan_evaluasi` TEXT DEFAULT NULL,
    `jadwal_rencana_json` LONGTEXT DEFAULT NULL,
    `jadwal_realisasi_json` LONGTEXT DEFAULT NULL,
    `created_by` VARCHAR(100) DEFAULT 'Admin Lab',
    `updated_by` VARCHAR(100) DEFAULT 'Admin Lab',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_renlak_kategori` (`kategori`),
    INDEX `idx_renlak_status` (`status_kegiatan`),
    INDEX `idx_renlak_tahun` (`tahun_mulai`, `tahun_selesai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `renlak_audit_logs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `renlak_id` INT UNSIGNED NOT NULL DEFAULT 0,
    `nama_kegiatan` VARCHAR(255) DEFAULT NULL,
    `action` VARCHAR(100) NOT NULL,
    `actor_name` VARCHAR(100) DEFAULT NULL,
    `actor_role` VARCHAR(100) DEFAULT NULL,
    `notes` TEXT DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_renlak_log_id` (`renlak_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
