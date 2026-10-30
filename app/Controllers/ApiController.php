<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class ApiController extends ResourceController
{
    public function index()
    {
        return $this->getData();
    }

    private function calcLabDeviceRecap($db, $deviceId, $date, $maxTempLimit = 25.0)
    {
        $query = $db->query("
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
                SUM(CASE WHEN temperature > ? THEN 1 ELSE 0 END) as alertCount
            FROM log_ruang_lab 
            WHERE DATE(created_at) = ? AND device_id = ?
        ", [$maxTempLimit, $date, $deviceId]);
        $row = $query->getRow();

        $qKeu = $db->query("
            SELECT 
                COUNT(*) as count,
                SUM(CASE WHEN ac_status = 1 THEN 1 ELSE 0 END) as acOnCount,
                SUM(CASE WHEN ac_status = 0 THEN 1 ELSE 0 END) as acOffCount,
                AVG(occupancy) as avgOcc
            FROM log_ruang_keuangan 
            WHERE DATE(created_at) = ? AND device_id = ?
        ", [$date, $deviceId]);
        $rKeu = $qKeu->getRow();

        return [
            'count' => $row ? (int)$row->count : 0,
            'avgSuhu' => ($row && $row->count > 0) ? round($row->sumSuhu / $row->count, 1) : 0,
            'minSuhu' => ($row && $row->count > 0) ? round((float)$row->minSuhu, 1) : 0,
            'maxSuhu' => ($row && $row->count > 0) ? round((float)$row->maxSuhu, 1) : 0,
            'avgHum' => ($row && $row->count > 0) ? round($row->sumHum / $row->count, 1) : 0,
            'minHum' => ($row && $row->count > 0) ? round((float)$row->minHum, 1) : 0,
            'maxHum' => ($row && $row->count > 0) ? round((float)$row->maxHum, 1) : 0,
            'avgMQ' => ($row && $row->count > 0) ? round($row->sumMQ / $row->count, 0) : 0,
            'minMQ' => ($row && $row->count > 0) ? round((float)$row->minMQ, 0) : 0,
            'maxMQ' => ($row && $row->count > 0) ? round((float)$row->maxMQ, 0) : 0,
            'alerts' => $row ? (int)$row->alertCount : 0,
            'acOnMinutes' => $rKeu ? round(((int)$rKeu->acOnCount * 5) / 60) : 0,
            'acOffMinutes' => $rKeu ? round(((int)$rKeu->acOffCount * 5) / 60) : 0,
            'avgOcc' => ($rKeu && $rKeu->count > 0) ? round((float)$rKeu->avgOcc, 1) : 0
        ];
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
            
            // 3. KEUANGAN (ALAT 1 - OTOMASI AC)
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

            // 3B. KEUANGAN ALAT 2 (SENSOR INTENSITAS CAHAYA LUMENS & SERVO SAKLAR LAMPU)
            $dataCahaya = [];
            try {
                $queryCahaya = $db->query("SELECT * FROM log_ruang_lab WHERE device_id = 'ALAT_2_CAHAYA' ORDER BY created_at DESC LIMIT 20");
                $resCahaya = array_reverse($queryCahaya->getResultArray());
                foreach ($resCahaya as $row) {
                    $dataCahaya[] = [
                        'lumens' => (int)$row['air_quality'],
                        'lampu' => ((float)$row['temperature']) > 0 ? 'ON' : 'OFF',
                        'servo' => (int)$row['humidity'],
                        'waktu' => $row['created_at']
                    ];
                }
            } catch (\Throwable $e) {}
            $data['keuangan_cahaya'] = $dataCahaya;

            // State Kontrol Manual / Otomatis Servo Lampu
            $lightServoState = [
                'ALAT_2_CAHAYA' => [
                    'mode' => 'auto',
                    'lampStatus' => 'ON',
                    'servoAngle' => 90,
                    'lumens' => 380,
                    'minLumens' => 250,
                    'maxLumens' => 500,
                    'servoActionsToday' => 4
                ]
            ];
            try {
                $lsQuery = $db->query("SELECT json_data FROM spbe_json_store WHERE key_name = 'light_servo_state'");
                $lsRow = $lsQuery->getRow();
                if ($lsRow && $lsRow->json_data) {
                    $decodedLs = json_decode($lsRow->json_data, true);
                    if (is_array($decodedLs)) {
                        $lightServoState = array_merge($lightServoState, $decodedLs);
                    }
                }
            } catch (\Throwable $e) {}
            $data['light_servo_state'] = $lightServoState;

            // 4. CUSTOM ROOMS & DEVICES (Dynamic from spbe_json_store)
            $customDevicesData = [];
            try {
                $cfgQuery = $db->query("SELECT json_data FROM spbe_json_store WHERE key_name = 'custom_iot_config'");
                $cfgRow = $cfgQuery->getRow();
                if ($cfgRow && $cfgRow->json_data) {
                    $cfg = json_decode($cfgRow->json_data, true);
                    if (!empty($cfg['devices']) && is_array($cfg['devices'])) {
                        foreach ($cfg['devices'] as $dev) {
                            $devId = $dev['deviceId'] ?? '';
                            if (!$devId) continue;

                            $qLab = $db->query("SELECT * FROM log_ruang_lab WHERE device_id = ? ORDER BY created_at DESC LIMIT 20", [$devId]);
                            $rLab = array_reverse($qLab->getResultArray());
                            $dLogs = [];
                            foreach ($rLab as $r) {
                                $dLogs[] = [
                                    'suhu' => (float)$r['temperature'],
                                    'hum' => (float)$r['humidity'],
                                    'mq' => (int)$r['air_quality'],
                                    'waktu' => $r['created_at']
                                ];
                            }

                            $qKeu = $db->query("SELECT * FROM log_ruang_keuangan WHERE device_id = ? ORDER BY created_at DESC LIMIT 1", [$devId]);
                            $rKeu = $qKeu->getRowArray();

                            $customDevicesData[$devId] = [
                                'logs' => $dLogs,
                                'acStatus' => $rKeu ? (bool)$rKeu['ac_status'] : false,
                                'kehadiran' => $rKeu ? (int)$rKeu['occupancy'] : 0
                            ];
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Ignore if table doesn't exist yet
            }
            $data['custom_devices'] = $customDevicesData;
            
            return $this->respond($data);
        }

        if ($key === 'customConfig') {
            $defaultConfig = ['rooms' => [], 'devices' => []];
            try {
                $query = $db->query("SELECT json_data FROM spbe_json_store WHERE key_name = 'custom_iot_config'");
                $row = $query->getRow();
                if ($row && $row->json_data) {
                    $decoded = json_decode($row->json_data, true);
                    if (is_array($decoded)) {
                        $defaultConfig = $decoded;
                    }
                }
            } catch (\Throwable $e) {
                // Fallback to empty config
            }
            return $this->respond(['status' => 'success', 'config' => $defaultConfig]);
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
            $targetDeviceId = $this->request->getGet('device_id');

            // If requesting recap for a specific custom device
            if ($targetDeviceId) {
                $maxLimit = (float)($this->request->getGet('max_suhu') ?? 25.0);
                $devRecap = $this->calcLabDeviceRecap($db, $targetDeviceId, $date, $maxLimit);
                return $this->respond([
                    'status' => 'success',
                    'date' => $date,
                    'device_id' => $targetDeviceId,
                    'recap' => $devRecap,
                    'device_recap' => $devRecap
                ]);
            }
            
            $lab = $this->calcLabDeviceRecap($db, 'ALAT_1_AC', $date, 25.0);
            $lab2 = $this->calcLabDeviceRecap($db, 'ALAT_2_NON_AC', $date, 25.0);

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

        if ($key === 'monthlyTempHumReport') {
            $deviceId = $this->request->getGet('device_id') ?: 'ALAT_1_AC';
            $year = (int)($this->request->getGet('year') ?: date('Y'));
            $month = (int)($this->request->getGet('month') ?: date('n'));

            $daysData = [];
            for ($d = 1; $d <= 31; $d++) {
                $daysData[$d] = [
                    'day' => $d,
                    'suhu_08' => null,
                    'hum_08'  => null,
                    'suhu_15' => null,
                    'hum_15'  => null
                ];
            }

            try {
                $q = $db->query("
                    SELECT temperature, humidity, created_at
                    FROM log_ruang_lab
                    WHERE device_id = ? AND YEAR(created_at) = ? AND MONTH(created_at) = ?
                    ORDER BY created_at ASC
                ", [$deviceId, $year, $month]);
                $rows = $q->getResultArray();

                $bestDiff08 = [];
                $bestDiff15 = [];

                foreach ($rows as $r) {
                    $ts = strtotime($r['created_at']);
                    if (!$ts) continue;
                    $day = (int)date('j', $ts);
                    $secOfDay = ((int)date('G', $ts) * 3600) + ((int)date('i', $ts) * 60) + (int)date('s', $ts);

                    $target08 = 8 * 3600;  // 08:00:00
                    $target15 = 15 * 3600; // 15:00:00

                    // Slot 08.00 (prioritaskan 07:45 - 08:15, fallback pagi 06:00 - 11:59)
                    if ($secOfDay >= 6 * 3600 && $secOfDay < 12 * 3600) {
                        $diff = abs($secOfDay - $target08);
                        if (!isset($bestDiff08[$day]) || $diff < $bestDiff08[$day]) {
                            $bestDiff08[$day] = $diff;
                            $daysData[$day]['suhu_08'] = round((float)$r['temperature'], 1);
                            $daysData[$day]['hum_08']  = (int)round((float)$r['humidity']);
                        }
                    }

                    // Slot 15.00 (prioritaskan 14:45 - 15:15, fallback siang/sore 12:00 - 18:30)
                    if ($secOfDay >= 12 * 3600 && $secOfDay <= 18.5 * 3600) {
                        $diff = abs($secOfDay - $target15);
                        if (!isset($bestDiff15[$day]) || $diff < $bestDiff15[$day]) {
                            $bestDiff15[$day] = $diff;
                            $daysData[$day]['suhu_15'] = round((float)$r['temperature'], 1);
                            $daysData[$day]['hum_15']  = (int)round((float)$r['humidity']);
                        }
                    }
                }
            } catch (\Throwable $e) {}

            return $this->respond([
                'status' => 'success',
                'device_id' => $deviceId,
                'year' => $year,
                'month' => $month,
                'days' => array_values($daysData)
            ]);
        }

        if ($key === 'historyData') {
            $range = $this->request->getGet('range'); // 1h, 6h, 24h
            $from = $this->request->getGet('from');   // Y-m-d
            $to = $this->request->getGet('to');       // Y-m-d
            $deviceIdsParam = $this->request->getGet('device_ids'); // comma-separated custom device IDs

            // Build WHERE clause based on parameters
            $whereTime = "DATE(created_at) = CURDATE()";
            $params = [];

            if ($range) {
                $hours = 24;
                if ($range === '1h') $hours = 1;
                elseif ($range === '6h') $hours = 6;
                elseif ($range === '24h') $hours = 24;
                $whereTime = "created_at >= DATE_SUB(NOW(), INTERVAL $hours HOUR)";
            } elseif ($from && $to) {
                $whereTime = "DATE(created_at) >= ? AND DATE(created_at) <= ?";
                $params = [$from, $to];
            }

            // If specific device_ids are requested (e.g. for custom room filter)
            $customHistory = [];
            if ($deviceIdsParam) {
                $ids = array_filter(array_map('trim', explode(',', $deviceIdsParam)));
                foreach ($ids as $dId) {
                    $sqlCustom = "SELECT temperature, humidity, air_quality, created_at 
                                  FROM log_ruang_lab 
                                  WHERE device_id = ? AND $whereTime
                                  ORDER BY created_at ASC";
                    $qParams = array_merge([$dId], $params);
                    $qRes = $db->query($sqlCustom, $qParams)->getResultArray();
                    $arr = [];
                    foreach ($qRes as $r) {
                        $arr[] = [
                            'suhu' => (float)$r['temperature'],
                            'hum' => (float)$r['humidity'],
                            'mq' => (int)$r['air_quality'],
                            'waktu' => $r['created_at']
                        ];
                    }
                    $customHistory[$dId] = $arr;
                }
            }

            // Fetch Alat 1 (AC)
            $sql1 = "SELECT temperature, humidity, air_quality, created_at 
                     FROM log_ruang_lab 
                     WHERE device_id = 'ALAT_1_AC' AND $whereTime
                     ORDER BY created_at ASC";
            $query1 = $db->query($sql1, $params);
            $results1 = $query1->getResultArray();

            // Fetch Alat 2 (Non-AC)
            $sql2 = "SELECT temperature, humidity, air_quality, created_at 
                     FROM log_ruang_lab 
                     WHERE device_id = 'ALAT_2_NON_AC' AND $whereTime
                     ORDER BY created_at ASC";
            $query2 = $db->query($sql2, $params);
            $results2 = $query2->getResultArray();

            // Fetch Keuangan (ALAT_1)
            $sqlKeu = "SELECT temperature, humidity, occupancy, ac_status, created_at 
                       FROM log_ruang_keuangan 
                       WHERE device_id = 'ALAT_1' AND $whereTime
                       ORDER BY created_at ASC";
            $resultsKeu = $db->query($sqlKeu, $params)->getResultArray();

            // Build separate arrays for each device
            $lab_ac = [];
            foreach ($results1 as $row) {
                $lab_ac[] = [
                    'suhu' => (float)$row['temperature'],
                    'hum' => (float)$row['humidity'],
                    'mq' => (int)$row['air_quality'],
                    'waktu' => $row['created_at']
                ];
            }

            $lab_non_ac = [];
            foreach ($results2 as $row) {
                $lab_non_ac[] = [
                    'suhu' => (float)$row['temperature'],
                    'hum' => (float)$row['humidity'],
                    'mq' => (int)$row['air_quality'],
                    'waktu' => $row['created_at']
                ];
            }

            $keuangan = [];
            foreach ($resultsKeu as $row) {
                $keuangan[] = [
                    'suhu' => (float)$row['temperature'],
                    'hum' => (float)$row['humidity'],
                    'kehadiran' => (int)$row['occupancy'],
                    'acStatus' => (bool)$row['ac_status'],
                    'waktu' => $row['created_at']
                ];
            }

            return $this->respond([
                'status' => 'success',
                'lab_ac' => $lab_ac,
                'lab_non_ac' => $lab_non_ac,
                'keuangan' => $keuangan,
                'custom_history' => $customHistory,
                'custom_devices' => $customHistory
            ]);
        }

        if ($key === 'hulsDocuments') {
            $docs = $this->getHulsDocumentsList($db);
            $q = strtolower(trim((string)($this->request->getGet('q') ?? '')));
            $statusFilter = trim((string)($this->request->getGet('status') ?? ''));

            if ($q !== '' || $statusFilter !== '') {
                $docs = array_values(array_filter($docs, function ($item) use ($q, $statusFilter) {
                    if ($statusFilter !== '' && strcasecmp($item['status_dokumen'] ?? '', $statusFilter) !== 0) {
                        return false;
                    }
                    if ($q !== '') {
                        $haystack = strtolower(
                            ($item['nomor_huls'] ?? '') . ' ' .
                            ($item['kode_sampel'] ?? '') . ' ' .
                            ($item['nama_sampel'] ?? '') . ' ' .
                            ($item['nama_penguji'] ?? '') . ' ' .
                            ($item['parameter_uji'] ?? '')
                        );
                        if (strpos($haystack, $q) === false) {
                            return false;
                        }
                    }
                    return true;
                }));
            }

            return $this->respond([
                'status' => 'success',
                'documents' => $docs,
                'audit_logs' => $this->getHulsAuditLogs($db)
            ]);
        }

        // ============================================================
        // GET RENLAK (Rencana Pelaksanaan / Kegiatan Laboratorium)
        // ============================================================
        if ($key === 'renlakData') {
            $activities = $this->getRenlakActivitiesList($db);
            $q          = strtolower(trim((string)($this->request->getGet('q') ?? '')));
            $tahun      = trim((string)($this->request->getGet('tahun') ?? ''));
            $bulan      = trim((string)($this->request->getGet('bulan') ?? ''));
            $kategori   = trim((string)($this->request->getGet('kategori') ?? ''));
            $status     = trim((string)($this->request->getGet('status') ?? ''));

            if ($q !== '' || $tahun !== '' || $bulan !== '' || $kategori !== '' || $status !== '') {
                $activities = array_values(array_filter($activities, function ($item) use ($q, $tahun, $bulan, $kategori, $status) {
                    if ($kategori !== '' && strcasecmp($item['kategori'] ?? '', $kategori) !== 0) {
                        return false;
                    }
                    if ($status !== '' && strcasecmp($item['status_kegiatan'] ?? '', $status) !== 0) {
                        return false;
                    }
                    if ($tahun !== '') {
                        $yrInt = (int)$tahun;
                        $hasYearInPlan = false;
                        foreach (array_keys($item['jadwal_rencana'] ?? []) as $wk) {
                            if (strpos($wk, $tahun . '-') === 0 && (float)($item['jadwal_rencana'][$wk] ?? 0) > 0) {
                                $hasYearInPlan = true;
                                break;
                            }
                        }
                        foreach (array_keys($item['jadwal_realisasi'] ?? []) as $wk) {
                            if (strpos($wk, $tahun . '-') === 0 && (float)($item['jadwal_realisasi'][$wk] ?? 0) > 0) {
                                $hasYearInPlan = true;
                                break;
                            }
                        }
                        $tMulai   = (int)($item['tahun_mulai'] ?? 2026);
                        $tSelesai = (int)($item['tahun_selesai'] ?? 2027);
                        if (!$hasYearInPlan && ($yrInt < $tMulai || $yrInt > $tSelesai)) {
                            return false;
                        }
                    }
                    if ($bulan !== '') {
                        $mPad = str_pad((string)(int)$bulan, 2, '0', STR_PAD_LEFT);
                        $hasMonth = false;
                        foreach (array_keys($item['jadwal_rencana'] ?? []) as $wk) {
                            if (preg_match('/^\d{4}-' . $mPad . '-\d+$/', $wk) && (float)($item['jadwal_rencana'][$wk] ?? 0) > 0) {
                                $hasMonth = true;
                                break;
                            }
                        }
                        if (!$hasMonth) {
                            foreach (array_keys($item['jadwal_realisasi'] ?? []) as $wk) {
                                if (preg_match('/^\d{4}-' . $mPad . '-\d+$/', $wk) && (float)($item['jadwal_realisasi'][$wk] ?? 0) > 0) {
                                    $hasMonth = true;
                                    break;
                                }
                            }
                        }
                        if (!$hasMonth) {
                            return false;
                        }
                    }
                    if ($q !== '') {
                        $haystack = strtolower(
                            ($item['nomor_urut'] ?? '') . ' ' .
                            ($item['kategori'] ?? '') . ' ' .
                            ($item['nama_kegiatan'] ?? '') . ' ' .
                            ($item['rencana_kegiatan'] ?? '') . ' ' .
                            ($item['satuan'] ?? '') . ' ' .
                            ($item['pic_penanggung_jawab'] ?? '') . ' ' .
                            ($item['catatan_evaluasi'] ?? '')
                        );
                        if (strpos($haystack, $q) === false) {
                            return false;
                        }
                    }
                    return true;
                }));
            }

            return $this->respond([
                'status'     => 'success',
                'activities' => $activities,
                'audit_logs' => $this->getRenlakAuditLogs($db)
            ]);
        }

        // Return IoT settings for the admin panel
        $query = $db->query("SELECT json_data FROM spbe_json_store WHERE key_name = 'iot_settings'");
        $row = $query->getRow();
        $settings = $row ? json_decode($row->json_data, true) : [];
        return $this->respond(['iot_settings' => $settings]);
    }

    private function ensureHulsTables($db): bool
    {
        try {
            $db->query("
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
                    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
            $db->query("
                CREATE TABLE IF NOT EXISTS `huls_audit_logs` (
                    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    `huls_id` INT UNSIGNED NOT NULL,
                    `nomor_huls` VARCHAR(100) DEFAULT NULL,
                    `action` VARCHAR(100) NOT NULL,
                    `actor_name` VARCHAR(100) DEFAULT NULL,
                    `actor_role` VARCHAR(100) DEFAULT NULL,
                    `notes` TEXT DEFAULT NULL,
                    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function getHulsDocumentsList($db): array
    {
        if ($this->ensureHulsTables($db)) {
            try {
                $rows = $db->query("SELECT * FROM huls_documents ORDER BY updated_at DESC, id DESC")->getResultArray();
                $result = [];
                foreach ($rows as $r) {
                    $r['id'] = (int)$r['id'];
                    $r['pre_investigasi'] = json_decode($r['pre_investigasi_json'] ?? '{}', true) ?: [];
                    $r['tahap1'] = json_decode($r['tahap1_json'] ?? '{}', true) ?: [];
                    unset($r['pre_investigasi_json'], $r['tahap1_json']);
                    $result[] = $r;
                }
                return $result;
            } catch (\Throwable $e) {}
        }

        // Fallback to spbe_json_store
        try {
            $q = $db->query("SELECT json_data FROM spbe_json_store WHERE key_name = 'huls_documents_store'");
            $row = $q->getRow();
            if ($row && $row->json_data) {
                $decoded = json_decode($row->json_data, true);
                if (is_array($decoded)) {
                    return $decoded;
                }
            }
        } catch (\Throwable $e) {}

        return [];
    }

    private function getHulsAuditLogs($db): array
    {
        if ($this->ensureHulsTables($db)) {
            try {
                return $db->query("SELECT * FROM huls_audit_logs ORDER BY created_at DESC, id DESC LIMIT 40")->getResultArray();
            } catch (\Throwable $e) {}
        }
        return [];
    }

    // =========================================================================
    // RENLAK (RENCANA PELAKSANAAN / KEGIATAN LABORATORIUM) HELPERS & SEEDER
    // =========================================================================
    private function getDefaultRenlakSeedData(): array
    {
        $now = '2026-10-29 08:00:00';
        return [
            [
                'id'                   => 1,
                'nomor_urut'           => '1',
                'kategori'             => 'Perencanaan',
                'nama_kegiatan'        => 'Pembentukan Tim Digitalisasi LAB',
                'rencana_kegiatan'     => 'Penyusunan dan penetapan SK Tim Transformasi Digitalisasi Laboratorium BBPOM di Bandung.',
                'realisasi'            => 1,
                'target'               => 1,
                'satuan'               => 'SK',
                'persentase_capaian'   => 100.00,
                'tahun_mulai'          => 2026,
                'tahun_selesai'        => 2027,
                'status_kegiatan'      => 'Selesai',
                'pic_penanggung_jawab' => 'Manajer Puncak & Tim Manajemen',
                'catatan_evaluasi'     => 'SK Tim Digitalisasi Lab telah diterbitkan.',
                'jadwal_rencana'       => ['2026-09-1' => 1],
                'jadwal_realisasi'     => ['2026-10-4' => 1],
                'created_by'           => 'Admin Lab',
                'updated_by'           => 'Admin Lab',
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
            [
                'id'                   => 2,
                'nomor_urut'           => '2',
                'kategori'             => 'Perencanaan',
                'nama_kegiatan'        => 'Penyusunan Program Kerja Tim Digitalisasi LAB',
                'rencana_kegiatan'     => 'Penyusunan dokumen roadmap dan rencana aksi pelaksanaan digitalisasi laboratorium 2026-2027.',
                'realisasi'            => 1,
                'target'               => 1,
                'satuan'               => 'Dokumen',
                'persentase_capaian'   => 100.00,
                'tahun_mulai'          => 2026,
                'tahun_selesai'        => 2027,
                'status_kegiatan'      => 'Selesai',
                'pic_penanggung_jawab' => 'Koordinator Perencanaan Lab',
                'catatan_evaluasi'     => 'Dokumen Program Kerja Tim Digitalisasi Lab telah disahkan.',
                'jadwal_rencana'       => ['2026-10-4' => 1],
                'jadwal_realisasi'     => ['2026-10-4' => 1],
                'created_by'           => 'Admin Lab',
                'updated_by'           => 'Admin Lab',
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
            [
                'id'                   => 3,
                'nomor_urut'           => '4',
                'kategori'             => 'Pengembangan Digitalisasi LAB',
                'nama_kegiatan'        => 'Pengembangan DATA BASE Fitur Digitalisasi LAB',
                'rencana_kegiatan'     => 'Perancangan dan pembangunan skema database terintegrasi untuk seluruh fitur modul Digitalisasi Lab.',
                'realisasi'            => 2,
                'target'               => 6,
                'satuan'               => 'Database',
                'persentase_capaian'   => 33.33,
                'tahun_mulai'          => 2026,
                'tahun_selesai'        => 2027,
                'status_kegiatan'      => 'Berjalan',
                'pic_penanggung_jawab' => 'Tim TI & Database Lab',
                'catatan_evaluasi'     => '2 modul database (IoT & HULS/Renlak) telah selesai; 4 modul tahap berikutnya terjadwal.',
                'jadwal_rencana'       => [
                    '2026-10-4' => 2,
                    '2026-11-3' => 1,
                    '2027-03-3' => 1,
                    '2027-06-2' => 1,
                    '2027-09-1' => 1,
                ],
                'jadwal_realisasi'     => [
                    '2026-10-4' => 2,
                ],
                'created_by'           => 'Admin Lab',
                'updated_by'           => 'Admin Lab',
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
            [
                'id'                   => 4,
                'nomor_urut'           => '5',
                'kategori'             => 'Pengembangan Digitalisasi LAB',
                'nama_kegiatan'        => 'Pengembangan Aplikasi Digitalisasi LAB',
                'rencana_kegiatan'     => 'Pengembangan platform utama portal web Digitalisasi Laboratorium BBPOM di Bandung (DigitLab BDG).',
                'realisasi'            => 1,
                'target'               => 2,
                'satuan'               => 'Aplikasi',
                'persentase_capaian'   => 50.00,
                'tahun_mulai'          => 2026,
                'tahun_selesai'        => 2027,
                'status_kegiatan'      => 'Berjalan',
                'pic_penanggung_jawab' => 'Tim Pengembang Aplikasi',
                'catatan_evaluasi'     => 'Rilis tahap 1 aplikasi web DigitLab BDG telah berjalan.',
                'jadwal_rencana'       => [
                    '2026-10-2' => 1,
                    '2027-06-2' => 1,
                ],
                'jadwal_realisasi'     => [
                    '2026-10-4' => 1,
                ],
                'created_by'           => 'Admin Lab',
                'updated_by'           => 'Admin Lab',
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
            [
                'id'                   => 5,
                'nomor_urut'           => '6',
                'kategori'             => 'Pengembangan Digitalisasi LAB',
                'nama_kegiatan'        => 'Pengembangan Aplikasi Digitalisasi LAB - Fitur Akomodasi Lingkungan (IoT)',
                'rencana_kegiatan'     => 'Integrasi sensor suhu, kelembapan, kualitas udara, saklar cahaya otomatis, dan laporan resmi BBPOM.',
                'realisasi'            => 1,
                'target'               => 1,
                'satuan'               => 'Fitur',
                'persentase_capaian'   => 100.00,
                'tahun_mulai'          => 2026,
                'tahun_selesai'        => 2027,
                'status_kegiatan'      => 'Selesai',
                'pic_penanggung_jawab' => 'Tim IoT & Sarpras Lab',
                'catatan_evaluasi'     => 'Fitur monitoring IoT dan cetak PDF resmi 3 halaman selesai 100%.',
                'jadwal_rencana'       => [
                    '2026-10-4' => 1,
                ],
                'jadwal_realisasi'     => [
                    '2026-10-4' => 1,
                ],
                'created_by'           => 'Admin Lab',
                'updated_by'           => 'Admin Lab',
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
            [
                'id'                   => 6,
                'nomor_urut'           => '7',
                'kategori'             => 'Pengembangan Digitalisasi LAB',
                'nama_kegiatan'        => 'Pengembangan Aplikasi Digitalisasi LAB - Fitur Pelaksanaan Pengujian dan LIMS',
                'rencana_kegiatan'     => 'Pengembangan sub-modul pengelolaan sampel, status pengujian, integrasi LIMS, reagen, pengesahan, dan HULS.',
                'realisasi'            => 0,
                'target'               => 2,
                'satuan'               => 'Fitur',
                'persentase_capaian'   => 0.00,
                'tahun_mulai'          => 2026,
                'tahun_selesai'        => 2027,
                'status_kegiatan'      => 'Belum Dimulai',
                'pic_penanggung_jawab' => 'Tim Pengujian & LIMS',
                'catatan_evaluasi'     => 'Terjadwal pada November dan Desember 2026.',
                'jadwal_rencana'       => [
                    '2026-11-2' => 1,
                    '2026-12-3' => 1,
                ],
                'jadwal_realisasi'     => [],
                'created_by'           => 'Admin Lab',
                'updated_by'           => 'Admin Lab',
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
            [
                'id'                   => 7,
                'nomor_urut'           => '8',
                'kategori'             => 'Pengembangan Digitalisasi LAB',
                'nama_kegiatan'        => 'Pengembangan Aplikasi Digitalisasi LAB - Fitur Keamanan dan Integritas Data LAB',
                'rencana_kegiatan'     => 'Implementasi audit trail, kontrol akses berbasis peran, dan proteksi integritas rekaman laboratorium.',
                'realisasi'            => 0,
                'target'               => 1,
                'satuan'               => 'Fitur',
                'persentase_capaian'   => 0.00,
                'tahun_mulai'          => 2026,
                'tahun_selesai'        => 2027,
                'status_kegiatan'      => 'Belum Dimulai',
                'pic_penanggung_jawab' => 'Tim Keamanan Informasi',
                'catatan_evaluasi'     => 'Terjadwal pada Minggu ke-4 November 2026.',
                'jadwal_rencana'       => [
                    '2026-11-4' => 1,
                ],
                'jadwal_realisasi'     => [],
                'created_by'           => 'Admin Lab',
                'updated_by'           => 'Admin Lab',
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
            [
                'id'                   => 8,
                'nomor_urut'           => '9',
                'kategori'             => 'Pengembangan Digitalisasi LAB',
                'nama_kegiatan'        => 'Pengembangan Aplikasi Digitalisasi LAB - Fitur Pengelolaan Instrumen, Inventori & Kalibrasi',
                'rencana_kegiatan'     => 'Digitalisasi logbook instrumen, jadwal kalibrasi berkala, dan manajemen stok inventori laboratorium.',
                'realisasi'            => 0,
                'target'               => 3,
                'satuan'               => 'Fitur',
                'persentase_capaian'   => 0.00,
                'tahun_mulai'          => 2026,
                'tahun_selesai'        => 2027,
                'status_kegiatan'      => 'Belum Dimulai',
                'pic_penanggung_jawab' => 'Penyelia Instrumen & Kalibrasi',
                'catatan_evaluasi'     => 'Terjadwal Desember 2026 s/d Februari 2027.',
                'jadwal_rencana'       => [
                    '2026-12-1' => 1,
                    '2027-01-1' => 1,
                    '2027-02-1' => 1,
                ],
                'jadwal_realisasi'     => [],
                'created_by'           => 'Admin Lab',
                'updated_by'           => 'Admin Lab',
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
            [
                'id'                   => 9,
                'nomor_urut'           => '8',
                'kategori'             => 'Pengembangan Digitalisasi LAB',
                'nama_kegiatan'        => 'Pengembangan Aplikasi Digitalisasi LAB - Fitur Metode, Validasi, Mutu & Kompetensi SDM',
                'rencana_kegiatan'     => 'Pengembangan repositori metode uji, verifikasi/validasi metode, jaminan mutu, dan matriks kompetensi analis.',
                'realisasi'            => 0,
                'target'               => 4,
                'satuan'               => 'Fitur',
                'persentase_capaian'   => 0.00,
                'tahun_mulai'          => 2026,
                'tahun_selesai'        => 2027,
                'status_kegiatan'      => 'Belum Dimulai',
                'pic_penanggung_jawab' => 'Manajer Mutu & SDM Lab',
                'catatan_evaluasi'     => 'Terjadwal Desember 2026 s/d Maret 2027.',
                'jadwal_rencana'       => [
                    '2026-12-3' => 1,
                    '2027-01-3' => 1,
                    '2027-02-4' => 1,
                    '2027-03-4' => 1,
                ],
                'jadwal_realisasi'     => [],
                'created_by'           => 'Admin Lab',
                'updated_by'           => 'Admin Lab',
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
            [
                'id'                   => 10,
                'nomor_urut'           => '1',
                'kategori'             => 'Monitoring dan Evaluasi',
                'nama_kegiatan'        => 'Rekon Data, Monitoring dan Evaluasi DIGITLAB',
                'rencana_kegiatan'     => 'Rekonsiliasi data bulanan serta evaluasi berkala efektivitas implementasi sistem DigitLab BDG.',
                'realisasi'            => 0,
                'target'               => 11,
                'satuan'               => 'Persen',
                'persentase_capaian'   => 0.00,
                'tahun_mulai'          => 2026,
                'tahun_selesai'        => 2027,
                'status_kegiatan'      => 'Belum Dimulai',
                'pic_penanggung_jawab' => 'Tim Monev Digitalisasi Lab',
                'catatan_evaluasi'     => 'Pelaksanaan rutin setiap bulan mulai November 2026 s/d September 2027.',
                'jadwal_rencana'       => [
                    '2026-11-1' => 1,
                    '2026-12-1' => 1,
                    '2026-12-4' => 1,
                    '2027-01-2' => 1,
                    '2027-02-2' => 1,
                    '2027-03-3' => 1,
                    '2027-04-3' => 1,
                    '2027-05-3' => 1,
                    '2027-06-4' => 1,
                    '2027-07-4' => 1,
                    '2027-09-1' => 1,
                ],
                'jadwal_realisasi'     => [],
                'created_by'           => 'Admin Lab',
                'updated_by'           => 'Admin Lab',
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
            [
                'id'                   => 11,
                'nomor_urut'           => '2',
                'kategori'             => 'Monitoring dan Evaluasi',
                'nama_kegiatan'        => 'Tinjau Ulang Manajemen DIGITLAB',
                'rencana_kegiatan'     => 'Rapat Tinjauan Manajemen (RTM) khusus evaluasi capaian indikator dan keberlanjutan inovasi DigitLab.',
                'realisasi'            => 0,
                'target'               => 1,
                'satuan'               => 'Persen',
                'persentase_capaian'   => 0.00,
                'tahun_mulai'          => 2026,
                'tahun_selesai'        => 2027,
                'status_kegiatan'      => 'Belum Dimulai',
                'pic_penanggung_jawab' => 'Manajer Puncak & Seluruh Penyelia',
                'catatan_evaluasi'     => 'Terjadwal pada Januari 2027 Minggu ke-1.',
                'jadwal_rencana'       => [
                    '2027-01-1' => 1,
                ],
                'jadwal_realisasi'     => [],
                'created_by'           => 'Admin Lab',
                'updated_by'           => 'Admin Lab',
                'created_at'           => $now,
                'updated_at'           => $now,
            ],
        ];
    }

    private function ensureRenlakTables($db): bool
    {
        try {
            $db->query("
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
                    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
            $db->query("
                CREATE TABLE IF NOT EXISTS `renlak_audit_logs` (
                    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    `renlak_id` INT UNSIGNED NOT NULL DEFAULT 0,
                    `nama_kegiatan` VARCHAR(255) DEFAULT NULL,
                    `action` VARCHAR(100) NOT NULL,
                    `actor_name` VARCHAR(100) DEFAULT NULL,
                    `actor_role` VARCHAR(100) DEFAULT NULL,
                    `notes` TEXT DEFAULT NULL,
                    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");

            // Seed initial 11 activities from Excel reference if table is completely empty
            $cntRow = $db->query("SELECT COUNT(*) AS cnt FROM renlak_activities")->getRow();
            if ($cntRow && (int)$cntRow->cnt === 0) {
                $seeds = $this->getDefaultRenlakSeedData();
                foreach ($seeds as $s) {
                    $db->query("
                        INSERT INTO renlak_activities
                        (nomor_urut, kategori, nama_kegiatan, rencana_kegiatan, realisasi, target, satuan, persentase_capaian, tahun_mulai, tahun_selesai, status_kegiatan, pic_penanggung_jawab, catatan_evaluasi, jadwal_rencana_json, jadwal_realisasi_json, created_by, updated_by, created_at, updated_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ", [
                        $s['nomor_urut'], $s['kategori'], $s['nama_kegiatan'], $s['rencana_kegiatan'],
                        $s['realisasi'], $s['target'], $s['satuan'], $s['persentase_capaian'],
                        $s['tahun_mulai'], $s['tahun_selesai'], $s['status_kegiatan'],
                        $s['pic_penanggung_jawab'], $s['catatan_evaluasi'],
                        json_encode($s['jadwal_rencana'], JSON_UNESCAPED_UNICODE),
                        json_encode($s['jadwal_realisasi'], JSON_UNESCAPED_UNICODE),
                        $s['created_by'], $s['updated_by'], $s['created_at'], $s['updated_at']
                    ]);
                }
                $db->query("
                    INSERT INTO renlak_audit_logs (renlak_id, nama_kegiatan, action, actor_name, actor_role, notes, created_at)
                    VALUES (0, 'Inisialisasi Sistem RENLAK', 'SEED_INITIAL', 'System', 'Administrator', 'Memuat 11 kegiatan awal sesuai matriks Excel RENLAK 2026-2027', ?)
                ", [date('Y-m-d H:i:s')]);
            }
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function getRenlakActivitiesList($db): array
    {
        if ($this->ensureRenlakTables($db)) {
            try {
                $rows = $db->query("SELECT * FROM renlak_activities ORDER BY id ASC")->getResultArray();
                $result = [];
                foreach ($rows as $r) {
                    $r['id']                 = (int)$r['id'];
                    $r['realisasi']          = (float)$r['realisasi'];
                    $r['target']             = (float)$r['target'];
                    $r['persentase_capaian'] = $r['target'] > 0 ? round(($r['realisasi'] / $r['target']) * 100, 2) : 0.0;
                    $r['tahun_mulai']        = (int)($r['tahun_mulai'] ?? 2026);
                    $r['tahun_selesai']      = (int)($r['tahun_selesai'] ?? 2027);
                    $r['jadwal_rencana']     = json_decode($r['jadwal_rencana_json'] ?? '{}', true) ?: [];
                    $r['jadwal_realisasi']   = json_decode($r['jadwal_realisasi_json'] ?? '{}', true) ?: [];
                    unset($r['jadwal_rencana_json'], $r['jadwal_realisasi_json']);
                    $result[] = $r;
                }
                return $result;
            } catch (\Throwable $e) {}
        }

        // Fallback 1: spbe_json_store
        try {
            $q = $db->query("SELECT json_data FROM spbe_json_store WHERE key_name = 'renlak_activities_store'");
            $row = $q->getRow();
            if ($row && $row->json_data) {
                $decoded = json_decode($row->json_data, true);
                if (is_array($decoded) && count($decoded) > 0) {
                    return $decoded;
                }
            }
        } catch (\Throwable $e) {}

        // Fallback 2: local writable JSON file
        $fileFallback = WRITEPATH . 'renlak_store.json';
        if (is_file($fileFallback)) {
            $raw = @file_get_contents($fileFallback);
            $decoded = $raw ? json_decode($raw, true) : null;
            if (is_array($decoded) && isset($decoded['activities'])) {
                return $decoded['activities'];
            }
        }

        return $this->getDefaultRenlakSeedData();
    }

    private function saveRenlakFallbackStore($db, array $activities, array $auditLogs): void
    {
        $jsonActs = json_encode(array_values($activities), JSON_UNESCAPED_UNICODE);
        try {
            $q = $db->query("SELECT key_name FROM spbe_json_store WHERE key_name = 'renlak_activities_store'");
            if ($q->getRow()) {
                $db->query("UPDATE spbe_json_store SET json_data = ? WHERE key_name = 'renlak_activities_store'", [$jsonActs]);
            } else {
                $db->query("INSERT INTO spbe_json_store (key_name, json_data) VALUES ('renlak_activities_store', ?)", [$jsonActs]);
            }
        } catch (\Throwable $e) {}

        $fileFallback = WRITEPATH . 'renlak_store.json';
        @file_put_contents($fileFallback, json_encode([
            'activities' => array_values($activities),
            'audit_logs' => array_slice($auditLogs, 0, 60)
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    private function getRenlakAuditLogs($db): array
    {
        if ($this->ensureRenlakTables($db)) {
            try {
                return $db->query("SELECT * FROM renlak_audit_logs ORDER BY created_at DESC, id DESC LIMIT 50")->getResultArray();
            } catch (\Throwable $e) {}
        }
        $fileFallback = WRITEPATH . 'renlak_store.json';
        if (is_file($fileFallback)) {
            $raw = @file_get_contents($fileFallback);
            $decoded = $raw ? json_decode($raw, true) : null;
            if (is_array($decoded) && isset($decoded['audit_logs'])) {
                return $decoded['audit_logs'];
            }
        }
        return [];
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
        if ($roomId === 'KEUANGAN' || $roomId === 'ALAT_1') {
            $acStatus = isset($json->ac) ? (int)$json->ac : 0;
            $occupancy = isset($json->occ) ? (int)$json->occ : 0;
            $sql = "INSERT INTO log_ruang_keuangan (device_id, temperature, humidity, occupancy, ac_status, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $db->query($sql, ['ALAT_1', $temperature, $humidity, $occupancy, $acStatus, date('Y-m-d H:i:s')]);
        } elseif ($roomId === 'KEUANGAN_CAHAYA' || $roomId === 'ALAT_2_CAHAYA' || isset($json->lumens)) {
            $devLightId = ($roomId === 'KEUANGAN_CAHAYA' || $roomId === 'ALAT_2_CAHAYA') ? 'ALAT_2_CAHAYA' : $roomId;
            $lumensVal = isset($json->lumens) ? (int)$json->lumens : (isset($json->lux) ? (int)$json->lux : $airQuality);
            $lampuOn = isset($json->lampu) ? (int)$json->lampu : ($lumensVal < 250 ? 1 : ($lumensVal > 500 ? 0 : 1));
            $servoAngle = isset($json->servo) ? (int)$json->servo : ($lampuOn ? 90 : 0);

            $sql = "INSERT INTO log_ruang_lab (device_id, temperature, humidity, air_quality, created_at) 
                    VALUES (?, ?, ?, ?, ?)";
            $db->query($sql, [$devLightId, $lampuOn, $servoAngle, $lumensVal, date('Y-m-d H:i:s')]);
        } else {
            // Default: masuk ke tabel Lab (mendukung ALAT_1_AC, ALAT_2_NON_AC, maupun ID alat baru apa pun)
            if ($roomId === 'LAB' || $roomId === 'LAB_AC' || $roomId === 'ALAT_1_AC') {
                $deviceId = 'ALAT_1_AC';
            } elseif ($roomId === 'LAB_NON_AC' || $roomId === 'ALAT_2_NON_AC') {
                $deviceId = 'ALAT_2_NON_AC';
            } else {
                $deviceId = $roomId;
            }
            $sql = "INSERT INTO log_ruang_lab (device_id, temperature, humidity, air_quality, created_at) 
                    VALUES (?, ?, ?, ?, ?)";
            $db->query($sql, [$deviceId, $temperature, $humidity, $airQuality, date('Y-m-d H:i:s')]);

            // Jika alat baru juga mengirim status AC / kehadiran
            if (isset($json->ac) || isset($json->occ)) {
                $acStatus = isset($json->ac) ? (int)$json->ac : 0;
                $occupancy = isset($json->occ) ? (int)$json->occ : 0;
                $sqlKeu = "INSERT INTO log_ruang_keuangan (device_id, temperature, humidity, occupancy, ac_status, created_at) 
                           VALUES (?, ?, ?, ?, ?, ?)";
                $db->query($sqlKeu, [$deviceId, $temperature, $humidity, $occupancy, $acStatus, date('Y-m-d H:i:s')]);
            }
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
                    'number' => '082123456789',
                    'message' => "🚨 *Peringatan SPBE BBPOM* 🚨\n\n📍 Ruang Reagen\nSuhu Saat ini: {$temperature}°C\nBatas Aman: 25.0°C\nKelembapan: {$humidity}%\nKualitas Udara: {$airQuality} PPM\n\nSegera lakukan pengecekan!"
                ]);
                
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                
                $response = curl_exec($ch);
                curl_close($ch);

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

        // Custom Rooms & Devices Configuration via Dashboard
        if ($key === 'customConfig') {
            $jsonString = json_encode($json);
            $query = $db->query("SELECT key_name FROM spbe_json_store WHERE key_name = 'custom_iot_config'");
            if ($query->getRow()) {
                $db->query("UPDATE spbe_json_store SET json_data = ? WHERE key_name = 'custom_iot_config'", [$jsonString]);
            } else {
                $db->query("INSERT INTO spbe_json_store (key_name, json_data) VALUES ('custom_iot_config', ?)", [$jsonString]);
            }
            return $this->respond(['status' => 'success', 'message' => 'Konfigurasi ruangan & alat berhasil disimpan']);
        }

        // Light Intensity Sensor & Servo Switch Control (Auto / Manual Switch ON-OFF)
        if ($key === 'lightServoControl') {
            $jsonString = json_encode($json);
            try {
                $query = $db->query("SELECT key_name FROM spbe_json_store WHERE key_name = 'light_servo_state'");
                if ($query->getRow()) {
                    $db->query("UPDATE spbe_json_store SET json_data = ? WHERE key_name = 'light_servo_state'", [$jsonString]);
                } else {
                    $db->query("INSERT INTO spbe_json_store (key_name, json_data) VALUES ('light_servo_state', ?)", [$jsonString]);
                }
            } catch (\Throwable $e) {}
            return $this->respond(['status' => 'success', 'message' => 'Status saklar servo lampu berhasil diperbarui']);
        }

        // Insert Sample / Simulated Sensor Data into Database for Demo
        if ($key === 'simulateDeviceData') {
            $deviceId = $json->deviceId ?? '';
            if (!$deviceId) {
                return $this->fail('Device ID wajib diisi');
            }
            $suhu = isset($json->suhu) ? (float)$json->suhu : 23.0;
            $hum = isset($json->hum) ? (float)$json->hum : 58.0;
            $mq = isset($json->mq) ? (int)$json->mq : 650;
            $ac = isset($json->ac) ? (int)$json->ac : 1;
            $occ = isset($json->occ) ? (int)$json->occ : 2;

            $sql = "INSERT INTO log_ruang_lab (device_id, temperature, humidity, air_quality, created_at) VALUES (?, ?, ?, ?, ?)";
            $db->query($sql, [$deviceId, $suhu, $hum, $mq, date('Y-m-d H:i:s')]);

            try {
                $sqlKeu = "INSERT INTO log_ruang_keuangan (device_id, temperature, humidity, occupancy, ac_status, created_at) VALUES (?, ?, ?, ?, ?, ?)";
                $db->query($sqlKeu, [$deviceId, $suhu, $hum, $occ, $ac, date('Y-m-d H:i:s')]);
            } catch (\Throwable $e) {}

            return $this->respond(['status' => 'success', 'message' => 'Sampel data sensor disimpan ke database']);
        }

        // Save (Create / Update) HULS Document (Pre-Investigasi + Investigasi Tahap 1)
        if ($key === 'saveHulsDocument') {
            $payload = json_decode(json_encode($json), true) ?: [];
            $pre = $payload['pre_investigasi'] ?? [];
            $tahap1 = $payload['tahap1'] ?? [];

            $namaSampel = trim((string)($payload['nama_sampel'] ?? $pre['nama_sampel'] ?? $tahap1['nama_sampel'] ?? ''));
            $kodeSampel = trim((string)($payload['kode_sampel'] ?? $pre['kode_sampel'] ?? $tahap1['kode_sampel'] ?? ''));
            $nomorHuls  = trim((string)($payload['nomor_huls'] ?? $pre['nomor_huls'] ?? $tahap1['nomor_huls'] ?? ''));

            if ($namaSampel === '' || $kodeSampel === '' || $nomorHuls === '') {
                return $this->failValidationErrors('Nama Sampel, Kode Sampel, dan Nomor HULS wajib diisi.');
            }

            $parameterUji   = trim((string)($payload['parameter_uji'] ?? $pre['parameter_uji'] ?? $tahap1['parameter_uji'] ?? ''));
            $namaPenguji    = trim((string)($payload['nama_penguji'] ?? $pre['nama_penguji'] ?? $tahap1['nama_penguji'] ?? ''));
            $tglPengujian   = trim((string)($payload['tanggal_pengujian'] ?? $pre['tanggal_pengujian'] ?? $tahap1['tanggal_pengujian'] ?? ''));
            $tglDilaporkan  = trim((string)($payload['tanggal_dilaporkan'] ?? $pre['tanggal_dilaporkan'] ?? $tahap1['tanggal_dilaporkan'] ?? ''));
            $hasilUji       = trim((string)($payload['hasil_uji'] ?? $pre['hasil_uji'] ?? $tahap1['hasil_uji'] ?? ''));
            $syarat         = trim((string)($payload['syarat'] ?? $pre['syarat'] ?? $tahap1['syarat'] ?? ''));
            $statusDokumen  = trim((string)($payload['status_dokumen'] ?? 'Draft'));
            $kesimpulanPre  = trim((string)($pre['kesimpulan_pre'] ?? 'TMS_LANJUT_TAHAP_1'));
            $kesimpulanT1   = trim((string)($tahap1['kesimpulan_fase1'] ?? ''));

            $session = session();
            $actorName = $session->get('userName') ?: ($payload['actor_name'] ?? 'Admin Lab');
            $actorRole = $session->get('userRole') ?: ($payload['actor_role'] ?? 'Administrator');
            $now = date('Y-m-d H:i:s');
            $docId = isset($payload['id']) ? (int)$payload['id'] : 0;

            if ($this->ensureHulsTables($db)) {
                try {
                    if ($docId === 0 && $nomorHuls !== '' && $kodeSampel !== '') {
                        $dup = $db->query("SELECT id, pre_investigasi_json, tahap1_json FROM huls_documents WHERE nomor_huls = ? AND kode_sampel = ? ORDER BY id DESC LIMIT 1", [$nomorHuls, $kodeSampel])->getRow();
                        if ($dup) {
                            $docId = (int)$dup->id;
                            $oldPre = json_decode((string)$dup->pre_investigasi_json, true) ?: [];
                            $oldT1  = json_decode((string)$dup->tahap1_json, true) ?: [];
                            foreach ($oldPre as $k => $v) {
                                if ((!isset($pre[$k]) || $pre[$k] === '') && $v !== '') {
                                    $pre[$k] = $v;
                                }
                            }
                            foreach ($oldT1 as $k => $v) {
                                if ((!isset($tahap1[$k]) || $tahap1[$k] === '') && $v !== '') {
                                    $tahap1[$k] = $v;
                                }
                            }
                        }
                    }

                    if ($docId > 0) {
                        $existing = $db->query("SELECT id FROM huls_documents WHERE id = ?", [$docId])->getRow();
                        if ($existing) {
                            $db->query("
                                UPDATE huls_documents SET
                                    nomor_huls = ?,
                                    kode_sampel = ?,
                                    nama_sampel = ?,
                                    parameter_uji = ?,
                                    nama_penguji = ?,
                                    tanggal_pengujian = ?,
                                    tanggal_dilaporkan = ?,
                                    hasil_uji = ?,
                                    syarat = ?,
                                    status_dokumen = ?,
                                    kesimpulan_pre = ?,
                                    kesimpulan_tahap1 = ?,
                                    pre_investigasi_json = ?,
                                    tahap1_json = ?,
                                    updated_by = ?,
                                    updated_at = ?
                                WHERE id = ?
                            ", [
                                $nomorHuls, $kodeSampel, $namaSampel, $parameterUji, $namaPenguji,
                                $tglPengujian, $tglDilaporkan, $hasilUji, $syarat, $statusDokumen,
                                $kesimpulanPre, $kesimpulanT1,
                                json_encode($pre, JSON_UNESCAPED_UNICODE),
                                json_encode($tahap1, JSON_UNESCAPED_UNICODE),
                                $actorName, $now, $docId
                            ]);

                            $actionLabel = $payload['audit_action'] ?? 'UPDATE_DOKUMEN_HULS';
                            $notesLabel  = $payload['audit_notes'] ?? "Memperbarui dokumen HULS {$nomorHuls} (Status: {$statusDokumen})";
                            $db->query("
                                INSERT INTO huls_audit_logs (huls_id, nomor_huls, action, actor_name, actor_role, notes, created_at)
                                VALUES (?, ?, ?, ?, ?, ?, ?)
                            ", [$docId, $nomorHuls, $actionLabel, $actorName, $actorRole, $notesLabel, $now]);
                        } else {
                            $docId = 0;
                        }
                    }

                    if ($docId === 0) {
                        $db->query("
                            INSERT INTO huls_documents
                            (nomor_huls, kode_sampel, nama_sampel, parameter_uji, nama_penguji, tanggal_pengujian, tanggal_dilaporkan, hasil_uji, syarat, status_dokumen, kesimpulan_pre, kesimpulan_tahap1, pre_investigasi_json, tahap1_json, created_by, updated_by, created_at, updated_at)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                        ", [
                            $nomorHuls, $kodeSampel, $namaSampel, $parameterUji, $namaPenguji,
                            $tglPengujian, $tglDilaporkan, $hasilUji, $syarat, $statusDokumen,
                            $kesimpulanPre, $kesimpulanT1,
                            json_encode($pre, JSON_UNESCAPED_UNICODE),
                            json_encode($tahap1, JSON_UNESCAPED_UNICODE),
                            $actorName, $actorName, $now, $now
                        ]);
                        $docId = (int)$db->insertID();
                        $db->query("
                            INSERT INTO huls_audit_logs (huls_id, nomor_huls, action, actor_name, actor_role, notes, created_at)
                            VALUES (?, ?, 'CREATE_DOKUMEN_HULS', ?, ?, ?, ?)
                        ", [$docId, $nomorHuls, $actorName, $actorRole, "Membuat dokumen HULS baru {$nomorHuls} untuk sampel {$kodeSampel}", $now]);
                    }
                } catch (\Throwable $e) {
                    return $this->failServerError('Gagal menyimpan ke tabel HULS: ' . $e->getMessage());
                }
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Dokumen HULS berhasil disimpan ke database.',
                'id' => $docId,
                'documents' => $this->getHulsDocumentsList($db),
                'audit_logs' => $this->getHulsAuditLogs($db)
            ]);
        }

        // Delete HULS Document
        if ($key === 'deleteHulsDocument') {
            $docId = isset($json->id) ? (int)$json->id : 0;
            if ($docId <= 0) {
                return $this->failValidationErrors('ID Dokumen HULS tidak valid.');
            }
            $session = session();
            $actorName = $session->get('userName') ?: 'Admin Lab';
            $actorRole = $session->get('userRole') ?: 'Administrator';

            if ($this->ensureHulsTables($db)) {
                try {
                    $row = $db->query("SELECT nomor_huls, kode_sampel FROM huls_documents WHERE id = ?", [$docId])->getRow();
                    $noHuls = $row ? $row->nomor_huls : ('#' . $docId);
                    $db->query("DELETE FROM huls_documents WHERE id = ?", [$docId]);
                    $db->query("
                        INSERT INTO huls_audit_logs (huls_id, nomor_huls, action, actor_name, actor_role, notes, created_at)
                        VALUES (?, ?, 'DELETE_DOKUMEN_HULS', ?, ?, ?, ?)
                    ", [$docId, $noHuls, $actorName, $actorRole, "Menghapus dokumen HULS {$noHuls}", date('Y-m-d H:i:s')]);
                } catch (\Throwable $e) {}
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Dokumen HULS berhasil dihapus.',
                'documents' => $this->getHulsDocumentsList($db),
                'audit_logs' => $this->getHulsAuditLogs($db)
            ]);
        }

        // =====================================================================
        // SAVE (CREATE / UPDATE) RENLAK ACTIVITY
        // =====================================================================
        if ($key === 'saveRenlakActivity') {
            $payload = json_decode(json_encode($json), true) ?: [];

            $namaKegiatan = trim((string)($payload['nama_kegiatan'] ?? ''));
            $kategori     = trim((string)($payload['kategori'] ?? 'Perencanaan'));
            if ($namaKegiatan === '' || $kategori === '') {
                return $this->failValidationErrors('Nama Kegiatan/Sasaran dan Kategori wajib diisi.');
            }

            $nomorUrut       = trim((string)($payload['nomor_urut'] ?? '1'));
            $rencanaKegiatan = trim((string)($payload['rencana_kegiatan'] ?? ''));
            $realisasi       = isset($payload['realisasi']) ? max(0, (float)$payload['realisasi']) : 0.0;
            $target          = isset($payload['target']) ? max(0, (float)$payload['target']) : 1.0;
            $satuan          = trim((string)($payload['satuan'] ?? 'Dokumen')) ?: 'Dokumen';
            $capaian         = $target > 0 ? round(($realisasi / $target) * 100, 2) : 0.0;
            $tahunMulai      = isset($payload['tahun_mulai']) ? (int)$payload['tahun_mulai'] : 2026;
            $tahunSelesai    = isset($payload['tahun_selesai']) ? (int)$payload['tahun_selesai'] : 2027;
            if ($tahunSelesai < $tahunMulai) {
                $tahunSelesai = $tahunMulai;
            }

            $pic             = trim((string)($payload['pic_penanggung_jawab'] ?? 'Tim Digitalisasi Lab'));
            $catatan         = trim((string)($payload['catatan_evaluasi'] ?? ''));
            $jadwalRencana   = is_array($payload['jadwal_rencana'] ?? null) ? $payload['jadwal_rencana'] : [];
            $jadwalRealisasi = is_array($payload['jadwal_realisasi'] ?? null) ? $payload['jadwal_realisasi'] : [];

            // Clean zero/empty schedule keys
            $cleanRencana = [];
            foreach ($jadwalRencana as $wk => $val) {
                $numVal = (float)$val;
                if ($numVal > 0) {
                    $cleanRencana[$wk] = $numVal;
                }
            }
            $cleanRealisasi = [];
            foreach ($jadwalRealisasi as $wk => $val) {
                $numVal = (float)$val;
                if ($numVal > 0) {
                    $cleanRealisasi[$wk] = $numVal;
                }
            }

            // Determine status automatically or honor manual status
            $statusReq = trim((string)($payload['status_kegiatan'] ?? ''));
            if ($statusReq === 'Otomatis' || $statusReq === '') {
                if ($target > 0 && $realisasi >= $target) {
                    $statusKegiatan = 'Selesai';
                } elseif ($realisasi > 0) {
                    $statusKegiatan = 'Berjalan';
                } else {
                    $statusKegiatan = 'Belum Dimulai';
                }
            } else {
                $statusKegiatan = $statusReq;
            }

            $session   = session();
            $actorName = $session->get('userName') ?: ($payload['actor_name'] ?? 'Admin Lab');
            $actorRole = $session->get('userRole') ?: ($payload['actor_role'] ?? 'Administrator');
            $now       = date('Y-m-d H:i:s');
            $actId     = isset($payload['id']) ? (int)$payload['id'] : 0;

            if ($this->ensureRenlakTables($db)) {
                try {
                    if ($actId > 0) {
                        $existing = $db->query("SELECT id FROM renlak_activities WHERE id = ?", [$actId])->getRow();
                        if ($existing) {
                            $db->query("
                                UPDATE renlak_activities SET
                                    nomor_urut = ?,
                                    kategori = ?,
                                    nama_kegiatan = ?,
                                    rencana_kegiatan = ?,
                                    realisasi = ?,
                                    target = ?,
                                    satuan = ?,
                                    persentase_capaian = ?,
                                    tahun_mulai = ?,
                                    tahun_selesai = ?,
                                    status_kegiatan = ?,
                                    pic_penanggung_jawab = ?,
                                    catatan_evaluasi = ?,
                                    jadwal_rencana_json = ?,
                                    jadwal_realisasi_json = ?,
                                    updated_by = ?,
                                    updated_at = ?
                                WHERE id = ?
                            ", [
                                $nomorUrut, $kategori, $namaKegiatan, $rencanaKegiatan,
                                $realisasi, $target, $satuan, $capaian,
                                $tahunMulai, $tahunSelesai, $statusKegiatan,
                                $pic, $catatan,
                                json_encode($cleanRencana, JSON_UNESCAPED_UNICODE),
                                json_encode($cleanRealisasi, JSON_UNESCAPED_UNICODE),
                                $actorName, $now, $actId
                            ]);

                            $notes = "Memperbarui kegiatan '{$namaKegiatan}' (Realisasi: {$realisasi}/{$target} {$satuan} = {$capaian}%, Status: {$statusKegiatan})";
                            $db->query("
                                INSERT INTO renlak_audit_logs (renlak_id, nama_kegiatan, action, actor_name, actor_role, notes, created_at)
                                VALUES (?, ?, 'UPDATE_KEGIATAN', ?, ?, ?, ?)
                            ", [$actId, $namaKegiatan, $actorName, $actorRole, $notes, $now]);
                        } else {
                            $actId = 0;
                        }
                    }

                    if ($actId === 0) {
                        $db->query("
                            INSERT INTO renlak_activities
                            (nomor_urut, kategori, nama_kegiatan, rencana_kegiatan, realisasi, target, satuan, persentase_capaian, tahun_mulai, tahun_selesai, status_kegiatan, pic_penanggung_jawab, catatan_evaluasi, jadwal_rencana_json, jadwal_realisasi_json, created_by, updated_by, created_at, updated_at)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                        ", [
                            $nomorUrut, $kategori, $namaKegiatan, $rencanaKegiatan,
                            $realisasi, $target, $satuan, $capaian,
                            $tahunMulai, $tahunSelesai, $statusKegiatan,
                            $pic, $catatan,
                            json_encode($cleanRencana, JSON_UNESCAPED_UNICODE),
                            json_encode($cleanRealisasi, JSON_UNESCAPED_UNICODE),
                            $actorName, $actorName, $now, $now
                        ]);
                        $actId = (int)$db->insertID();
                        $notes = "Menambahkan kegiatan baru '{$namaKegiatan}' pada kategori {$kategori} (Target: {$target} {$satuan})";
                        $db->query("
                            INSERT INTO renlak_audit_logs (renlak_id, nama_kegiatan, action, actor_name, actor_role, notes, created_at)
                            VALUES (?, ?, 'CREATE_KEGIATAN', ?, ?, ?, ?)
                        ", [$actId, $namaKegiatan, $actorName, $actorRole, $notes, $now]);
                    }
                } catch (\Throwable $e) {}
            }

            // Also update fallback store
            $allList = $this->getRenlakActivitiesList($db);
            $logsList = $this->getRenlakAuditLogs($db);
            $this->saveRenlakFallbackStore($db, $allList, $logsList);

            return $this->respond([
                'status'     => 'success',
                'message'    => 'Data kegiatan RENLAK berhasil disimpan.',
                'id'         => $actId,
                'activities' => $allList,
                'audit_logs' => $logsList
            ]);
        }

        // =====================================================================
        // TOGGLE / UPDATE WEEK CELL IN RENLAK TIMELINE GANTT
        // =====================================================================
        if ($key === 'toggleRenlakWeekCell') {
            $payload = json_decode(json_encode($json), true) ?: [];
            $actId   = isset($payload['id']) ? (int)$payload['id'] : 0;
            $type    = ($payload['type'] ?? 'rencana') === 'realisasi' ? 'realisasi' : 'rencana';
            $weekKey = trim((string)($payload['week_key'] ?? ''));
            $val     = isset($payload['value']) ? (float)$payload['value'] : 0.0;
            $syncSum = !empty($payload['sync_sum']);

            if ($actId <= 0 || $weekKey === '') {
                return $this->failValidationErrors('Parameter jadwal tidak valid.');
            }

            $session   = session();
            $actorName = $session->get('userName') ?: 'Admin Lab';
            $actorRole = $session->get('userRole') ?: 'Administrator';
            $now       = date('Y-m-d H:i:s');

            if ($this->ensureRenlakTables($db)) {
                try {
                    $row = $db->query("SELECT * FROM renlak_activities WHERE id = ?", [$actId])->getRowArray();
                    if ($row) {
                        $jRencana   = json_decode($row['jadwal_rencana_json'] ?? '{}', true) ?: [];
                        $jRealisasi = json_decode($row['jadwal_realisasi_json'] ?? '{}', true) ?: [];

                        if ($type === 'rencana') {
                            if ($val > 0) {
                                $jRencana[$weekKey] = $val;
                            } else {
                                unset($jRencana[$weekKey]);
                            }
                        } else {
                            if ($val > 0) {
                                $jRealisasi[$weekKey] = $val;
                            } else {
                                unset($jRealisasi[$weekKey]);
                            }
                        }

                        $realisasi = (float)$row['realisasi'];
                        $target    = (float)$row['target'];
                        if ($syncSum && $type === 'realisasi') {
                            $realisasi = array_sum(array_map('floatval', $jRealisasi));
                        }
                        $capaian = $target > 0 ? round(($realisasi / $target) * 100, 2) : 0.0;
                        $statusKegiatan = $row['status_kegiatan'];
                        if ($target > 0 && $realisasi >= $target) {
                            $statusKegiatan = 'Selesai';
                        } elseif ($realisasi > 0 && $statusKegiatan === 'Belum Dimulai') {
                            $statusKegiatan = 'Berjalan';
                        }

                        $db->query("
                            UPDATE renlak_activities SET
                                realisasi = ?,
                                persentase_capaian = ?,
                                status_kegiatan = ?,
                                jadwal_rencana_json = ?,
                                jadwal_realisasi_json = ?,
                                updated_by = ?,
                                updated_at = ?
                            WHERE id = ?
                        ", [
                            $realisasi, $capaian, $statusKegiatan,
                            json_encode($jRencana, JSON_UNESCAPED_UNICODE),
                            json_encode($jRealisasi, JSON_UNESCAPED_UNICODE),
                            $actorName, $now, $actId
                        ]);

                        $labelType = $type === 'realisasi' ? 'Realisasi' : 'Rencana';
                        $notes = "Mengubah jadwal {$labelType} [{$weekKey}] menjadi " . ($val > 0 ? $val : 'kosong') . " pada '{$row['nama_kegiatan']}'";
                        $db->query("
                            INSERT INTO renlak_audit_logs (renlak_id, nama_kegiatan, action, actor_name, actor_role, notes, created_at)
                            VALUES (?, ?, 'UPDATE_JADWAL_TIMELINE', ?, ?, ?, ?)
                        ", [$actId, $row['nama_kegiatan'], $actorName, $actorRole, $notes, $now]);
                    }
                } catch (\Throwable $e) {}
            }

            $allList  = $this->getRenlakActivitiesList($db);
            $logsList = $this->getRenlakAuditLogs($db);
            $this->saveRenlakFallbackStore($db, $allList, $logsList);

            return $this->respond([
                'status'     => 'success',
                'message'    => 'Jadwal timeline berhasil diperbarui.',
                'activities' => $allList,
                'audit_logs' => $logsList
            ]);
        }

        // =====================================================================
        // DELETE RENLAK ACTIVITY
        // =====================================================================
        if ($key === 'deleteRenlakActivity') {
            $actId = isset($json->id) ? (int)$json->id : 0;
            if ($actId <= 0) {
                return $this->failValidationErrors('ID Kegiatan RENLAK tidak valid.');
            }
            $session   = session();
            $actorName = $session->get('userName') ?: 'Admin Lab';
            $actorRole = $session->get('userRole') ?: 'Administrator';
            $now       = date('Y-m-d H:i:s');

            if ($this->ensureRenlakTables($db)) {
                try {
                    $row = $db->query("SELECT nama_kegiatan, kategori FROM renlak_activities WHERE id = ?", [$actId])->getRow();
                    $namaKeg = $row ? $row->nama_kegiatan : ('#' . $actId);
                    $db->query("DELETE FROM renlak_activities WHERE id = ?", [$actId]);
                    $db->query("
                        INSERT INTO renlak_audit_logs (renlak_id, nama_kegiatan, action, actor_name, actor_role, notes, created_at)
                        VALUES (?, ?, 'DELETE_KEGIATAN', ?, ?, ?, ?)
                    ", [$actId, $namaKeg, $actorName, $actorRole, "Menghapus kegiatan RENLAK '{$namaKeg}'", $now]);
                } catch (\Throwable $e) {}
            }

            $allList  = $this->getRenlakActivitiesList($db);
            $logsList = $this->getRenlakAuditLogs($db);
            $this->saveRenlakFallbackStore($db, $allList, $logsList);

            return $this->respond([
                'status'     => 'success',
                'message'    => 'Kegiatan RENLAK berhasil dihapus.',
                'activities' => $allList,
                'audit_logs' => $logsList
            ]);
        }

        // =====================================================================
        // RESET / RESTORE DEFAULT EXCEL RENLAK ACTIVITIES
        // =====================================================================
        if ($key === 'resetRenlakDefault') {
            $session   = session();
            $actorName = $session->get('userName') ?: 'Admin Lab';
            $actorRole = $session->get('userRole') ?: 'Administrator';
            $now       = date('Y-m-d H:i:s');

            if ($this->ensureRenlakTables($db)) {
                try {
                    $db->query("TRUNCATE TABLE renlak_activities");
                    $seeds = $this->getDefaultRenlakSeedData();
                    foreach ($seeds as $s) {
                        $db->query("
                            INSERT INTO renlak_activities
                            (nomor_urut, kategori, nama_kegiatan, rencana_kegiatan, realisasi, target, satuan, persentase_capaian, tahun_mulai, tahun_selesai, status_kegiatan, pic_penanggung_jawab, catatan_evaluasi, jadwal_rencana_json, jadwal_realisasi_json, created_by, updated_by, created_at, updated_at)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                        ", [
                            $s['nomor_urut'], $s['kategori'], $s['nama_kegiatan'], $s['rencana_kegiatan'],
                            $s['realisasi'], $s['target'], $s['satuan'], $s['persentase_capaian'],
                            $s['tahun_mulai'], $s['tahun_selesai'], $s['status_kegiatan'],
                            $s['pic_penanggung_jawab'], $s['catatan_evaluasi'],
                            json_encode($s['jadwal_rencana'], JSON_UNESCAPED_UNICODE),
                            json_encode($s['jadwal_realisasi'], JSON_UNESCAPED_UNICODE),
                            $actorName, $actorName, $now, $now
                        ]);
                    }
                    $db->query("
                        INSERT INTO renlak_audit_logs (renlak_id, nama_kegiatan, action, actor_name, actor_role, notes, created_at)
                        VALUES (0, 'Reset Data Referensi Excel', 'RESET_DEFAULT', ?, ?, 'Mengembalikan 11 kegiatan awal sesuai referensi Excel RENLAK 2026-2027', ?)
                    ", [$actorName, $actorRole, $now]);
                } catch (\Throwable $e) {}
            }

            $allList  = $this->getRenlakActivitiesList($db);
            $logsList = $this->getRenlakAuditLogs($db);
            $this->saveRenlakFallbackStore($db, $allList, $logsList);

            return $this->respond([
                'status'     => 'success',
                'message'    => 'Data kegiatan RENLAK dikembalikan ke referensi awal Excel.',
                'activities' => $allList,
                'audit_logs' => $logsList
            ]);
        }

        return $this->fail('Invalid key');
    }
}
