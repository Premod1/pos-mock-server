<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Terminal Logs</title>
    
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
            background: linear-gradient(135deg, #fb7185 0%, #e11d48 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.02em;
        }

        .logo-area p {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: transparent;
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.6rem 1.2rem;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            gap: 0.5rem;
        }

        .btn-outline:hover {
            border-color: var(--text-primary);
            background-color: rgba(255, 255, 255, 0.05);
            transform: translateY(-1px);
        }

        .btn-danger-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: transparent;
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.4);
            border-radius: 0.5rem;
            padding: 0.6rem 1.2rem;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            gap: 0.5rem;
        }

        .btn-danger-outline:hover {
            border-color: var(--danger);
            background-color: var(--danger-glow);
            transform: translateY(-1px);
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

        /* Filter Controls Card */
        .controls-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        @media (min-width: 768px) {
            .controls-card {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }

        .search-wrapper {
            position: relative;
            flex-grow: 1;
            max-width: 450px;
        }

        .search-input {
            width: 100%;
            background-color: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.65rem 1rem 0.65rem 2.5rem;
            color: var(--text-primary);
            font-family: var(--font-main);
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.25);
            background-color: var(--bg-primary);
        }

        .search-icon {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
        }

        .filter-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .filter-btn {
            background-color: var(--bg-secondary);
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            padding: 0.45rem 0.85rem;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .filter-btn:hover {
            color: var(--text-primary);
            border-color: var(--text-secondary);
        }

        .filter-btn.active {
            background-color: var(--accent-color);
            color: white;
            border-color: var(--accent-color);
            box-shadow: 0 0 8px rgba(79, 70, 229, 0.3);
        }

        /* Logs Table Card styling */
        .table-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
            width: 100%;
            overflow: hidden;
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

        .mono-cell {
            font-family: var(--font-mono);
            font-size: 0.85rem;
            color: var(--text-secondary);
            white-space: nowrap;
        }

        .message-cell {
            font-size: 0.9rem;
            color: var(--text-primary);
            word-break: break-all;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.65rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-info {
            background-color: var(--success-glow);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #34d399;
        }

        .badge-debug {
            background-color: rgba(148, 163, 184, 0.15);
            border: 1px solid rgba(148, 163, 184, 0.2);
            color: #cbd5e1;
        }

        .badge-warning {
            background-color: var(--warning-glow);
            border: 1px solid rgba(245, 158, 11, 0.2);
            color: #fbbf24;
        }

        .badge-error, .badge-critical {
            background-color: var(--danger-glow);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        .empty-state {
            padding: 4rem 1.75rem;
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
            border-top-color: #fb7185;
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
                <h1>POS Terminal Logs</h1>
                <p>Monitor C# application log traces and execution stream</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('dashboard') }}" class="btn-outline">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Back to Dashboard</span>
                </a>
                
                @if(!$logs->isEmpty())
                    <form action="{{ route('terminal-logs.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear all logs? This action cannot be undone.');">
                        @csrf
                        <button type="submit" class="btn-danger-outline">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>Clear All</span>
                        </button>
                    </form>
                @endif
            </div>
        </header>

        <!-- Flash Message -->
        @if(session('success'))
            <div class="alert alert-success" id="success-alert">
                <span>{{ session('success') }}</span>
                <span style="cursor: pointer;" onclick="document.getElementById('success-alert').style.display='none'">&times;</span>
            </div>
        @endif

        <!-- Controls (Filters and Search) -->
        <div class="controls-card">
            <!-- Search Bar -->
            <div class="search-wrapper">
                <svg class="search-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" id="search-input" class="search-input" placeholder="Search by message or agent name..." oninput="handleSearchFilter()">
            </div>

            <!-- Filter Buttons -->
            <div class="filter-buttons">
                <button class="filter-btn active" data-level="ALL" onclick="setLevelFilter('ALL')">ALL</button>
                <button class="filter-btn" data-level="INFO" onclick="setLevelFilter('INFO')">INFO</button>
                <button class="filter-btn" data-level="DEBUG" onclick="setLevelFilter('DEBUG')">DEBUG</button>
                <button class="filter-btn" data-level="WARNING" onclick="setLevelFilter('WARNING')">WARNING</button>
                <button class="filter-btn" data-level="ERROR" onclick="setLevelFilter('ERROR')">ERROR</button>
                <button class="filter-btn" data-level="CRITICAL" onclick="setLevelFilter('CRITICAL')">CRITICAL</button>
            </div>
        </div>

        <!-- Logs Table Card -->
        <div class="table-card">
            <div class="table-header-wrapper">
                <div style="display: flex; flex-direction: column; gap: 0.2rem;">
                    <h2 style="font-size: 1.15rem; font-weight: 600;">System Event Stream</h2>
                    <p style="font-size: 0.8rem; color: var(--text-secondary);">Latest client-side logs captured from terminal agent</p>
                </div>
                <div class="refresh-info">
                    <span class="spinner"></span>
                    <span>Live updating...</span>
                </div>
            </div>

            <div class="table-responsive" id="table-container">
                @if($logs->isEmpty())
                    <div class="empty-state">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <p>No terminal logs captured yet. Logs sent from the C# agent will display here.</p>
                    </div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Client Timestamp</th>
                                <th>Logged to Server</th>
                                <th>Agent</th>
                                <th>Level</th>
                                <th>Log Message</th>
                            </tr>
                        </thead>
                        <tbody id="logs-tbody">
                            @foreach($logs as $log)
                                <tr class="log-row" data-level="{{ strtoupper($log->level) }}">
                                    <td class="mono-cell">
                                        {{ \Carbon\Carbon::parse($log->client_timestamp)->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td class="mono-cell" style="color: var(--text-muted)">
                                        {{ $log->created_at->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td style="font-weight: 500; color: #a5b4fc">
                                        {{ $log->agent_name ?? 'Unknown-Agent' }}
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = 'badge-info';
                                            $lvl = strtoupper($log->level);
                                            if ($lvl === 'INFO') $badgeClass = 'badge-info';
                                            elseif ($lvl === 'DEBUG') $badgeClass = 'badge-debug';
                                            elseif ($lvl === 'WARNING') $badgeClass = 'badge-warning';
                                            elseif ($lvl === 'ERROR') $badgeClass = 'badge-error';
                                            elseif ($lvl === 'CRITICAL') $badgeClass = 'badge-critical';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $lvl }}
                                        </span>
                                    </td>
                                    <td class="message-cell">
                                        {{ $log->message }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

    </div>

    <!-- Live AJAX Refresh, Search & Filter Script -->
    <script>
        let selectedLevel = 'ALL';
        let searchQuery = '';
        let allLogs = [];

        // Fetch logs data immediately on load to populate local array
        function fetchInitialLogs() {
            fetch('{{ route('terminal-logs.data') }}')
                .then(response => response.json())
                .then(data => {
                    allLogs = data;
                    renderLogsTable();
                })
                .catch(err => console.error('Error fetching initial logs:', err));
        }

        // Set log level filter
        function setLevelFilter(level) {
            selectedLevel = level;
            
            // Toggle active state on buttons
            document.querySelectorAll('.filter-btn').forEach(btn => {
                if (btn.getAttribute('data-level') === level) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            renderLogsTable();
        }

        // Search text filter
        function handleSearchFilter() {
            searchQuery = document.getElementById('search-input').value.toLowerCase().trim();
            renderLogsTable();
        }

        // Function to filter and build table rows dynamically
        function renderLogsTable() {
            const container = document.getElementById('table-container');
            
            // Filter logs locally
            const filteredLogs = allLogs.filter(log => {
                const matchesLevel = (selectedLevel === 'ALL' || log.level === selectedLevel);
                const matchesSearch = (
                    log.message.toLowerCase().includes(searchQuery) ||
                    log.agent_name.toLowerCase().includes(searchQuery)
                );
                return matchesLevel && matchesSearch;
            });

            if (allLogs.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <p>No terminal logs captured yet. Logs sent from the C# agent will display here.</p>
                    </div>
                `;
                return;
            }

            if (filteredLogs.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <p>No logs found matching your filters.</p>
                    </div>
                `;
                return;
            }

            let html = `
                <table>
                    <thead>
                        <tr>
                            <th>Client Timestamp</th>
                            <th>Logged to Server</th>
                            <th>Agent</th>
                            <th>Level</th>
                            <th>Log Message</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            filteredLogs.forEach(log => {
                let badgeClass = 'badge-info';
                if (log.level === 'INFO') badgeClass = 'badge-info';
                else if (log.level === 'DEBUG') badgeClass = 'badge-debug';
                else if (log.level === 'WARNING') badgeClass = 'badge-warning';
                else if (log.level === 'ERROR') badgeClass = 'badge-error';
                else if (log.level === 'CRITICAL') badgeClass = 'badge-critical';

                html += `
                    <tr>
                        <td class="mono-cell">${log.client_timestamp_formatted}</td>
                        <td class="mono-cell" style="color: var(--text-muted)">${log.created_at_formatted}</td>
                        <td style="font-weight: 500; color: #a5b4fc">${log.agent_name}</td>
                        <td>
                            <span class="badge ${badgeClass}">${log.level}</span>
                        </td>
                        <td class="message-cell">${log.message}</td>
                    </tr>
                `;
            });

            html += `
                    </tbody>
                </table>
            `;

            container.innerHTML = html;
        }

        // Periodically refresh the data table via AJAX
        function refreshLogsTable() {
            fetch('{{ route('terminal-logs.data') }}')
                .then(response => response.json())
                .then(data => {
                    allLogs = data;
                    renderLogsTable();
                })
                .catch(err => console.error('Error refreshing logs:', err));
        }

        // Run on load
        fetchInitialLogs();

        // Refresh log traces every 3 seconds dynamically without reloading the page
        setInterval(refreshLogsTable, 30000000);
    </script>
</body>
</html>
