-- =========================================================================
-- DATABASE SCHEMA: MODUL HULS (HASIL UJI DI LUAR SPESIFIKASI)
-- Digitalisasi Lab BBPOM di Bandung
-- =========================================================================

CREATE TABLE IF NOT EXISTS `huls_documents` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nomor_huls` VARCHAR(100) NOT NULL,
    `kode_sampel` VARCHAR(100) NOT NULL,
    `nama_sampel` VARCHAR(255) NOT NULL,
    `parameter_uji` TEXT DEFAULT NULL,
    `nama_penguji` VARCHAR(150) DEFAULT NULL,
    `tanggal_pengujian` VARCHAR(50) DEFAULT NULL,
    `tanggal_dilaporkan` VARCHAR(50) DEFAULT NULL,
    `hasil_uji` VARCHAR(255) DEFAULT NULL,
    `syarat` VARCHAR(255) DEFAULT NULL,
    `status_dokumen` VARCHAR(50) NOT NULL DEFAULT 'Draft',
    `kesimpulan_pre` VARCHAR(120) DEFAULT NULL,
    `kesimpulan_tahap1` TEXT DEFAULT NULL,
    `pre_investigasi_json` LONGTEXT DEFAULT NULL,
    `tahap1_json` LONGTEXT DEFAULT NULL,
    `created_by` VARCHAR(100) DEFAULT 'Admin Lab',
    `updated_by` VARCHAR(100) DEFAULT 'Admin Lab',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_kode_sampel` (`kode_sampel`),
    INDEX `idx_nomor_huls` (`nomor_huls`),
    INDEX `idx_status` (`status_dokumen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `huls_audit_logs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `huls_id` INT UNSIGNED NOT NULL,
    `nomor_huls` VARCHAR(100) DEFAULT NULL,
    `action` VARCHAR(100) NOT NULL,
    `actor_name` VARCHAR(100) DEFAULT NULL,
    `actor_role` VARCHAR(100) DEFAULT NULL,
    `notes` TEXT DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_huls_id` (`huls_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
