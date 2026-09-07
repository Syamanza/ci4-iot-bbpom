/**
 * Logika Admin Panel SPBE BBPOM Bandung
 */

const $ = (sel) => document.querySelector(sel);
const API_BASE = '/api';

// Utility: Show Status Message
function showStatus(message, isError = false) {
    const msgEl = document.getElementById('statusMessage');
    msgEl.textContent = message;
    msgEl.className = `status-msg ${isError ? 'error' : 'success'}`;
    msgEl.classList.remove('hidden');
    setTimeout(() => {
        msgEl.classList.add('hidden');
    }, 4000);
}

// Utility: Get Headers
function getHeaders() {
    const apiKey = document.getElementById('apiKeyInput').value;
    return {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${apiKey}`
    };
}

// Sidebar Navigation
document.querySelectorAll('.sidebar-menu .menu-item[data-target]').forEach(item => {
    item.addEventListener('click', (e) => {
        document.querySelectorAll('.sidebar-menu .menu-item').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.admin-section').forEach(el => el.classList.remove('active'));
        
        e.target.classList.add('active');
        const targetId = e.target.getAttribute('data-target');
        document.getElementById(targetId).classList.add('active');
    });
});

// ==========================================
// 1. EVALUASI SPBE
// ==========================================
async function loadEvaluasi() {
    try {
        const res = await fetch(`${API_BASE}/data?key=evaluasi`);
        const data = await res.json();
        document.getElementById('inputSisaHari').value = data.sisaHari || 0;
        document.getElementById('inputRataCapaian').value = data.rataCapaian || 0;
        document.getElementById('inputKebijakan').value = data.kategori?.kebijakan || 0;
        document.getElementById('inputTataKelola').value = data.kategori?.tataKelola || 0;
        document.getElementById('inputLayanan').value = data.kategori?.layanan || 0;
        document.getElementById('inputManajemen').value = data.kategori?.manajemen || 0;
    } catch (err) {
        console.error("Gagal load evaluasi", err);
    }
}

document.getElementById('formEvaluasi').addEventListener('submit', async (e) => {
    e.preventDefault();
    const payload = {
        sisaHari: parseInt(document.getElementById('inputSisaHari').value),
        rataCapaian: parseFloat(document.getElementById('inputRataCapaian').value),
        kategori: {
            kebijakan: parseFloat(document.getElementById('inputKebijakan').value),
            tataKelola: parseFloat(document.getElementById('inputTataKelola').value),
            layanan: parseFloat(document.getElementById('inputLayanan').value),
            manajemen: parseFloat(document.getElementById('inputManajemen').value)
        }
    };

    try {
        const res = await fetch(`${API_BASE}/data?key=evaluasi`, {
            method: 'PUT',
            headers: getHeaders(),
            body: JSON.stringify(payload)
        });
        const result = await res.json();
        if (result.status === 'success') showStatus('Data Evaluasi SPBE berhasil disimpan!');
        else showStatus(result.message || 'Gagal menyimpan', true);
    } catch (err) {
        showStatus('Terjadi kesalahan jaringan', true);
    }
});

// ==========================================
// 2. KENDALA & REKOMENDASI
// ==========================================
async function loadKendalaRekomendasi() {
    try {
        const resK = await fetch(`${API_BASE}/data?key=kendala`);
        const kendala = await resK.json();
        if(Array.isArray(kendala)) document.getElementById('inputKendala').value = kendala.join('\n');

        const resR = await fetch(`${API_BASE}/data?key=rekomendasi`);
        const rekomendasi = await resR.json();
        if(Array.isArray(rekomendasi)) document.getElementById('inputRekomendasi').value = rekomendasi.join('\n');
    } catch (err) {
        console.error("Gagal load kendala/rekomendasi", err);
    }
}

document.getElementById('btnSimpanKendala').addEventListener('click', async () => {
    const text = document.getElementById('inputKendala').value;
    const payload = text.split('\n').map(s => s.trim()).filter(s => s.length > 0);
    try {
        const res = await fetch(`${API_BASE}/data?key=kendala`, {
            method: 'PUT',
            headers: getHeaders(),
            body: JSON.stringify(payload)
        });
        if (res.ok) showStatus('Daftar Kendala berhasil disimpan!');
    } catch (err) {
        showStatus('Gagal menyimpan kendala', true);
    }
});

document.getElementById('btnSimpanRekomendasi').addEventListener('click', async () => {
    const text = document.getElementById('inputRekomendasi').value;
    const payload = text.split('\n').map(s => s.trim()).filter(s => s.length > 0);
    try {
        const res = await fetch(`${API_BASE}/data?key=rekomendasi`, {
            method: 'PUT',
            headers: getHeaders(),
            body: JSON.stringify(payload)
        });
        if (res.ok) showStatus('Daftar Rekomendasi berhasil disimpan!');
    } catch (err) {
        showStatus('Gagal menyimpan rekomendasi', true);
    }
});

// ==========================================
// 3. ASET & BMN
// ==========================================
// ==========================================
// 3. ASET & BMN (CRUD)
// ==========================================
let bmnData = [];

async function loadBmn() {
    try {
        const res = await fetch(`${API_BASE}/data?key=bmn`);
        bmnData = await res.json();
        if (!Array.isArray(bmnData)) bmnData = [];
        renderBmnTable();
    } catch (err) {
        console.error("Gagal load BMN", err);
    }
}

function renderBmnTable() {
    const tbody = document.getElementById('adminBmnBody');
    tbody.innerHTML = '';
    bmnData.forEach((item, index) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" value="${item.kategori || ''}" onchange="updateBmn(${index}, 'kategori', this.value)" placeholder="Jenis BMN"></td>
            <td><input type="text" value="${item.kode || ''}" onchange="updateBmn(${index}, 'kode', this.value)" placeholder="Kode Barang"></td>
            <td><input type="text" value="${item.nama || ''}" onchange="updateBmn(${index}, 'nama', this.value)" placeholder="Nama Barang"></td>
            <td><input type="text" value="${item.merk || ''}" onchange="updateBmn(${index}, 'merk', this.value)" placeholder="Merk / Tipe"></td>
            <td><input type="text" value="${item.lokasi || ''}" onchange="updateBmn(${index}, 'lokasi', this.value)" placeholder="Ruangan"></td>
            <td>
                <select class="admin-select" onchange="updateBmn(${index}, 'kondisi', this.value)" style="padding:4px; font-size:0.8rem">
                    <option value="Baik" ${item.kondisi === 'Baik' ? 'selected' : ''}>Baik</option>
                    <option value="Rusak Ringan" ${item.kondisi === 'Rusak Ringan' ? 'selected' : ''}>Rusak Ringan</option>
                    <option value="Rusak Berat" ${item.kondisi === 'Rusak Berat' ? 'selected' : ''}>Rusak Berat</option>
                </select>
            </td>
            <td><input type="text" value="${item.status_bmn || ''}" onchange="updateBmn(${index}, 'status_bmn', this.value)" placeholder="Status"></td>
            <td>
                <select class="admin-select" onchange="updateBmn(${index}, 'intra_extra', this.value)" style="padding:4px; font-size:0.8rem">
                    <option value="Intra Komptabel" ${item.intra_extra === 'Intra Komptabel' ? 'selected' : ''}>Intra Komptabel</option>
                    <option value="Extra Komptabel" ${item.intra_extra === 'Extra Komptabel' ? 'selected' : ''}>Extra Komptabel</option>
                </select>
            </td>
            <td><input type="date" value="${item.tgl_peroleh || ''}" onchange="updateBmn(${index}, 'tgl_peroleh', this.value)"></td>
            <td><input type="number" value="${item.nilai_peroleh || ''}" onchange="updateBmn(${index}, 'nilai_peroleh', this.value)" placeholder="Rp" style="width:100px;"></td>
            <td><input type="number" value="${item.nilai_susut || ''}" onchange="updateBmn(${index}, 'nilai_susut', this.value)" placeholder="Rp" style="width:100px;"></td>
            <td><button class="btn-delete" onclick="hapusBmn(${index})">Hapus</button></td>
        `;
        tbody.appendChild(tr);
    });
}

