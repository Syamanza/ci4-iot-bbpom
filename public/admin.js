
const API_BASE = '/ci4-iot/public/api';

document.addEventListener('DOMContentLoaded', () => {
    loadIoTSettings();

    // Form Submit
    document.getElementById('formIoT').addEventListener('submit', async (e) => {
        e.preventDefault();
        const data = {
            waNumber: document.getElementById('iotWaNumber').value,
            maxTemp: parseFloat(document.getElementById('iotMaxTemp').value) || 25,
            minTemp: parseFloat(document.getElementById('iotMinTemp').value) || 15
        };

        try {
            const res = await fetch(`${API_BASE}/data?key=iot_settings`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await res.json();
            if (result.status === 'success') {
                showToast('Pengaturan IoT berhasil disimpan', 'success');
            } else {
                showToast('Gagal menyimpan pengaturan IoT', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Terjadi kesalahan jaringan', 'error');
        }
    });
});

async function loadIoTSettings() {
    try {
        const res = await fetch(`${API_BASE}/data`);
        const result = await res.json();
        if (result.iot_settings) {
            document.getElementById('iotWaNumber').value = result.iot_settings.waNumber || '';
            document.getElementById('iotMaxTemp').value = result.iot_settings.maxTemp || 25;
            document.getElementById('iotMinTemp').value = result.iot_settings.minTemp || 15;
        }
    } catch (err) {
        console.error('Failed to load IoT Settings', err);
    }
}

function showToast(message, type = 'success') {
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.style.cssText = 'position: fixed; bottom: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 12px; pointer-events: none;';
        document.body.appendChild(toastContainer);
    }
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.style.cssText = `
        background: ${type === 'success' ? '#2E7D32' : '#C62828'};
        color: white; padding: 12px 24px; border-radius: 8px; font-weight: 500;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15); animation: slideIn 0.3s ease forwards;
        display: flex; align-items: center; gap: 8px;
    `;
    
    toast.innerHTML = `
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            ${type === 'success' ? '<polyline points="20 6 9 17 4 12"></polyline>' : '<circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>'}
        </svg>
        <span>${message}</span>
    `;
    
    toastContainer.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease forwards';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
