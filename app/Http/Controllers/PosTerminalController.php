<?php

namespace App\Http\Controllers;

use App\Models\PosTerminalLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PosTerminalController extends Controller
{
    /**
     * Display the terminal logs dashboard.
     */
    public function index()
    {
        $logs = PosTerminalLog::latest()->get();
        return view('pos_logs', compact('logs'));
    }

    /**
     * API: POST endpoint to receive logs from C# agent and store in DB.
     */
    public function storeLog(Request $request)
    {
        // 1. Validation (C# එකෙන් එන දේවල් නිවැරදිද බලනවා)
        $validated = $request->validate([
            'timestamp'  => 'required|string',
            'level'      => 'required|string',
            'message'    => 'required|string',
            'agent_name' => 'nullable|string'
        ]);

        try {
            // 2. Database එකේ සේව් කිරීම
            PosTerminalLog::create([
                'agent_name'       => $validated['agent_name'] ?? 'Unknown-Agent',
                'level'            => strtoupper($validated['level']),
                'message'          => $validated['message'],
                'client_timestamp' => Carbon::parse($validated['timestamp']),
            ]);

            // 3. (Optional) Laravel වල default laravel.log ෆයිල් එකටත් ලියාගන්න ඕනේ නම්:
            $systemLogLine = "[{$validated['level']}] [C# AGENT: {$validated['agent_name']}] {$validated['message']}";
            if (in_array(strtoupper($validated['level']), ['ERROR', 'CRITICAL'])) {
                Log::error($systemLogLine);
            }

            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            // මොකක් හරි හේතුවකින් DB සේව් වුනේ නැත්නම් Laravel log එකේ ලියනවා
            Log::critical("Failed to save C# Agent Log to Database: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal Server Error'], 500);
        }
    }

    /**
     * GET: Fetch all logs formatted for AJAX UI data table.
     */
    public function getLogsData()
    {
        $logs = PosTerminalLog::latest()->get()->map(function($log) {
            return [
                'id' => $log->id,
                'created_at_formatted' => $log->created_at->format('Y-m-d H:i:s'),
                'client_timestamp_formatted' => Carbon::parse($log->client_timestamp)->format('Y-m-d H:i:s'),
                'agent_name' => $log->agent_name ?? 'Unknown-Agent',
                'level' => strtoupper($log->level),
                'message' => $log->message,
            ];
        });

        return response()->json($logs);
    }

    /**
     * POST: Clear all terminal logs from the database.
     */
    public function clearLogs()
    {
        PosTerminalLog::truncate();
        return redirect()->back()->with('success', 'Terminal logs cleared successfully!');
    }
}