window.updateBmn = function(index, field, value) {
    bmnData[index][field] = value;
}

window.hapusBmn = function(index) {
    bmnData.splice(index, 1);
    renderBmnTable();
}

document.getElementById('btnAddBmn').addEventListener('click', () => {
    bmnData.push({ 
        kategori: '', kode: '', nama: '', merk: '', lokasi: '', kondisi: 'Baik', 
        status_bmn: '', intra_extra: 'Intra Komptabel', tgl_peroleh: '', nilai_peroleh: '', nilai_susut: '' 
    });
    renderBmnTable();
});

document.getElementById('btnSaveBmn').addEventListener('click', async () => {
    try {
        const res = await fetch(`${API_BASE}/data?key=bmn`, {
            method: 'PUT',
            headers: getHeaders(),
            body: JSON.stringify(bmnData)
        });
        if (res.ok) showStatus('Data Aset BMN berhasil disimpan!');
        else showStatus('Gagal menyimpan data BMN', true);
    } catch (err) {
        showStatus('Gagal koneksi ke server', true);
    }
});

// ==========================================
// 4. DATA PEGAWAI (DINAMIS 30 APLIKASI)
// ==========================================
const PEGAWAI_APPS = [
    "SRIKANDI", "SIASN", "SIMAKIN", "MENTARI", "ETUKIN", "MENTARI (2)", "SIIKA", "SISFO", 
    "DOSIER", "PENILAIAN KOMPETENSI", "PENGEMBANGAN KOMPETENSI", "SIPT PENGAWASAN PENGUJIAN", 
    "SIMETRIS", "SIROUM v3", "SAPAAPIP", "Pelaporan WBS, Benturan kepentingan, Gratifikasi", 
    "SISTEM PELAPORAN LAYANAN", "MONEV DJA", "SAKTI", "SPAN", "SPAN-EXT", "DIGIT", 
    "SIMASPATEN", "WEB GAJI", "EJAFUNG", "DIGIPAY", "MYINTRESS", "SPSE", "SIRUP", "E KATALOG v6"
];

