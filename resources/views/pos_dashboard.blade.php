<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Mock Server Dashboard</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Premium Vanilla CSS Styling -->
    <style>
        :root {
            --bg-primary: #0a0f1d;
            --bg-secondary: #131a30;
            --bg-card: #1c2541;
            --accent-color: #4f46e5;
            --accent-hover: #4338ca;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --success: #10b981;
            --success-glow: rgba(16, 185, 129, 0.15);
            --warning: #f59e0b;
            --warning-glow: rgba(245, 158, 11, 0.15);
            --danger: #ef4444;
            --danger-glow: rgba(239, 68, 68, 0.15);
            --border-color: #2e3a5f;
            --font-main: 'Outfit', sans-serif;
            --font-mono: 'Space Mono', monospace;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            font-family: var(--font-main);
            line-height: 1.5;
            min-height: 100vh;
            padding: 2rem 1.5rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header design */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .logo-area h1 {
            font-size: 1.85rem;
            font-weight: 700;
            background: linear-gradient(135deg, #a5b4fc 0%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.02em;
        }

        .logo-area p {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        .server-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background-color: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--success);
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background-color: var(--success);
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 8px var(--success);
            animation: pulse 1.8s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.9); opacity: 0.6; }
            50% { transform: scale(1.15); opacity: 1; box-shadow: 0 0 12px var(--success); }
            100% { transform: scale(0.9); opacity: 0.6; }
        }

        /* Toast notifications */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 0.75rem;
            margin-bottom: 2rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: slideIn 0.3s ease-out;
        }

        .alert-success {
            background-color: var(--success-glow);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
        }

        @keyframes slideIn {
            from { transform: translateY(-10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Layout Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-bottom: 2.5rem;
        }

        @media (min-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        /* Glassmorphism Card Style */
        .card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.75rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.4), 0 10px 15px -8px rgba(0, 0, 0, 0.4);
            border-color: rgba(99, 102, 241, 0.35);
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-primary);
        }

        /* Forms styling */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .input-group {
            position: relative;
        }

        .form-control {
            width: 100%;
            background-color: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            color: var(--text-primary);
            font-family: var(--font-main);
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.25);
            background-color: var(--bg-primary);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: var(--accent-color);
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            width: 100%;
            gap: 0.5rem;
        }

        .btn:hover {
            background-color: var(--accent-hover);
            transform: translateY(-1px);
        }

        .btn:active {
            transform: translateY(1px);
        }

        /* Sales Table Card styling */
        .table-card {
            width: 100%;
            overflow: hidden;
            padding: 0;
        }

        .table-header-wrapper {
            padding: 1.5rem 1.75rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background-color: rgba(19, 26, 48, 0.5);
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem 1.75rem;
            border-bottom: 1px solid var(--border-color);
        }

        td {
            padding: 1.15rem 1.75rem;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.95rem;
            color: var(--text-primary);
            vertical-align: middle;
        }

        tr:hover td {
            background-color: rgba(255, 255, 255, 0.02);
        }

        .invoice-cell {
            font-family: var(--font-mono);
            font-weight: 500;
            color: #818cf8;
            font-size: 0.85rem;
        }

        .mobile-cell {
            font-family: var(--font-mono);
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .amount-cell {
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .badge-pending {
            background-color: var(--warning-glow);
            border: 1px solid rgba(245, 158, 11, 0.2);
            color: #fbbf24;
        }

        .badge-completed {
            background-color: var(--success-glow);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #34d399;
        }

        .badge-failed {
            background-color: var(--danger-glow);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        .details-btn {
            background: none;
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            border-radius: 0.375rem;
            padding: 0.35rem 0.65rem;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .details-btn:hover {
            border-color: var(--accent-color);
            color: var(--text-primary);
            background-color: rgba(79, 70, 229, 0.1);
        }

        /* Response Code Snippet */
        .response-container {
            display: none;
            padding: 1rem 1.75rem;
            background-color: rgba(10, 15, 29, 0.6);
            border-bottom: 1px solid var(--border-color);
        }

        .response-container pre {
            font-family: var(--font-mono);
            font-size: 0.8rem;
            color: #a5b4fc;
            overflow-x: auto;
            white-space: pre-wrap;
            word-break: break-all;
        }

        .empty-state {
            padding: 3rem 1.75rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .empty-state svg {
            width: 48px;
            height: 48px;
            stroke: var(--text-muted);
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Refresh Indicator info */
        .refresh-info {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .spinner {
            width: 12px;
            height: 12px;
            border: 2px solid var(--border-color);
            border-top-color: var(--accent-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

    <div class="container">
        
        <!-- Header -->
        <header>
            <div class="logo-area">
                <h1>POS Terminal Mock Server</h1>
                <p>Simulate Sales & Wi-Fi Terminal Polling</p>
            </div>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <a href="{{ route('terminal-logs.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; background-color: rgba(244, 63, 94, 0.1); border: 1px solid rgba(244, 63, 94, 0.2); padding: 0.5rem 1.2rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 500; color: #fb7185; text-decoration: none; transition: all 0.2s ease;" onmouseover="this.style.backgroundColor='rgba(244,63,94,0.18)'" onmouseout="this.style.backgroundColor='rgba(244,63,94,0.1)'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <span>View Terminal Logs</span>
                </a>
                <div class="server-status">
                    <span class="status-dot"></span>
                    <span>Gateway Server Active</span>
                </div>
            </div>
        </header>

        <!-- Flash Message -->
        @if(session('success'))
            <div class="alert alert-success" id="success-alert">
                <span>{{ session('success') }}</span>
                <span style="cursor: pointer;" onclick="document.getElementById('success-alert').style.display='none'">&times;</span>
            </div>
        @endif

        <!-- Grid Layout for Config & Sale Creation -->
        <div class="dashboard-grid">
            
            <!-- Terminal Setup Card -->
            <div class="card">
                <div class="card-title">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: var(--accent-color);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>Wi-Fi Terminal Settings</span>
                </div>
                
                <form action="{{ route('terminal.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="machine_ip">Terminal Wi-Fi IP Address</label>
                        <input type="text" name="machine_ip" id="machine_ip" class="form-control" placeholder="e.g. 192.168.1.150" value="{{ old('machine_ip', $terminal->machine_ip) }}" required>
                        @error('machine_ip')
                            <small style="color: var(--danger); margin-top: 0.25rem; display: block;">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="machine_port">Terminal Port</label>
                        <input type="number" name="machine_port" id="machine_port" class="form-control" placeholder="e.g. 8888" value="{{ old('machine_port', $terminal->machine_port) }}" required>
                        @error('machine_port')
                            <small style="color: var(--danger); margin-top: 0.25rem; display: block;">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn">
                        Save Configuration
                    </button>
                </form>
            </div>

            <!-- Create Sale Form Card -->
            <div class="card">
                <div class="card-title">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #10b981;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Simulate New Sale</span>
                </div>

                <form action="{{ route('sale.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="amount">Sale Amount (LKR)</label>
                        <input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="e.g. 350.00" value="{{ old('amount') }}" required>
                        @error('amount')
                            <small style="color: var(--danger); margin-top: 0.25rem; display: block;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="customer_mobile">Customer Mobile Number</label>
                        <input type="text" name="customer_mobile" id="customer_mobile" class="form-control" placeholder="e.g. 94771234567" value="{{ old('customer_mobile') }}" required>
                        @error('customer_mobile')
                            <small style="color: var(--danger); margin-top: 0.25rem; display: block;">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn" style="background-color: var(--success); hover: background-color: #059669;">
                        Generate Sale (Push to Queue)
                    </button>
                </form>
            </div>

        </div>

        <!-- Sales Queue Table Card -->
        <div class="card table-card">
            <div class="table-header-wrapper">
                <div style="display: flex; flex-direction: column; gap: 0.2rem;">
                    <h2 style="font-size: 1.15rem; font-weight: 600;">Sales Queue & Transaction History</h2>
                    <p style="font-size: 0.8rem; color: var(--text-secondary);">Watch C# update transactions in real-time</p>
                </div>
                <div class="refresh-info">
                    <span class="spinner"></span>
                    <span>Auto-refreshing...</span>
                </div>
            </div>

            <div class="table-responsive" id="table-container">
                @if($sales->isEmpty())
                    <div class="empty-state">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <p>No sales simulated yet. Use the form above to add a sale to the polling queue.</p>
                    </div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Date / Time</th>
                                <th>Invoice Number</th>
                                <th>Mobile</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th style="text-align: right;">Action / Info</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $sale)
                                @php
                                    $badgeClass = 'badge-pending';
                                    $displayStatus = $sale->status;
                                    if ($sale->status === 'PENDING_CARD_PAYMENT') {
                                        $badgeClass = 'badge-pending';
                                        $displayStatus = 'PENDING PAYMENT';
                                    } elseif ($sale->status === 'PAID') {
                                        $badgeClass = 'badge-completed';
                                        $displayStatus = 'PAID';
                                    } elseif ($sale->status === 'FAILED') {
                                        $badgeClass = 'badge-failed';
                                        $displayStatus = 'FAILED';
                                    }
                                @endphp
                                <tr>
                                    <td style="color: var(--text-secondary); font-size: 0.85rem;">
                                        {{ $sale->created_at->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td class="invoice-cell">
                                        {{ $sale->invoice_number }}
                                    </td>
                                    <td class="mobile-cell">
                                        {{ $sale->customer_mobile }}
                                    </td>
                                    <td class="amount-cell">
                                        Rs. {{ number_format($sale->amount, 2) }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $displayStatus }}
                                        </span>
                                    </td>
                                    <td style="text-align: right;">
                                        @if($sale->pos_response)
                                            <button class="details-btn" onclick="toggleDetails('{{ $sale->id }}')">
                                                Show Payload
                                            </button>
                                        @else
                                            <span style="color: var(--text-muted); font-size: 0.8rem; font-style: italic;">
                                                Waiting for C#...
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                
                                @if($sale->pos_response)
                                    <tr id="details-row-{{ $sale->id }}" class="response-container">
                                        <td colspan="6">
                                            <div style="padding: 0.5rem 0;">
                                                <h4 style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.4rem; letter-spacing: 0.05em;">C# POST Response Payload:</h4>
                                                <pre><code>{{ json_encode(json_decode($sale->pos_response), JSON_PRETTY_PRINT) }}</code></pre>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

    </div>

    <!-- Live AJAX Refresh Script & Details Toggle -->
    <script>
        // Track which detail rows are expanded so they don't close during refresh
        const openDetails = new Set();

        // Toggle C# response JSON detail rows
        function toggleDetails(id) {
            const row = document.getElementById('details-row-' + id);
            if (row) {
                const idStr = String(id);
                if (row.style.display === 'table-row') {
                    row.style.display = 'none';
                    openDetails.delete(idStr);
                } else {
                    row.style.display = 'table-row';
                    openDetails.add(idStr);
                }
            }
        }

        // Periodically refresh ONLY the data table via AJAX (without reloading the page)
        function refreshSalesTable() {
            fetch('{{ route('sales.data') }}')
                .then(response => response.json())
                .then(sales => {
                    const container = document.getElementById('table-container');
                    
                    if (sales.length === 0) {
                        container.innerHTML = `
                            <div class="empty-state">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                <p>No sales simulated yet. Use the form above to add a sale to the polling queue.</p>
                            </div>
                        `;
                        return;
                    }

                    let html = `
                        <table>
                            <thead>
                                <tr>
                                    <th>Date / Time</th>
                                    <th>Invoice Number</th>
                                    <th>Mobile</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th style="text-align: right;">Action / Info</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    sales.forEach(sale => {
                        let badgeClass = 'badge-pending';
                        let statusLabel = sale.status;

                        if (sale.status === 'PENDING_CARD_PAYMENT') {
                            badgeClass = 'badge-pending';
                            statusLabel = 'PENDING PAYMENT';
                        } else if (sale.status === 'PAID') {
                            badgeClass = 'badge-completed';
                            statusLabel = 'PAID';
                        } else if (sale.status === 'FAILED') {
                            badgeClass = 'badge-failed';
                            statusLabel = 'FAILED';
                        }

                        let actionHtml = '';
                        if (sale.pos_response_raw) {
                            actionHtml = `
                                <button class="details-btn" onclick="toggleDetails('${sale.id}')">
                                    Show Payload
                                </button>
                            `;
                        } else {
                            actionHtml = `
                                <span style="color: var(--text-muted); font-size: 0.8rem; font-style: italic;">
                                    Waiting for C#...
                                </span>
                            `;
                        }

                        html += `
                            <tr>
                                <td style="color: var(--text-secondary); font-size: 0.85rem;">
                                    ${sale.created_at_formatted}
                                </td>
                                <td class="invoice-cell">
                                    ${sale.invoice_number}
                                </td>
                                <td class="mobile-cell">
                                    ${sale.customer_mobile}
                                </td>
                                <td class="amount-cell">
                                    ${sale.amount_formatted}
                                </td>
                                <td>
                                    <span class="badge ${badgeClass}">
                                        ${statusLabel}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    ${actionHtml}
                                </td>
                            </tr>
                        `;

                        if (sale.pos_response_raw) {
                            let parsedResponse = {};
                            try {
                                parsedResponse = JSON.parse(sale.pos_response_raw);
                            } catch(e) {}
                            
                            const isDisplayed = openDetails.has(String(sale.id)) ? 'table-row' : 'none';
                            
                            html += `
                                <tr id="details-row-${sale.id}" class="response-container" style="display: ${isDisplayed}">
                                    <td colspan="6">
                                        <div style="padding: 0.5rem 0;">
                                            <h4 style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.4rem; letter-spacing: 0.05em;">C# POST Response Payload:</h4>
                                            <pre><code>${JSON.stringify(parsedResponse, null, 4)}</code></pre>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        }
                    });

                    html += `
                            </tbody>
                        </table>
                    `;

                    container.innerHTML = html;
                })
                .catch(err => console.error('Error refreshing sales:', err));
        }

        // Fetch sales log updates every 3 seconds dynamically without reloading the page
        setInterval(refreshSalesTable, 3000);
    </script>
</body>
</html>
