-- ============================================================================
-- DigitLab BDG - BBPOM di Bandung
-- Database Alat IoT (Akomodasi & Lingkungan / Monitoring IoT)
-- ----------------------------------------------------------------------------
-- Berisi tabel-tabel yang digunakan oleh fitur IoT pada ApiController.php:
--   1. log_ruang_lab        : log sensor suhu, kelembapan, kualitas udara (MQ)
--                             Alat Lab (ALAT_1_AC, ALAT_2_NON_AC, alat custom)
--                             + sensor cahaya/servo lampu (ALAT_2_CAHAYA)
--   2. log_ruang_keuangan   : log sensor Ruang Keuangan (ALAT_1) beserta
--                             status AC otomatis & jumlah kehadiran (occupancy)
--   3. iot_sensor_logs      : akumulasi harian sensor (todayAccumulator)
--   4. spbe_json_store      : penyimpanan konfigurasi IoT berbasis JSON
--                             (custom_iot_config, iot_settings,
--                              light_servo_state, last_wa_alert)
--
-- Catatan:
--   * Nama tabel `spbe_json_store` dipertahankan karena dipanggil langsung
--     oleh kode (ApiController.php). Mengganti namanya akan memutus koneksi
--     konfigurasi ruangan & alat IoT yang sudah tersimpan.
--   * Seluruh perintah memakai CREATE TABLE IF NOT EXISTS / INSERT IGNORE
--     sehingga aman di-import ulang tanpa menghapus data log yang sudah ada.
-- ============================================================================

SET NAMES utf8mb4;

-- ----------------------------------------------------------------------------
-- 1. LOG SENSOR RUANG LAB
--    device_id   : ALAT_1_AC | ALAT_2_NON_AC | ALAT_2_CAHAYA | ID alat custom
--    temperature : suhu (°C)            -> untuk ALAT_2_CAHAYA = status lampu (1/0)
--    humidity    : kelembapan (%RH)     -> untuk ALAT_2_CAHAYA = sudut servo (°)
--    air_quality : kualitas udara (PPM) -> untuk ALAT_2_CAHAYA = intensitas (lumens)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `log_ruang_lab` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `device_id`   VARCHAR(50)     NOT NULL,
  `temperature` FLOAT           NOT NULL DEFAULT 0,
  `humidity`    FLOAT           NOT NULL DEFAULT 0,
  `air_quality` INT             NOT NULL DEFAULT 0,
  `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_lab_device_time` (`device_id`, `created_at`),
  KEY `idx_lab_time` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 2. LOG SENSOR RUANG KEUANGAN (OTOMASI AC & KEHADIRAN)
--    device_id : ALAT_1 | ID alat custom yang mengirim status AC / kehadiran
--    ac_status : 1 = AC menyala, 0 = AC mati
--    occupancy : jumlah orang terdeteksi di ruangan
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `log_ruang_keuangan` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `device_id`   VARCHAR(50)     NOT NULL,
  `temperature` FLOAT           NOT NULL DEFAULT 0,
  `humidity`    FLOAT           NOT NULL DEFAULT 0,
  `occupancy`   INT             NOT NULL DEFAULT 0,
  `ac_status`   TINYINT(1)      NOT NULL DEFAULT 0,
  `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_keu_device_time` (`device_id`, `created_at`),
  KEY `idx_keu_time` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 3. LOG SENSOR UMUM (AKUMULASI HARIAN)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `iot_sensor_logs` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `room_id`     VARCHAR(50)     NOT NULL,
  `temperature` FLOAT           NOT NULL DEFAULT 0,
  `humidity`    FLOAT           NOT NULL DEFAULT 0,
  `air_quality` INT             NOT NULL DEFAULT 0,
  `occupancy`   INT             NOT NULL DEFAULT 0,
  `ac_status`   TINYINT(1)      NOT NULL DEFAULT 0,
  `created_at`  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_iot_room_time` (`room_id`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 4. PENYIMPANAN KONFIGURASI IoT (JSON KEY-VALUE STORE)
--    key_name yang digunakan:
--      custom_iot_config : daftar ruangan & alat IoT tambahan (dashboard)
--      iot_settings      : pengaturan batas suhu/kelembapan (admin panel)
--      light_servo_state : mode auto/manual saklar servo lampu ALAT_2_CAHAYA
--      last_wa_alert     : waktu terakhir notifikasi WhatsApp peringatan suhu
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `spbe_json_store` (
  `key_name`   VARCHAR(100) NOT NULL,
  `json_data`  LONGTEXT     NULL,
  `updated_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data awal konfigurasi IoT (tidak menimpa data yang sudah ada)
INSERT IGNORE INTO `spbe_json_store` (`key_name`, `json_data`) VALUES
('custom_iot_config', '{"rooms":[],"devices":[]}'),
('light_servo_state', '{"ALAT_2_CAHAYA":{"mode":"auto","lampStatus":"ON","servoAngle":90,"lumens":380,"minLumens":250,"maxLumens":500,"servoActionsToday":0}}');