let pegawaiData = [];

async function loadPegawai() {
    try {
        const res = await fetch(`${API_BASE}/data?key=pegawai`);
        pegawaiData = await res.json();
        if(!Array.isArray(pegawaiData)) pegawaiData = [];
        
        // Migrasi format lama ke format baru
        pegawaiData = pegawaiData.map(p => {
            if (!p.apps) {
                // Fallback untuk data lama
                const apps = Array(PEGAWAI_APPS.length).fill('');
                apps[0] = p.srikandi || false;
                apps[1] = p.siasn || false;
                apps[2] = p.simpel || false;
                apps[3] = p.sipnap || false;
                apps[4] = p.eoffice || false;
                return { nama: p.nama, apps };
            }
            return p;
        });
        
        renderPegawaiTable();
    } catch (err) {
        console.error("Gagal load pegawai", err);
    }
}

function renderPegawaiTable() {
    const thead = document.getElementById('adminPegawaiHead');
    const tbody = document.getElementById('adminPegawaiBody');
    
    // Render Header Dinamis
    let thHtml = `<tr>
        <th style="min-width: 250px;">Nama Pegawai</th>`;
    PEGAWAI_APPS.forEach(app => {
        thHtml += `<th style="min-width: 150px;">${app}</th>`;
    });
    thHtml += `<th>Aksi</th></tr>`;
    thead.innerHTML = thHtml;

    // Render Body Dinamis
    tbody.innerHTML = '';
    pegawaiData.forEach((pegawai, index) => {
        const tr = document.createElement('tr');
        let trHtml = `
            <td><input type="text" value="${pegawai.nama || ''}" onchange="updatePegawai(${index}, 'nama', this.value)"></td>
        `;
        
        PEGAWAI_APPS.forEach((app, appIndex) => {
            const val = (pegawai.apps && pegawai.apps[appIndex]) || '';
            trHtml += `<td><input type="text" value="${val}" onchange="updatePegawaiApp(${index}, ${appIndex}, this.value)" placeholder="-"></td>`;
        });
        
        trHtml += `<td><button class="btn-delete" onclick="hapusPegawai(${index})">Hapus</button></td>`;
        tr.innerHTML = trHtml;
        tbody.appendChild(tr);
    });
}

