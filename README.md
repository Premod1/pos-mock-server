# POS Card Machine Connection & User Guide

Welcome to the POS Mock Server. This guide outlines the setup and workflow for connecting and operating the POS Card Machine Terminal.

---

## 🚀 Step-by-Step Guide

### 1. Terminal Configuration
* Locate the **Wi-Fi Terminal Settings** card on the dashboard.
* Enter the **Card Machine IP Address** and **Port**.
* Click **Update Settings** to save the configuration and establish a connection.

### 2. Initiating a Sale
* Navigate to the **Simulate New Sale** card.
* Enter the required **Sale Amount** (in LKR).
* Enter the **Customer Mobile Number** (which is optional).
* Click **Generate Sale** to push the transaction into the queue.

### 3. Card Authorization
* Once a sale is submitted, it is added to the queue as `PENDING_CARD_PAYMENT`.
* A payment request is automatically transmitted to the configured POS card machine.
* The card terminal will prompt the customer to **tap, swipe, or insert (dip)** the card.

### 4. Status Update
* Once the transaction is authorized, the status on the dashboard will instantly update to **PAID**.
* If the connection fails or the card transaction is declined, the status will remain pending or show the failure details in the raw payload.

---

## 📸 Dashboard Preview

Below is the user interface of the POS Mock Server dashboard:

![POS Card Machine Dashboard Interface](./Screenshot%20from%202026-06-05%2022-26-19.png)

---

## 🖨️ PrintBridge SDK Integration Guide

This project integrates the **PrintBridge JS SDK** to communicate with thermal receipt printers linked to hardware devices.

### 1. Include the SDK
Include the official PrintBridge JavaScript SDK in your HTML `<head>` or before your closing `</body>` tag:

```html
<script src="https://printbridge.online/sdk/v1/printbridge.js"></script>
```

---

### 2. Fetch Connected Printers
To retrieve the list of printers connected to a specific device, call `PrintBridge.getPrinters()` with the device's UUID:

```javascript
async function fetchPrinters(deviceId) {
    try {
        const response = await PrintBridge.getPrinters({
            deviceId: deviceId, // e.g. '245f4431-da6d-4bf9-97fd-e00e012d027d'
            timeout: 5          // Timeout in seconds
        });

        let printers = [];

        // Handle direct / synchronous printer array
        if (response && Array.isArray(response.printers)) {
            printers = response.printers;
        } else if (Array.isArray(response)) {
            printers = response;
        } else if (response && response.requestId) {
            // Optional: fallback polling via backend/API if async request ID is returned
            console.log('Async request ID:', response.requestId);
        }

        // Extract printer names
        printers.forEach((printer) => {
            const printerName = typeof printer === 'object' 
                ? (printer.Name || printer.name || printer.printerName) 
                : printer;
            console.log('Found printer:', printerName);
        });

        return printers;
    } catch (error) {
        console.error('Error fetching printers:', error);
        throw error;
    }
}
```

---

### 3. Print a Receipt / Document
To send a print job, call `PrintBridge.print()` passing the `deviceId`, `printerName`, paper `width`, and standard `html` content:

```javascript
function printReceipt(deviceId, printerName, paperWidth = '80mm') {
    const payload = {
        deviceId: deviceId,
        printerName: printerName,
        width: paperWidth, // '80mm' or '58mm'
        html: `
            <div style="font-family: monospace; font-size: 12px; width: 100%; padding: 4px;">
                <div style="text-align: center;">
                    <h3 style="margin: 0;">RECEIPT TITLE</h3>
                    <p style="margin: 4px 0;">Order #12345</p>
                </div>
                <div style="border-top: 1px dashed #000; margin: 8px 0;"></div>
                <p>Item 1: Rs. 500.00</p>
                <p>Item 2: Rs. 250.00</p>
                <div style="border-top: 1px dashed #000; margin: 8px 0;"></div>
                <p style="text-align: right; font-weight: bold;">Total: Rs. 750.00</p>
                <p style="text-align: center; margin-top: 10px;">Thank You!</p>
            </div>
        `
    };

    PrintBridge.print(payload)
        .then((result) => {
            console.log('Print job sent successfully:', result);
        })
        .catch((error) => {
            console.error('Print job failed:', error);
        });
}
```

---

### 4. Interactive Diagnostics Page
A ready-to-use testing page is available at:

* **URL Route:** `/test-sdk` (View: `resources/views/sdk.blade.php`)
* **Features:** Device UUID input, fetch printer dropdown, paper width selection (80mm / 58mm), real-time debug console log, and test print trigger.

