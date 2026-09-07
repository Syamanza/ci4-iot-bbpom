<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class ApiController extends ResourceController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        $key = $this->request->getGet('key');
        
        if ($key === 'sensorData') {
            // Get latest 20 sensor data
            $query = $db->query("SELECT * FROM iot_sensor_logs ORDER BY id DESC LIMIT 20");
            $labResults = $query->getResultArray();
            
            // Format array for frontend charting
            $data = [];
            foreach ($labResults as $row) {
                $data[] = [
                    'suhu' => (float)$row['temperature'],
                    'hum' => (float)$row['humidity'],
                    'mq' => (int)$row['air_quality'],
                    'acStatus' => (bool)$row['ac_status'],
                    'kehadiran' => (int)$row['occupancy']
                ];
            }
            return $this->respond($data);
        }
        
        if ($key === 'evaluasi') {
            $query = $db->query("SELECT * FROM spbe_evaluasi ORDER BY tahun DESC LIMIT 1");
            $row = $query->getRow();
            
            $data = [
                'sisaHari' => 365,
                'rataCapaian' => $row ? (($row->kebijakan_score + $row->tata_kelola_score + $row->layanan_score + $row->manajemen_score) / 4) : 0,
                'kategori' => [
                    'kebijakan' => $row ? (float)$row->kebijakan_score : 0,
                    'tataKelola' => $row ? (float)$row->tata_kelola_score : 0,
                    'layanan' => $row ? (float)$row->layanan_score : 0,
                    'manajemen' => $row ? (float)$row->manajemen_score : 0
                ]
            ];
            return $this->respond($data);
        }

        if ($key === 'pemeliharaan') {
            $query = $db->query("SELECT * FROM tik_pemeliharaan");
            $results = $query->getResultArray();
            $data = [];
            foreach ($results as $row) {
                $data[] = [
                    'id' => $row['id'],
                    'aktivitas' => $row['aktivitas'],
                    'tgl' => $row['tanggal_mulai'],
                    'status' => $row['status'] == 'Selesai' ? 'selesai' : 'pending'
                ];
            }
            return $this->respond($data);
        }

        if ($key === 'perencanaan') {
            $query = $db->query("SELECT * FROM tik_perencanaan");
            return $this->respond($query->getResultArray());
        }

        if ($key === 'pegawai') {
            $query = $db->query("SELECT p.*, a.srikandi, a.siasn, a.simakin, a.mentari, a.etukin, a.mentari_2, a.siika 
                                 FROM pegawai p LEFT JOIN pegawai_akses_sistem a ON p.id = a.pegawai_id");
            return $this->respond($query->getResultArray());
        }

        if ($key === 'pengembangan') {
            $query = $db->query("SELECT * FROM tik_pengembangan");
            return $this->respond($query->getResultArray());
        }
        
        if ($key === 'todayAccumulator') {
            $today = date('Y-m-d');
            $query = $db->query("
                SELECT 
                    COUNT(*) as count,
                    SUM(temperature) as sumSuhu,
                    MIN(temperature) as minSuhu,
                    MAX(temperature) as maxSuhu,
                    SUM(humidity) as sumKelembapan,
                    MIN(humidity) as minKelembapan,
                    MAX(humidity) as maxKelembapan,
                    SUM(air_quality) as sumMQ,
                    MIN(air_quality) as minMQ,
                    MAX(air_quality) as maxMQ,
                    SUM(CASE WHEN temperature > 25.0 THEN 1 ELSE 0 END) as alertCount
                FROM iot_sensor_logs 
                WHERE DATE(created_at) = ?
            ", [$today]);
            
            $row = $query->getRow();
            
            if ($row && $row->count > 0) {
                return $this->respond([
                    'count' => (int)$row->count,
                    'sumSuhu' => (float)$row->sumSuhu,
                    'minSuhu' => (float)$row->minSuhu,
                    'maxSuhu' => (float)$row->maxSuhu,
                    'sumKelembapan' => (float)$row->sumKelembapan,
                    'minKelembapan' => (float)$row->minKelembapan,
                    'maxKelembapan' => (float)$row->maxKelembapan,
                    'sumMQ' => (float)$row->sumMQ,
                    'minMQ' => (int)$row->minMQ,
                    'maxMQ' => (int)$row->maxMQ,
                    'alertCount' => (int)$row->alertCount,
                    'date' => $today
                ]);
            }
            return $this->respond(['count' => 0]);
        }

        // Jika key kosong, kembalikan semua data untuk halaman utama
        if (empty($key)) {
            $data = [];
            
            // Ambil kendala
            $kQuery = $db->query("SELECT isi_catatan FROM spbe_catatan WHERE tipe_catatan = 'Kendala'");
            $data['kendala'] = array_map(function($r) { return $r['isi_catatan']; }, $kQuery->getResultArray());
            
            // Ambil rekomendasi
            $rQuery = $db->query("SELECT isi_catatan FROM spbe_catatan WHERE tipe_catatan = 'Rekomendasi'");
            $data['rekomendasi'] = array_map(function($r) { return $r['isi_catatan']; }, $rQuery->getResultArray());
            
            // Ambil evaluasi
            $evQuery = $db->query("SELECT * FROM spbe_evaluasi ORDER BY tahun DESC LIMIT 1");
            $evRow = $evQuery->getRow();
            $data['evaluasi'] = [
                'sisaHari' => 365,
                'rataCapaian' => $evRow ? (($evRow->kebijakan_score + $evRow->tata_kelola_score + $evRow->layanan_score + $evRow->manajemen_score) / 4) : 0,
                'kategori' => [
                    'kebijakan' => $evRow ? (float)$evRow->kebijakan_score : 0,
                    'tataKelola' => $evRow ? (float)$evRow->tata_kelola_score : 0,
                    'layanan' => $evRow ? (float)$evRow->layanan_score : 0,
                    'manajemen' => $evRow ? (float)$evRow->manajemen_score : 0
                ]
            ];

            // Ambil dari spbe_json_store untuk array yang dinamis
            $keys = ['bmn', 'pegawai', 'pemeliharaan', 'perencanaan'];
            foreach ($keys as $k) {
                $jQuery = $db->query("SELECT json_data FROM spbe_json_store WHERE key_name = ?", [$k]);
                $jRow = $jQuery->getRow();
                if ($jRow) {
                    $data[$k] = json_decode($jRow->json_data, true);
                } else {
                    $data[$k] = []; // Fallback array kosong
                }
            }

            // Ambil pengembangan (Tetap dari tabel tik_pengembangan yang sudah kita sesuaikan)
            $pengQuery = $db->query("SELECT * FROM tik_pengembangan");
            $data['pengembangan'] = [];
            foreach ($pengQuery->getResultArray() as $row) {
                // Konversi format kembali sesuai kebutuhan frontend
                $data['pengembangan'][] = [
                    'nama' => $row['nama'],
                    'deskripsi' => $row['deskripsi'],
                    'progres' => (int)$row['progres'],
                    'mulai' => $row['mulai'],
                    'target' => $row['target']
                ];
            }
            
            return $this->respond($data);
        }

        // Return empty array for any other keys
        return $this->respond([]);
    }

    public function receiveSensor()
    {
        // 1. Cek Kunci Akses (API Key) dari ESP32
        $authHeader = $this->request->getHeaderLine('Authorization');
        if (strpos($authHeader, 'spbe-bbpom-secret-key-2026') === false) {
            return $this->failUnauthorized('Kunci Akses Ditolak!');
        }

        // 2. Tangkap JSON dari ESP32
        $json = $this->request->getJSON();
        if (!$json) {
            return $this->fail('Data yang dikirim kosong atau bukan JSON');
        }

        // 3. Masukkan ke MySQL (Tabel iot_sensor_logs)
        $db = \Config\Database::connect();
        
        $roomId = isset($json->id) ? $json->id : 'LAB';
        $temperature = isset($json->suhu) ? (float)$json->suhu : 0;
        $humidity = isset($json->hum) ? (float)$json->hum : 0;
        $airQuality = isset($json->mq) ? (int)$json->mq : 0;
        $occupancy = isset($json->kehadiran) ? (int)$json->kehadiran : 0;
        $acStatus = isset($json->acStatus) ? (int)$json->acStatus : 0;

        $db->table('iot_sensor_logs')->insert([
            'room_id' => $roomId,
            'temperature' => $temperature,
            'humidity' => $humidity,
            'air_quality' => $airQuality,
            'occupancy' => $occupancy,
            'ac_status' => $acStatus,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // LOGIKA BOT WHATSAPP (Mencegah spam setiap 10 detik, kita batasi peringatan 1 kali per 10 menit)
        if ($temperature > 25.0) {
            $alertKey = 'last_wa_alert';
            $qAlert = $db->query("SELECT json_data FROM spbe_json_store WHERE key_name = ?", [$alertKey]);
            $rowAlert = $qAlert->getRow();
            $lastAlertTime = $rowAlert ? (int)$rowAlert->json_data : 0;
            $now = time();

            // Jika belum pernah kirim atau sudah lewat 1 menit (60 detik)
            if ($now - $lastAlertTime > 60) {
                // Update waktu terakhir kirim
                $db->query("INSERT INTO spbe_json_store (key_name, json_data) VALUES (?, ?) ON DUPLICATE KEY UPDATE json_data = VALUES(json_data)", [$alertKey, (string)$now]);

                // Kirim pesan ke API Node.js Bot WA
                try {
                    $waPayload = json_encode([
                        'number' => '08871702569',
                        'message' => "🚨 *Peringatan SPBE BBPOM* 🚨\n\n📍 Ruang Reagen\n\n🌡️ Suhu Saat Ini: *{$temperature}°C*\n⚠️ Batas Aman: *25°C*\n💧 Kelembapan: *{$humidity}%*\n🌫️ Kualitas Udara: *{$airQuality} PPM*\n\n⚡ *Segera lakukan pengecekan!*"
                    ]);

                    $ch = curl_init('http://localhost:3000/send-message');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $waPayload);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 3); // Jangan kelamaan nunggu bot WA
                    curl_exec($ch);
                    curl_close($ch);
                } catch (\Exception $e) {
                    // Abaikan error WA bot jika bot belum nyala
                }
            }
        }

        return $this->respondCreated(['status' => 'Sukses', 'pesan' => 'Sensor berhasil disimpan ke MySQL!']);
    }

    public function updateData()
    {
        $db = \Config\Database::connect();
        $key = $this->request->getGet('key');
        $json = $this->request->getJSON(true);

        if ($key === 'kendala' || $key === 'rekomendasi') {
            $enumType = $key === 'kendala' ? 'Kendala' : 'Rekomendasi';
            $tahun = date('Y');

            $db->transStart();
            $db->table('spbe_catatan')->where('tipe_catatan', $enumType)->delete();
            
            if (is_array($json)) {
                $insertData = [];
                foreach ($json as $teks) {
                    $insertData[] = [
                        'tahun' => $tahun,
                        'tipe_catatan' => $enumType,
                        'isi_catatan' => $teks,
                        'status' => 'Open'
                    ];
                }
                if (!empty($insertData)) {
                    $db->table('spbe_catatan')->insertBatch($insertData);
                }
            }
            $db->transComplete();
            
            return $this->respond(['status' => 'success', 'message' => 'Data berhasil disimpan']);
        } elseif ($key === 'evaluasi') {
            $tahun = date('Y');
            $kebijakan = isset($json['kategori']['kebijakan']) ? (float)$json['kategori']['kebijakan'] : 0;
            $tataKelola = isset($json['kategori']['tataKelola']) ? (float)$json['kategori']['tataKelola'] : 0;
            $layanan = isset($json['kategori']['layanan']) ? (float)$json['kategori']['layanan'] : 0;
            $manajemen = isset($json['kategori']['manajemen']) ? (float)$json['kategori']['manajemen'] : 0;

            // Cek apakah sudah ada data untuk tahun ini
            $existing = $db->query("SELECT id FROM spbe_evaluasi WHERE tahun = ?", [$tahun])->getRow();

            if ($existing) {
                $db->table('spbe_evaluasi')->where('id', $existing->id)->update([
                    'kebijakan_score' => $kebijakan,
                    'tata_kelola_score' => $tataKelola,
                    'layanan_score' => $layanan,
                    'manajemen_score' => $manajemen,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                $db->table('spbe_evaluasi')->insert([
                    'tahun' => $tahun,
                    'kebijakan_score' => $kebijakan,
                    'tata_kelola_score' => $tataKelola,
                    'layanan_score' => $layanan,
                    'manajemen_score' => $manajemen,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            return $this->respond(['status' => 'success', 'message' => 'Data Evaluasi SPBE berhasil disimpan']);
        } elseif ($key === 'pengembangan') {
            $db->transStart();
            $db->table('tik_pengembangan')->truncate();
            
            if (is_array($json)) {
                $insertData = [];
                foreach ($json as $item) {
                    $insertData[] = [
                        'nama' => isset($item['nama']) ? $item['nama'] : '',
                        'deskripsi' => isset($item['deskripsi']) ? $item['deskripsi'] : '',
                        'progres' => isset($item['progres']) ? (int)$item['progres'] : 0,
                        'mulai' => !empty($item['mulai']) ? $item['mulai'] : null,
                        'target' => !empty($item['target']) ? $item['target'] : null
                    ];
                }
                if (!empty($insertData)) {
                    $db->table('tik_pengembangan')->insertBatch($insertData);
                }
            }
            $db->transComplete();
            
            return $this->respond(['status' => 'success', 'message' => 'Data pengembangan berhasil disimpan']);
        } elseif (in_array($key, ['bmn', 'pegawai', 'pemeliharaan', 'perencanaan'])) {
            $jsonStr = $this->request->getBody(); // Ambil string JSON mentah
            
            $db->query("INSERT INTO spbe_json_store (key_name, json_data) VALUES (?, ?) ON DUPLICATE KEY UPDATE json_data = VALUES(json_data)", [$key, $jsonStr]);
            
            return $this->respond(['status' => 'success', 'message' => "Data $key berhasil disimpan"]);
        }

        return $this->fail('Key tidak valid atau belum diimplementasikan');
    }
}