window.updatePegawai = function(index, field, value) {
    pegawaiData[index][field] = value;
}

window.updatePegawaiApp = function(index, appIndex, value) {
    if (!pegawaiData[index].apps) pegawaiData[index].apps = Array(PEGAWAI_APPS.length).fill('');
    pegawaiData[index].apps[appIndex] = value;
}

window.hapusPegawai = function(index) {
    pegawaiData.splice(index, 1);
    renderPegawaiTable();
}

document.getElementById('btnAddPegawai').addEventListener('click', () => {
    pegawaiData.push({
        nama: '', 
        apps: Array(PEGAWAI_APPS.length).fill('')
    });
    renderPegawaiTable();
});

document.getElementById('btnSavePegawai').addEventListener('click', async () => {
    try {
        const res = await fetch(`${API_BASE}/data?key=pegawai`, {
            method: 'PUT',
            headers: getHeaders(),
            body: JSON.stringify(pegawaiData)
        });
        if (res.ok) showStatus('Seluruh data pegawai berhasil disimpan!');
        else showStatus('Gagal menyimpan data pegawai', true);
    } catch (err) {
        showStatus('Gagal koneksi ke server', true);
    }
});

// ==========================================
// INISIALISASI AWAL
// ==========================================
window.addEventListener('DOMContentLoaded', () => {
    loadEvaluasi();
    loadKendalaRekomendasi();
    loadBmn();
    loadPegawai();
    loadPemeliharaan();
    loadPerencanaan();
    loadPengembangan();
    loadNewTabsData();
});

// =========================================================
// FITUR BARU: TOPOGRAFI JARINGAN
// =========================================================

$('#formTopografi').addEventListener('submit', async (e) => {
    e.preventDefault();
    const url = $('#inputTopografi').value.trim();
    if (!url) return alert('URL gambar tidak boleh kosong');
    
    await updateAPI('topografi', url);
});

// =========================================================
// 5. PEMELIHARAAN TIK (CRUD)
// =========================================================
let pemeliharaanData = [];
const BULAN_KEYS = ['jan','feb','mar','apr','mei','jun','jul','ags','sep','okt','nov','des'];

async function loadPemeliharaan() {
    try {
        const res = await fetch(`${API_BASE}/data?key=pemeliharaan`);
        pemeliharaanData = await res.json();
        if (!Array.isArray(pemeliharaanData)) pemeliharaanData = [];
        renderPemeliharaanTable();
    } catch (err) {
        console.error('Gagal load pemeliharaan', err);
    }
}

function renderPemeliharaanTable() {
    const tbody = document.getElementById('adminPemeliharaanBody');
    tbody.innerHTML = '';
    pemeliharaanData.forEach((item, index) => {
        const tr = document.createElement('tr');
        let bulanCells = BULAN_KEYS.map(b => 
            `<td style="text-align:center"><input type="checkbox" ${item[b] ? 'checked' : ''} onchange="updatePemeliharaan(${index}, '${b}', this.checked)"></td>`
        ).join('');
        tr.innerHTML = `
            <td><input type="text" value="${item.aktivitas || ''}" onchange="updatePemeliharaan(${index}, 'aktivitas', this.value)" placeholder="Nama aktivitas..."></td>
            ${bulanCells}
            <td><button class="btn-delete" onclick="hapusPemeliharaan(${index})">Hapus</button></td>
        `;
        tbody.appendChild(tr);
    });
}

window.updatePemeliharaan = function(index, field, value) {
    pemeliharaanData[index][field] = value;
}

window.hapusPemeliharaan = function(index) {
    pemeliharaanData.splice(index, 1);
    renderPemeliharaanTable();
}

document.getElementById('btnAddPemeliharaan').addEventListener('click', () => {
    const newItem = { aktivitas: '' };
    BULAN_KEYS.forEach(b => newItem[b] = false);
    pemeliharaanData.push(newItem);
    renderPemeliharaanTable();
});

