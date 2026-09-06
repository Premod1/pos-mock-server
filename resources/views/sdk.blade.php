<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrintBridge SDK Test & Diagnostics</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
            padding: 2rem;
            margin: 0;
            display: flex;
            justify-content: center;
        }
        .container {
            max-width: 680px;
            width: 100%;
            background: #1e293b;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.4);
            border: 1px solid #334155;
        }
        h2 { margin-top: 0; color: #38bdf8; }
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; font-size: 0.875rem; margin-bottom: 0.4rem; color: #94a3b8; }
        input, select {
            width: 100%;
            padding: 0.65rem;
            background: #0f172a;
            border: 1px solid #475569;
            color: #f8fafc;
            border-radius: 6px;
            font-size: 0.95rem;
        }
        .btn-row { display: flex; gap: 0.75rem; margin-top: 1.5rem; }
        button {
            flex: 1;
            padding: 0.75rem;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        button:disabled { opacity: 0.5; cursor: not-allowed; }
        .btn-fetch { background: #0284c7; color: white; }
        .btn-print { background: #16a34a; color: white; }
        .btn-clear { background: #475569; color: white; flex: 0.35; }
        .log-box {
            margin-top: 1.5rem;
            background: #090d16;
            border: 1px solid #334155;
            padding: 1rem;
            border-radius: 6px;
            font-family: monospace;
            font-size: 0.8rem;
            height: 250px;
            overflow-y: auto;
            white-space: pre-wrap;
            color: #a5f3fc;
            line-height: 1.4;
        }
    </style>

    <!-- PrintBridge Official JS SDK -->
    <script src="https://printbridge.online/sdk/v1/printbridge.js"></script>
</head>
<body>

<div class="container">
    <h2>PrintBridge Async Diagnostic Tester</h2>

    <div class="form-group">
        <label for="deviceId">Device UUID</label>
        <input type="text" id="deviceId" placeholder="Enter hardware device UUID..." value="245f4431-da6d-4bf9-97fd-e00e012d027d">
    </div>

    <div class="form-group">
        <label for="printerSelect">Connected Printers</label>
        <select id="printerSelect">
            <option value="">-- Fetch printers to select --</option>
        </select>
    </div>

    <div class="form-group">
        <label for="paperWidth">Paper Width</label>
        <select id="paperWidth">
            <option value="80mm">80mm</option>
            <option value="58mm">58mm</option>
        </select>
    </div>

    <div class="btn-row">
        <button type="button" class="btn-fetch" id="btnFetch" onclick="loadPrinters()">Fetch Printers</button>
        <button type="button" class="btn-print" id="btnPrint" onclick="executeTestPrint()" disabled>Test Print</button>
        <button type="button" class="btn-clear" onclick="clearLog()">Clear</button>
    </div>

    <div class="log-box" id="consoleLog">> Initializing diagnostics...</div>
</div>

<script>
    const logEl = document.getElementById('consoleLog');

    function writeLog(tag, msg, obj = null) {
        const timestamp = new Date().toLocaleTimeString();
        let text = `[${timestamp}] [${tag}] ${msg}`;
        if (obj !== null) {
            try {
                text += `\n` + JSON.stringify(obj, null, 2);
            } catch (e) {
                text += ` [Unstringifiable Object]`;
            }
        }
        logEl.textContent += '\n' + text;
        logEl.scrollTop = logEl.scrollHeight;

        if (tag.includes('ERROR')) {
            console.error(`[${tag}]`, msg, obj || '');
        } else if (tag.includes('WARN')) {
            console.warn(`[${tag}]`, msg, obj || '');
        } else {
            console.log(`[${tag}]`, msg, obj || '');
        }
    }

    function clearLog() {
        logEl.textContent = '> Console cleared.';
    }

    window.addEventListener('DOMContentLoaded', () => {
        writeLog('INIT', 'DOM ready.');
        if (typeof PrintBridge === 'undefined') {
            writeLog('WARN', 'PrintBridge SDK script not found on window object.');
        } else {
            writeLog('INFO', 'PrintBridge SDK active. Exposed members:', Object.keys(PrintBridge));
        }
    });

    async function loadPrinters() {
        const deviceId = document.getElementById('deviceId').value.trim();
        const btnFetch = document.getElementById('btnFetch');

        if (!deviceId) {
            alert('Please enter a valid Device UUID');
            return;
        }

        btnFetch.disabled = true;
        writeLog('REQ', `Initiating printer discovery for: ${deviceId}`);

        try {
            writeLog('SDK', 'Invoking PrintBridge.getPrinters()...');
            
            const res = await PrintBridge.getPrinters({ 
                deviceId: deviceId,
                timeout: 10000 
            });

            writeLog('SDK-ACK', 'Received initial acknowledgement:', res);

            let printers = [];

            // Case 1: Direct sync array
            if (Array.isArray(res)) {
                printers = res;
            } else if (res && Array.isArray(res.printers)) {
                printers = res.printers;
            } 
            // Case 2: Asynchronous MQTT dispatch
            else if (res && res.requestId) {
                writeLog('MQTT-WAIT', `Hardware notified. Request ID: ${res.requestId}`);
                
                // Event listener check (SDK built-in hook)
                if (typeof PrintBridge.on === 'function') {
                    writeLog('LISTENER', 'Attaching PrintBridge.on() event subscriber...');
                    PrintBridge.on('printers', (data) => {
                        writeLog('MQTT-DATA', 'Printers received via event listener:', data);
                        renderPrinterOptions(data.printers || data);
                    });
                    return;
                }

                // Fallback polling strategy
                printers = await pollPrinterResponse(deviceId, res.requestId);
            }

            renderPrinterOptions(printers);

        } catch (err) {
            writeLog('ERROR', `Discovery call failed: ${err.message || err}`);
        } finally {
            btnFetch.disabled = false;
        }
    }

    async function pollPrinterResponse(deviceId, requestId) {
        writeLog('POLL', 'Polling API for hardware response topic output (up to 5 attempts)...');
        
        for (let attempt = 1; attempt <= 5; attempt++) {
            writeLog('POLL-ATTEMPT', `Attempt ${attempt}/5: waiting 2.5s...`);
            await new Promise(resolve => setTimeout(resolve, 2500));

            try {
                const resp = await fetch('https://printbridge.online/api/v1/get-printers', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ deviceId: deviceId, requestId: requestId })
                });

                if (resp.ok) {
                    const data = await resp.json();
                    writeLog('POLL-RESP', `Attempt ${attempt} raw reply:`, data);
                    
                    if (Array.isArray(data.printers) && data.printers.length > 0) {
                        return data.printers;
                    }
                    if (Array.isArray(data) && data.length > 0) {
                        return data;
                    }
                }
            } catch (err) {
                writeLog('POLL-WARN', `Attempt ${attempt} request error: ${err.message}`);
            }
        }
        return [];
    }

    function renderPrinterOptions(printers) {
        const printerSelect = document.getElementById('printerSelect');
        printerSelect.innerHTML = '';

        if (Array.isArray(printers) && printers.length > 0) {
            writeLog('SUCCESS', `Loaded ${printers.length} printer(s) to dropdown.`);
            printers.forEach((p, idx) => {
                const name = typeof p === 'object' ? (p.name || p.printerName || p.id || JSON.stringify(p)) : p;
                const opt = document.createElement('option');
                opt.value = name;
                opt.textContent = name;
                printerSelect.appendChild(opt);
                writeLog('PRINTER-ITEM', `[${idx}] ${name}`);
            });
            document.getElementById('btnPrint').disabled = false;
        } else {
            writeLog('EMPTY', 'No printers resolved. The hardware client may be offline or not responding.');
            printerSelect.innerHTML = '<option value="">No printers detected</option>';
            document.getElementById('btnPrint').disabled = true;
        }
    }

    function executeTestPrint() {
        const deviceId = document.getElementById('deviceId').value.trim();
        const printerName = document.getElementById('printerSelect').value;
        const width = document.getElementById('paperWidth').value;

        if (!printerName) {
            alert('Select a printer first');
            return;
        }

        const payload = {
            deviceId: deviceId,
            printerName: printerName,
            width: width,
            html: `
                <div style="font-family: monospace; font-size: 12px; width: 100%;">
                    <div style="text-align: center;">
                        <h2 style="margin: 0;">PRINTBRIDGE OK</h2>
                        <p style="margin: 4px 0;">Hardware Link Test</p>
                    </div>
                    <div style="border-top: 1px dashed #000; margin: 8px 0;"></div>
                    <p style="margin: 4px 0;">Device: ${deviceId}</p>
                    <p style="margin: 4px 0;">Printer: ${printerName}</p>
                    <p style="margin: 4px 0;">Width: ${width}</p>
                    <div style="border-top: 1px dashed #000; margin: 8px 0;"></div>
                    <p style="text-align: center; margin-top: 8px;">Completed Successfully</p>
                </div>
            `
        };

        writeLog('PRINT-REQ', `Sending payload to ${printerName}...`, payload);

        PrintBridge.print(payload)
            .then(res => {
                writeLog('PRINT-SUCCESS', 'Print dispatched to bridge:', res);
            })
            .catch(err => {
                writeLog('PRINT-ERROR', 'Print dispatch rejected:', {
                    message: err.message || err,
                    details: err
                });
            });
    }
</script>

</body>
</html>