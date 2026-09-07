const { makeWASocket, useMultiFileAuthState, fetchLatestBaileysVersion, Browsers } = require('@whiskeysockets/baileys');
const pino = require('pino');
const express = require('express');
const QRCode = require('qrcode');
const fs = require('fs');
const path = require('path');

// ============ STATE ============
let sock = null;
let latestQR = null;     // string QR mentah dari Baileys
let connected = false;

// ============ EXPRESS ============
const app = express();
app.use(express.json());

// Halaman scan QR di browser — statis, TIDAK pernah reload sendiri.
// Halaman ini menggunakan polling AJAX ringan untuk mengecek status.
app.get('/qr', async (req, res) => {
    res.send(QR_PAGE_HTML);
});

// API JSON untuk polling dari halaman /qr
app.get('/api/status', async (req, res) => {
    res.set('Cache-Control', 'no-store');
    if (connected) {
        return res.json({ state: 'connected' });
    }
    if (!latestQR) {
        return res.json({ state: 'loading' });
    }
    // Convert QR string ke base64 PNG
    const dataUrl = await QRCode.toDataURL(latestQR, { width: 280, margin: 2 });
    return res.json({ state: 'qr', img: dataUrl });
});

// Endpoint kirim pesan dari CI4
app.post('/send-message', async (req, res) => {
    const { number, message } = req.body || {};
    if (!number || !message) return res.status(400).json({ ok: false, err: 'number & message wajib diisi' });
    if (!sock || !connected)  return res.status(503).json({ ok: false, err: 'Bot belum terhubung ke WhatsApp' });

    let jid = number.replace(/\D/g, '');
    if (jid.startsWith('0')) jid = '62' + jid.slice(1);
    jid += '@s.whatsapp.net';

    try {
        await sock.sendMessage(jid, { text: message });
        console.log(`[OK] Pesan terkirim ke ${number}`);
        return res.json({ ok: true });
    } catch (e) {
        console.error('[ERR] Gagal kirim:', e.message);
        return res.status(500).json({ ok: false, err: e.message });
    }
});

// ============ WHATSAPP ============
async function startBot() {
    const authDir = path.join(__dirname, 'auth');
    const { state, saveCreds } = await useMultiFileAuthState(authDir);
    const { version } = await fetchLatestBaileysVersion();

    sock = makeWASocket({
        version,
        auth: state,
        printQRInTerminal: false,
        logger: pino({ level: 'silent' }),
        browser: Browsers.macOS('Desktop'),
    });

    sock.ev.on('creds.update', saveCreds);

    sock.ev.on('connection.update', (update) => {
        const { connection, lastDisconnect, qr } = update;

        if (qr) {
            latestQR = qr;
            // Juga cetak di terminal supaya ada 2 opsi
            console.log('\n--- QR baru tersedia ---');
            console.log('Scan di browser: http://localhost:3000/qr\n');
        }

        if (connection === 'open') {
            connected = true;
            latestQR = null;
            console.log('==========================================');
            console.log('  ✅  WhatsApp terhubung! Bot siap pakai.');
            console.log('==========================================\n');
        }

        if (connection === 'close') {
            connected = false;
            const code = lastDisconnect?.error?.output?.statusCode;
            if (code === 401) {
                console.log('[!] Sesi di-logout dari HP. Menghapus sesi lama...');
                latestQR = null;
                try { fs.rmSync(authDir, { recursive: true, force: true }); } catch (_) {}
            }
            console.log('[*] Menyambung ulang dalam 3 detik...');
            setTimeout(startBot, 3000);
        }
    });
}

// ============ START ============
app.listen(3000, () => {
    console.log('');
    console.log('╔═══════════════════════════════════════════════╗');
    console.log('║   🤖  Bot WhatsApp SPBE BBPOM                ║');
    console.log('║                                               ║');
    console.log('║   Buka Google Chrome lalu ketik:               ║');
    console.log('║   👉  http://localhost:3000/qr                ║');
    console.log('║                                               ║');
    console.log('║   Scan QR Code yang muncul di halaman tsb.    ║');
    console.log('╚═══════════════════════════════════════════════╝');
    console.log('');
    startBot();
});

// ============ HALAMAN HTML (inline) ============
const QR_PAGE_HTML = `<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>WhatsApp Bot – SPBE BBPOM</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; display: flex;
         align-items: center; justify-content: center; height: 100vh; }
  .card { background: #fff; border-radius: 16px; padding: 48px 40px;
          box-shadow: 0 8px 24px rgba(0,0,0,.08); text-align: center; max-width: 400px; }
  h2 { margin-bottom: 8px; }
  .sub { color: #888; margin-bottom: 24px; font-size: .9em; }
  #qr { margin: 0 auto; }
  #msg { margin-top: 16px; font-size: 1.05em; }
  .ok  { color: #22c55e; font-weight: 700; }
  .wait { color: #888; }
</style>
</head>
<body>
<div class="card">
  <h2>Bot WhatsApp SPBE</h2>
  <p class="sub">Scan QR di bawah ini dari WhatsApp HP Anda</p>
  <div id="qr"></div>
  <div id="msg" class="wait">Memuat...</div>
</div>
<script>
let prevImg = '';
async function poll() {
  try {
    const r = await fetch('/api/status?_=' + Date.now());
    const d = await r.json();
    const qrEl  = document.getElementById('qr');
    const msgEl = document.getElementById('msg');
    if (d.state === 'connected') {
      qrEl.innerHTML = '';
      msgEl.className = 'ok';
      msgEl.textContent = '✅ WhatsApp sudah terhubung! Jendela ini boleh ditutup.';
      return; // berhenti polling
    }
    if (d.state === 'qr' && d.img !== prevImg) {
      prevImg = d.img;
      qrEl.innerHTML = '<img src="' + d.img + '" width="280" height="280">';
      msgEl.className = '';
      msgEl.textContent = 'Arahkan kamera WhatsApp ke QR ini';
    }
    if (d.state === 'loading') {
      msgEl.className = 'wait';
      msgEl.textContent = 'Menunggu QR dari server...';
    }
  } catch(_) {}
  setTimeout(poll, 3000);
}
poll();
</script>
</body>
</html>`;