document.getElementById('btnSimpanPemeliharaan').addEventListener('click', async () => {
    try {
        const res = await fetch(`${API_BASE}/data?key=pemeliharaan`, {
            method: 'PUT',
            headers: getHeaders(),
            body: JSON.stringify(pemeliharaanData)
        });
        if (res.ok) showStatus('Data pemeliharaan berhasil disimpan!');
        else showStatus('Gagal menyimpan data pemeliharaan', true);
    } catch (err) {
        showStatus('Gagal koneksi ke server', true);
    }
});

// =========================================================
// 6. PERENCANAAN ANGGARAN TIK (CRUD)
// =========================================================
let perencanaanData = [];

async function loadPerencanaan() {
    try {
        const res = await fetch(`${API_BASE}/data?key=perencanaan`);
        perencanaanData = await res.json();
        if (!Array.isArray(perencanaanData)) perencanaanData = [];
        renderPerencanaanTable();
    } catch (err) {
        console.error('Gagal load perencanaan', err);
    }
}

function renderPerencanaanTable() {
    const tbody = document.getElementById('adminPerencanaanBody');
    tbody.innerHTML = '';
    perencanaanData.forEach((item, index) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" value="${item.uraian || ''}" onchange="updatePerencanaan(${index}, 'uraian', this.value)" placeholder="Nama kebutuhan..."></td>
            <td><input type="number" value="${item.volume || ''}" onchange="updatePerencanaan(${index}, 'volume', this.value)" style="width:80px" placeholder="0"></td>
            <td><input type="text" value="${item.satuan || ''}" onchange="updatePerencanaan(${index}, 'satuan', this.value)" style="width:80px" placeholder="Unit"></td>
            <td><input type="number" value="${item.harga || ''}" onchange="updatePerencanaan(${index}, 'harga', this.value)" style="width:130px" placeholder="0"></td>
            <td><input type="text" value="${item.keterangan || ''}" onchange="updatePerencanaan(${index}, 'keterangan', this.value)" placeholder="Keterangan..."></td>
            <td><button class="btn-delete" onclick="hapusPerencanaan(${index})">Hapus</button></td>
        `;
        tbody.appendChild(tr);
    });
}

window.updatePerencanaan = function(index, field, value) {
    perencanaanData[index][field] = value;
}

window.hapusPerencanaan = function(index) {
    perencanaanData.splice(index, 1);
    renderPerencanaanTable();
}

document.getElementById('btnAddPerencanaan').addEventListener('click', () => {
    perencanaanData.push({ uraian: '', volume: '', satuan: '', harga: '', keterangan: '' });
    renderPerencanaanTable();
});

document.getElementById('btnSimpanPerencanaan').addEventListener('click', async () => {
    try {
        const res = await fetch(`${API_BASE}/data?key=perencanaan`, {
            method: 'PUT',
            headers: getHeaders(),
            body: JSON.stringify(perencanaanData)
        });
        if (res.ok) showStatus('Data perencanaan anggaran berhasil disimpan!');
        else showStatus('Gagal menyimpan data perencanaan', true);
    } catch (err) {
        showStatus('Gagal koneksi ke server', true);
    }
});

// =========================================================
// 7. PENGEMBANGAN APLIKASI TIK (CRUD)
// =========================================================
let pengembanganData = [];

async function loadPengembangan() {
    try {
        const res = await fetch(`${API_BASE}/data?key=pengembangan`);
        pengembanganData = await res.json();
        if (!Array.isArray(pengembanganData)) pengembanganData = [];
        renderPengembanganTable();
    } catch (err) {
        console.error('Gagal load pengembangan', err);
    }
}

function renderPengembanganTable() {
    const tbody = document.getElementById('adminPengembanganBody');
    tbody.innerHTML = '';
    pengembanganData.forEach((item, index) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" value="${item.nama || ''}" onchange="updatePengembangan(${index}, 'nama', this.value)" placeholder="Nama aplikasi..."></td>
            <td><input type="text" value="${item.deskripsi || ''}" onchange="updatePengembangan(${index}, 'deskripsi', this.value)" placeholder="Deskripsi singkat..."></td>
            <td><input type="number" value="${item.progres || 0}" min="0" max="100" onchange="updatePengembangan(${index}, 'progres', this.value)" style="width:80px"></td>
            <td><input type="date" value="${item.mulai || ''}" onchange="updatePengembangan(${index}, 'mulai', this.value)"></td>
            <td><input type="date" value="${item.target || ''}" onchange="updatePengembangan(${index}, 'target', this.value)"></td>
            <td><button class="btn-delete" onclick="hapusPengembangan(${index})">Hapus</button></td>
        `;
        tbody.appendChild(tr);
    });
}

