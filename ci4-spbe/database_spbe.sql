-- phpMyAdmin SQL Dump / HeidiSQL SQL Script
-- SPBE BBPOM Database Setup
-- --------------------------------------------------------

-- 1. Modul Monitoring IoT
CREATE TABLE IF NOT EXISTS `iot_sensor_logs` (
  `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
  `room_id` VARCHAR(20) NOT NULL,
  `temperature` FLOAT NOT NULL,
  `humidity` FLOAT NOT NULL,
  `air_quality` INT NOT NULL,
  `occupancy` INT DEFAULT 0,
  `ac_status` BOOLEAN DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `iot_daily_recaps` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `room_id` VARCHAR(20) NOT NULL,
  `recap_date` DATE NOT NULL,
  `avg_temp` FLOAT NOT NULL,
  `max_temp` FLOAT NOT NULL,
  `min_temp` FLOAT NOT NULL,
  `avg_air_quality` INT NOT NULL,
  `alert_count` INT DEFAULT 0,
  `ac_on_minutes` INT DEFAULT 0,
  `ac_off_minutes` INT DEFAULT 0
);

-- 2. Modul Evaluasi SPBE
CREATE TABLE IF NOT EXISTS `spbe_evaluasi` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `tahun` YEAR NOT NULL,
  `kebijakan_score` FLOAT NOT NULL,
  `tata_kelola_score` FLOAT NOT NULL,
  `layanan_score` FLOAT NOT NULL,
  `manajemen_score` FLOAT NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `spbe_catatan` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `tahun` YEAR NOT NULL,
  `tipe_catatan` ENUM('Kendala', 'Rekomendasi') NOT NULL,
  `isi_catatan` TEXT NOT NULL,
  `status` ENUM('Open', 'Resolved') DEFAULT 'Open',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Modul Pemeliharaan & Perencanaan
CREATE TABLE IF NOT EXISTS `tik_pemeliharaan` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `aktivitas` VARCHAR(255) NOT NULL,
  `tanggal_mulai` DATE NOT NULL,
  `tanggal_selesai` DATE NOT NULL,
  `status` ENUM('Pending', 'Selesai') DEFAULT 'Pending'
);

CREATE TABLE IF NOT EXISTS `tik_perencanaan` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `uraian_kebutuhan` VARCHAR(255) NOT NULL,
  `volume` INT NOT NULL,
  `satuan` VARCHAR(50) NOT NULL,
  `harga_satuan` DECIMAL(15,2) NOT NULL,
  `keterangan` TEXT
);

-- 4. Modul Kepegawaian & Akses
CREATE TABLE IF NOT EXISTS `pegawai` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nip` VARCHAR(50) UNIQUE NOT NULL,
  `nama_pegawai` VARCHAR(150) NOT NULL,
  `jabatan` VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS `pegawai_akses_sistem` (
  `pegawai_id` INT PRIMARY KEY,
  `srikandi` BOOLEAN DEFAULT 0,
  `siasn` BOOLEAN DEFAULT 0,
  `simakin` BOOLEAN DEFAULT 0,
  `mentari` BOOLEAN DEFAULT 0,
  `etukin` BOOLEAN DEFAULT 0,
  `mentari_2` BOOLEAN DEFAULT 0,
  `siika` BOOLEAN DEFAULT 0,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai`(`id`) ON DELETE CASCADE
);

-- 5. Modul Pengembangan & Aset
CREATE TABLE IF NOT EXISTS `tik_pengembangan` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_aplikasi` VARCHAR(150) NOT NULL,
  `deskripsi` TEXT,
  `progres_persen` TINYINT DEFAULT 0,
  `status` ENUM('Perencanaan', 'Coding', 'Testing', 'Selesai') DEFAULT 'Perencanaan',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `tik_aset` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `kode_bmn` VARCHAR(50) UNIQUE NOT NULL,
  `nama_barang` VARCHAR(255) NOT NULL,
  `kategori` ENUM('Server', 'Networking', 'Endpoint', 'Lainnya') NOT NULL,
  `merk_tipe` VARCHAR(150),
  `tahun_perolehan` YEAR NOT NULL,
  `kondisi` ENUM('Baik', 'Rusak Ringan', 'Rusak Berat') DEFAULT 'Baik',
  `lokasi_ruangan` VARCHAR(100),
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 6. Modul Pengguna (Login CI4)
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) UNIQUE NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `role_level` ENUM('SuperAdmin', 'Teknisi', 'Pimpinan') DEFAULT 'Teknisi',
  `last_login` TIMESTAMP NULL DEFAULT NULL
);

-- 7. Modul Konfigurasi Sistem
CREATE TABLE IF NOT EXISTS `web_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(50) UNIQUE NOT NULL,
  `setting_value` TEXT NOT NULL,
  `deskripsi` VARCHAR(255)
);

-- Insert Dummy Data for Settings
INSERT IGNORE INTO `web_settings` (`setting_key`, `setting_value`, `deskripsi`) VALUES
('WA_TARGET_NUMBER', '08123456789', 'Nomor WA teknisi untuk peringatan IoT'),
('LAB_SUHU_MAX', '25', 'Batas atas suhu lab (Celcius)'),
('LAB_SUHU_MIN', '15', 'Batas bawah suhu lab (Celcius)'),
('AC_KEUANGAN_OVERRIDE', 'auto', 'Status paksa AC Keuangan (auto/on/off)');
