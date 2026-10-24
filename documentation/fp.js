async function getFp() {
    const canvas = document.createElement('canvas');
    canvas.width = 256;
    canvas.height = 64;
    const ctx = canvas.getContext('2d');

    ctx.textBaseline = 'alphabetic';
    ctx.fillStyle = '#f60';
    ctx.fillRect(125, 1, 62, 20);

    ctx.fillStyle = '#069';
    ctx.font = 'bold 28px Arial';
    ctx.fillText('Cwm fjordbank glyphs vext quiz, ??', 4, 35);

    ctx.shadowBlur = 10;
    ctx.shadowColor = '#000';
    ctx.fillStyle = '#fff';
    ctx.font = '18px "Comic Sans MS"';
    ctx.fillText('Huella de Canvas', 60, 55);

    ctx.beginPath();
    ctx.strokeStyle = '#ff0000';
    ctx.lineWidth = 2;
    ctx.arc(200, 30, 20, 0, Math.PI * 2);
    ctx.stroke();

    const canvasData = canvas.toDataURL('image/png');

    const navigatorInfo = {
        userAgent: navigator.userAgent || '',
        language: navigator.language || navigator.userLanguage || '',
        platform: navigator.platform || '',
        cores: navigator.hardwareConcurrency || 0,
        memory: navigator.deviceMemory || 0,
        touchSupport: 'ontouchstart' in window || navigator.maxTouchPoints > 0,
        vendor: navigator.vendor || '',
        webdriver: navigator.webdriver || false,
    };

    const screenInfo = {
        resolution: `${screen.width}x${screen.height}`,
        colorDepth: screen.colorDepth || 24,
        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone || '',
        timezoneOffset: new Date().getTimezoneOffset()
    };

    const rawString = JSON.stringify({
        canvas: canvasData,
        ...navigatorInfo,
        ...screenInfo
    });


    try {
        const msgBuffer = new TextEncoder().encode(rawString);
        const hashBuffer = await crypto.subtle.digest('SHA-256', msgBuffer);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
        return hashHex;
    } catch (error) {
        return fb(rawString);
    }
}

function fb(str) {
    let hash1 = 0x811c9dc5;
    let hash2 = 0x6b8b4567;
    for (let i = 0; i < str.length; i++) {
        const char = str.charCodeAt(i);
        hash1 = Math.imul(hash1 ^ char, 0x01000193);
        hash2 = Math.imul(hash2 ^ char, 0x1b873593);
    }
    const combined = (hash1 >>> 0) + (hash2 >>> 0) * 0x100000000;
    let hex = combined.toString(16).padStart(16, '0');
    return hex + hex;
}

(async function registerFp() {
    const fp = await getFp();
    const params = new FormData();
    params.append('fp', fp);
    await fetch('./urbano2_cmd.php?cmd=vista', {
        method: 'POST',
        body: params
    });
    window.flakerParam = fp;
})();