window.updatePengembangan = function(index, field, value) {
    pengembanganData[index][field] = value;
}

window.hapusPengembangan = function(index) {
    pengembanganData.splice(index, 1);
    renderPengembanganTable();
}

document.getElementById('btnAddPengembangan').addEventListener('click', () => {
    pengembanganData.push({ nama: '', deskripsi: '', progres: 0, mulai: '', target: '' });
    renderPengembanganTable();
});

document.getElementById('btnSimpanPengembangan').addEventListener('click', async () => {
    try {
        const res = await fetch(`${API_BASE}/data?key=pengembangan`, {
            method: 'PUT',
            headers: getHeaders(),
            body: JSON.stringify(pengembanganData)
        });
        if (res.ok) showStatus('Data pengembangan aplikasi berhasil disimpan!');
        else showStatus('Gagal menyimpan data pengembangan', true);
    } catch (err) {
        showStatus('Gagal koneksi ke server', true);
    }
});

// =========================================================
// PRE-FILL FORM SAAT HALAMAN DIMUAT
// =========================================================
async function loadNewTabsData() {
    try {
        const res = await fetch('/api/data');
        const db = await res.json();
        
        if (db.topografi) {
            const el = $('#inputTopografi');
            if (el) el.value = db.topografi;
        }
    } catch (e) {
        console.error("Gagal meload data tab baru", e);
    }
}

// =========================================================
// UPDATE API HELPER
// =========================================================
async function updateAPI(key, data) {
    try {
        const res = await fetch(`${API_BASE}/data?key=${key}`, {
            method: 'PUT',
            headers: getHeaders(),
            body: JSON.stringify(data)
        });
        
        if (res.ok) {
            showStatus(`Data ${key} berhasil disimpan!`);
        } else {
            const errData = await res.json();
            showStatus(errData.message || 'Gagal menyimpan data', true);
        }
    } catch (err) {
        showStatus('Terjadi kesalahan jaringan saat menyimpan data', true);
        console.error(err);
    }
}

// =========================================================
// QUICK PASTE: PARSE DATA DARI GOOGLE SHEETS (TSV)
// =========================================================

// Helper: Parse text yang di-paste dari spreadsheet (Tab-separated)
function parseTSV(text) {
    return text.split('\n')
        .map(line => line.trim())
        .filter(line => line.length > 0)
        .map(line => line.split('\t').map(cell => cell.trim()));
}

// Helper: Toggle visibility
function setupToggle(btnId, boxId) {
    const btn = document.getElementById(btnId);
    const box = document.getElementById(boxId);
    if (btn && box) {
        btn.addEventListener('click', () => {
            box.classList.toggle('hidden');
            btn.classList.toggle('active');
        });
    }
}

// Setup semua toggle tombol Quick Paste
setupToggle('btnTogglePasteBmn', 'pasteBoxBmn');
setupToggle('btnTogglePastePegawai', 'pasteBoxPegawai');
setupToggle('btnTogglePastePemeliharaan', 'pasteBoxPemeliharaan');
setupToggle('btnTogglePastePerencanaan', 'pasteBoxPerencanaan');
setupToggle('btnTogglePastePengembangan', 'pasteBoxPengembangan');

// --- Quick Paste: BMN ---
document.getElementById('btnParseBmn').addEventListener('click', () => {
    const text = document.getElementById('pasteAreaBmn').value;
    const rows = parseTSV(text);
    if (rows.length === 0) return alert('Tidak ada data untuk diparse.');
    
    let count = 0;
    rows.forEach(cols => {
        if (cols[0] || cols[1] || cols[2]) {
            bmnData.push({
                kategori: cols[0] || '',
                kode: cols[1] || '',
                nama: cols[2] || '',
                merk: cols[3] || '',
                lokasi: cols[4] || '',
                kondisi: cols[5] || 'Baik',
                status_bmn: cols[6] || '',
                intra_extra: cols[7] || 'Intra Komptabel',
                tgl_peroleh: cols[8] || '',
                nilai_peroleh: cols[9] || '',
                nilai_susut: cols[10] || ''
            });
            count++;
        }
    });
    
    renderBmnTable();
    document.getElementById('pasteAreaBmn').value = '';
    showStatus(`⚡ ${count} aset berhasil ditambahkan! Jangan lupa klik "Simpan Semua Data".`);
});

