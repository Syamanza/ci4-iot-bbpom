<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class ApiController extends ResourceController
{
    public function index()
    {
        return $this->getData();
    }

    public function getData()
    {
        $key = $this->request->getGet('key');
        $db = \Config\Database::connect();

        if ($key === 'sensorData') {
            $data = [];
            
            // 1. LAB AC
            $queryLab1 = $db->query("SELECT * FROM log_ruang_lab WHERE device_id = 'ALAT_1_AC' ORDER BY created_at DESC LIMIT 20");
            $resLab1 = array_reverse($queryLab1->getResultArray());
            $dataLab1 = [];
            foreach ($resLab1 as $row) {
                $dataLab1[] = ['suhu' => (float)$row['temperature'], 'hum' => (float)$row['humidity'], 'mq' => (int)$row['air_quality']];
            }
            $data['lab_ac'] = $dataLab1;
            
            // 2. LAB NON-AC
            $queryLab2 = $db->query("SELECT * FROM log_ruang_lab WHERE device_id = 'ALAT_2_NON_AC' ORDER BY created_at DESC LIMIT 20");
            $resLab2 = array_reverse($queryLab2->getResultArray());
            $dataLab2 = [];
            foreach ($resLab2 as $row) {
                $dataLab2[] = ['suhu' => (float)$row['temperature'], 'hum' => (float)$row['humidity'], 'mq' => (int)$row['air_quality']];
            }
            $data['lab_non_ac'] = $dataLab2;
            
            // 3. KEUANGAN
            $queryKeu = $db->query("SELECT * FROM log_ruang_keuangan WHERE device_id = 'ALAT_1' ORDER BY created_at DESC LIMIT 20");
            $resKeu = array_reverse($queryKeu->getResultArray());
            $dataKeu = [];
            foreach ($resKeu as $row) {
                $dataKeu[] = [
                    'suhu' => (float)$row['temperature'], 
                    'hum' => (float)$row['humidity'],
                    'acStatus' => (bool)$row['ac_status'],
                    'kehadiran' => (int)$row['occupancy']
                ];
            }
            $data['keuangan'] = $dataKeu;
            
            return $this->respond($data);
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

        if ($key === 'recapByDate') {
            $date = $this->request->getGet('date') ?? date('Y-m-d');
            
            // Calculate for LAB
            $queryLab = $db->query("
                SELECT 
                    COUNT(*) as count,
                    SUM(temperature) as sumSuhu,
                    MIN(temperature) as minSuhu,
                    MAX(temperature) as maxSuhu,
                    SUM(humidity) as sumHum,
                    MIN(humidity) as minHum,
                    MAX(humidity) as maxHum,
                    SUM(air_quality) as sumMQ,
                    MIN(air_quality) as minMQ,
                    MAX(air_quality) as maxMQ,
                    SUM(CASE WHEN temperature > 25.0 THEN 1 ELSE 0 END) as alertCount
                FROM log_ruang_lab 
                WHERE DATE(created_at) = ? AND device_id = 'ALAT_1_AC'
            ", [$date]);
            $rowLab = $queryLab->getRow();
            
            // Calculate for LAB_NON_AC
            $queryLab2 = $db->query("
                SELECT 
                    COUNT(*) as count,
                    SUM(temperature) as sumSuhu,
                    MIN(temperature) as minSuhu,
                    MAX(temperature) as maxSuhu,
                    SUM(humidity) as sumHum,
                    MIN(humidity) as minHum,
                    MAX(humidity) as maxHum,
                    SUM(air_quality) as sumMQ,
                    MIN(air_quality) as minMQ,
                    MAX(air_quality) as maxMQ,
                    SUM(CASE WHEN temperature > 25.0 THEN 1 ELSE 0 END) as alertCount
                FROM log_ruang_lab 
                WHERE DATE(created_at) = ? AND device_id = 'ALAT_2_NON_AC'
            ", [$date]);
            $rowLab2 = $queryLab2->getRow();
            
            $lab2 = [
                'count' => $rowLab2 ? (int)$rowLab2->count : 0,
                'avgSuhu' => ($rowLab2 && $rowLab2->count > 0) ? round($rowLab2->sumSuhu / $rowLab2->count, 1) : 0,
                'minSuhu' => $rowLab2 ? round((float)$rowLab2->minSuhu, 1) : 0,
                'maxSuhu' => $rowLab2 ? round((float)$rowLab2->maxSuhu, 1) : 0,
                'avgHum' => ($rowLab2 && $rowLab2->count > 0) ? round($rowLab2->sumHum / $rowLab2->count, 1) : 0,
                'minHum' => $rowLab2 ? round((float)$rowLab2->minHum, 1) : 0,
                'maxHum' => $rowLab2 ? round((float)$rowLab2->maxHum, 1) : 0,
                'avgMQ' => ($rowLab2 && $rowLab2->count > 0) ? round($rowLab2->sumMQ / $rowLab2->count, 0) : 0,
                'minMQ' => $rowLab2 ? round((float)$rowLab2->minMQ, 0) : 0,
                'maxMQ' => $rowLab2 ? round((float)$rowLab2->maxMQ, 0) : 0,
                'alerts' => $rowLab2 ? (int)$rowLab2->alertCount : 0
            ];

            // Calculate for KEUANGAN
            $queryKeu = $db->query("
                SELECT 
                    COUNT(*) as count,
                    SUM(CASE WHEN ac_status = 1 THEN 1 ELSE 0 END) as acOnCount,
                    SUM(CASE WHEN ac_status = 0 THEN 1 ELSE 0 END) as acOffCount,
                    AVG(temperature) as avgTemp,
                    AVG(occupancy) as avgOcc
                FROM log_ruang_keuangan 
                WHERE DATE(created_at) = ? AND device_id = 'ALAT_1'
            ", [$date]);
            $rowKeu = $queryKeu->getRow();
            
            $lab = [
                'count' => $rowLab ? (int)$rowLab->count : 0,
                'avgSuhu' => ($rowLab && $rowLab->count > 0) ? round($rowLab->sumSuhu / $rowLab->count, 1) : 0,
                'minSuhu' => $rowLab ? round((float)$rowLab->minSuhu, 1) : 0,
                'maxSuhu' => $rowLab ? round((float)$rowLab->maxSuhu, 1) : 0,
                'avgHum' => ($rowLab && $rowLab->count > 0) ? round($rowLab->sumHum / $rowLab->count, 1) : 0,
                'minHum' => $rowLab ? round((float)$rowLab->minHum, 1) : 0,
                'maxHum' => $rowLab ? round((float)$rowLab->maxHum, 1) : 0,
                'avgMQ' => ($rowLab && $rowLab->count > 0) ? round($rowLab->sumMQ / $rowLab->count, 0) : 0,
                'minMQ' => $rowLab ? round((float)$rowLab->minMQ, 0) : 0,
                'maxMQ' => $rowLab ? round((float)$rowLab->maxMQ, 0) : 0,
                'alerts' => $rowLab ? (int)$rowLab->alertCount : 0
            ];
            
            // Assume 1 log = 5 seconds, so acOnCount * 5 / 60 = minutes
            $acOnMinutes = $rowKeu ? round(((int)$rowKeu->acOnCount * 5) / 60) : 0;
            $acOffMinutes = $rowKeu ? round(((int)$rowKeu->acOffCount * 5) / 60) : 0;
            
            $keu = [
                'count' => $rowKeu ? (int)$rowKeu->count : 0,
                'acOnMinutes' => $acOnMinutes,
                'acOffMinutes' => $acOffMinutes,
                'avgTemp' => $rowKeu ? round((float)$rowKeu->avgTemp, 1) : 0,
                'avgOcc' => $rowKeu ? round((float)$rowKeu->avgOcc, 1) : 0
            ];
            
            return $this->respond([
                'date' => $date,
                'lab' => $lab,
                'lab2' => $lab2,
                'keuangan' => $keu
            ]);
        }

        // Return IoT settings for the admin panel
        $query = $db->query("SELECT json_data FROM spbe_json_store WHERE key_name = 'iot_settings'");
        $row = $query->getRow();
        $settings = $row ? json_decode($row->json_data, true) : [];
        return $this->respond(['iot_settings' => $settings]);
    }

    public function receiveSensor()
    {
        $authHeader = $this->request->getHeaderLine('Authorization');
        if (strpos($authHeader, 'spbe-bbpom-secret-key-2026') === false) {
            return $this->failUnauthorized('Kunci Akses Ditolak!');
        }

        $json = $this->request->getJSON();
        if (!$json) {
            return $this->fail('Data yang dikirim kosong atau bukan JSON');
        }

        $db = \Config\Database::connect();
        
        $roomId = isset($json->id) ? $json->id : 'LAB';
        $temperature = isset($json->suhu) ? (float)$json->suhu : 0;
        $humidity = isset($json->hum) ? (float)$json->hum : 0;
        $airQuality = isset($json->mq) ? (int)$json->mq : 0;

        // Route ke tabel yang benar berdasarkan ID alat
        if ($roomId === 'KEUANGAN') {
            $acStatus = isset($json->ac) ? (int)$json->ac : 0;
            $occupancy = isset($json->occ) ? (int)$json->occ : 0;
            $sql = "INSERT INTO log_ruang_keuangan (device_id, temperature, humidity, occupancy, ac_status, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $db->query($sql, ['ALAT_1', $temperature, $humidity, $occupancy, $acStatus, date('Y-m-d H:i:s')]);
        } else {
            // Default: masuk ke tabel Lab
            // Mapping: LAB / LAB_AC -> ALAT_1_AC, LAB_NON_AC -> ALAT_2_NON_AC
            $deviceId = 'ALAT_1_AC';
            if ($roomId === 'LAB_NON_AC') {
                $deviceId = 'ALAT_2_NON_AC';
            }
            $sql = "INSERT INTO log_ruang_lab (device_id, temperature, humidity, air_quality, created_at) 
                    VALUES (?, ?, ?, ?, ?)";
            $db->query($sql, [$deviceId, $temperature, $humidity, $airQuality, date('Y-m-d H:i:s')]);
        }

        // ALARM LOGIC WA BOT
        if ($temperature > 25.0) {
            $lastSentQuery = $db->query("SELECT json_data FROM spbe_json_store WHERE key_name = 'last_wa_alert'");
            $lastSentRow = $lastSentQuery->getRow();
            
            $lastSentTime = $lastSentRow ? strtotime($lastSentRow->json_data) : 0;
            $currentTime = time();
            
            // Throttle 1 menit
            if (($currentTime - $lastSentTime) > 60) {
                // Send cURL to Node WA Bot
                $ch = curl_init('http://localhost:3000/send-message');
                $payload = json_encode([
                    'number' => '082123456789', // Gantilah secara dinamis jika mau
                    'message' => "🚨 *Peringatan SPBE BBPOM* 🚨\n\n📍 Ruang Reagen\nSuhu Saat ini: {$temperature}°C\nBatas Aman: 25.0°C\nKelembapan: {$humidity}%\nKualitas Udara: {$airQuality} PPM\n\nSegera lakukan pengecekan!"
                ]);
                
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Timeout cepat agar tidak hang
                
                $response = curl_exec($ch);
                curl_close($ch);

                // Update timestamp
                if ($lastSentRow) {
                    $db->query("UPDATE spbe_json_store SET json_data = ? WHERE key_name = 'last_wa_alert'", [date('Y-m-d H:i:s')]);
                } else {
                    $db->query("INSERT INTO spbe_json_store (key_name, json_data) VALUES ('last_wa_alert', ?)", [date('Y-m-d H:i:s')]);
                }
            }
        }

        return $this->respondCreated(['status' => 'success', 'message' => 'Data sensor IoT disimpan']);
    }

    public function updateData()
    {
        $key = $this->request->getGet('key');
        $json = $this->request->getJSON();
        
        if (!$json) {
            return $this->fail('Invalid JSON Data');
        }

        $db = \Config\Database::connect();
        
        // IoT Settings via Admin
        if ($key === 'iot_settings') {
            $jsonString = json_encode($json);
            $query = $db->query("SELECT key_name FROM spbe_json_store WHERE key_name = 'iot_settings'");
            if ($query->getRow()) {
                $db->query("UPDATE spbe_json_store SET json_data = ? WHERE key_name = 'iot_settings'", [$jsonString]);
            } else {
                $db->query("INSERT INTO spbe_json_store (key_name, json_data) VALUES ('iot_settings', ?)", [$jsonString]);
            }
            return $this->respond(['status' => 'success', 'message' => 'Pengaturan IoT disimpan']);
        }

        return $this->fail('Invalid key');
    }
}