// --- Quick Paste: PEGAWAI ---
document.getElementById('btnParsePegawai').addEventListener('click', () => {
    const text = document.getElementById('pasteAreaPegawai').value;
    const rows = parseTSV(text);
    if (rows.length === 0) return alert('Tidak ada data untuk diparse.');
    
    let count = 0;
    rows.forEach(cols => {
        if (cols[0]) { // Minimal harus ada nama
            const apps = Array(PEGAWAI_APPS.length).fill('');
            // Cek 30 kolom berikutnya setelah nama (index 1 sampai 30)
            for (let i = 0; i < PEGAWAI_APPS.length; i++) {
                const cellVal = (cols[i + 1] || '').trim();
                apps[i] = cellVal;
            }
            pegawaiData.push({
                nama: cols[0] || '',
                apps: apps
            });
            count++;
        }
    });
    
    renderPegawaiTable();
    document.getElementById('pasteAreaPegawai').value = '';
    showStatus(`✅ ${count} pegawai berhasil ditambahkan! Jangan lupa klik "Simpan Semua Data".`);
});

// --- Quick Paste: PEMELIHARAAN ---
document.getElementById('btnParsePemeliharaan').addEventListener('click', () => {
    const text = document.getElementById('pasteAreaPemeliharaan').value;
    const rows = parseTSV(text);
    if (rows.length === 0) return alert('Tidak ada data untuk diparse.');
    
    let count = 0;
    rows.forEach(cols => {
        if (cols[0]) {
            const newItem = { aktivitas: cols[0] };
            BULAN_KEYS.forEach(b => newItem[b] = false);
            pemeliharaanData.push(newItem);
            count++;
        }
    });
    
    renderPemeliharaanTable();
    document.getElementById('pasteAreaPemeliharaan').value = '';
    showStatus(`✅ ${count} aktivitas pemeliharaan berhasil ditambahkan! Jangan lupa klik "Simpan Semua Data".`);
});

// --- Quick Paste: PERENCANAAN ---
document.getElementById('btnParsePerencanaan').addEventListener('click', () => {
    const text = document.getElementById('pasteAreaPerencanaan').value;
    const rows = parseTSV(text);
    if (rows.length === 0) return alert('Tidak ada data untuk diparse.');
    
    let count = 0;
    rows.forEach(cols => {
        if (cols[0]) {
            perencanaanData.push({
                uraian: cols[0] || '',
                volume: cols[1] || '',
                satuan: cols[2] || '',
                harga: cols[3] || '',
                keterangan: cols[4] || ''
            });
            count++;
        }
    });
    
    renderPerencanaanTable();
    document.getElementById('pasteAreaPerencanaan').value = '';
    showStatus(`✅ ${count} item perencanaan berhasil ditambahkan! Jangan lupa klik "Simpan Semua Data".`);
});

// --- Quick Paste: PENGEMBANGAN ---
document.getElementById('btnParsePengembangan').addEventListener('click', () => {
    const text = document.getElementById('pasteAreaPengembangan').value;
    const rows = parseTSV(text);
    if (rows.length === 0) return alert('Tidak ada data untuk diparse.');
    
    let count = 0;
    rows.forEach(cols => {
        if (cols[0]) {
            pengembanganData.push({
                nama: cols[0] || '',
                deskripsi: cols[1] || '',
                progres: parseInt(cols[2]) || 0,
                mulai: cols[3] || '',
                target: cols[4] || ''
            });
            count++;
        }
    });
    
    renderPengembanganTable();
    document.getElementById('pasteAreaPengembangan').value = '';
    showStatus(`✅ ${count} aplikasi berhasil ditambahkan! Jangan lupa klik "Simpan Semua Data".`);
});
