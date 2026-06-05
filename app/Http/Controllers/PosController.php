<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Terminal;
use App\Models\Sale;
use Illuminate\Support\Facades\Log;

class PosController extends Controller
{
    /**
     * Display the main web UI dashboard.
     */
    public function index()
    {
        // Fetch terminal settings from database, fallback to defaults if table is empty
        $terminal = Terminal::first() ?? new Terminal([
            'machine_ip' => '192.168.1.150',
            'machine_port' => 8888,
        ]);

        // Get all sales, sorted by newest first
        $sales = Sale::latest()->get();

        return view('pos_dashboard', compact('terminal', 'sales'));
    }

    /**
     * Update the Wi-Fi Terminal IP and Port configuration.
     */
    public function updateTerminal(Request $request)
    {
        $request->validate([
            'machine_ip' => 'required|ip',
            'machine_port' => 'required|integer|min:1|max:65535',
        ]);

        $terminal = Terminal::first() ?? new Terminal();
        $terminal->machine_ip = $request->input('machine_ip');
        $terminal->machine_port = $request->input('machine_port');
        $terminal->save();

        return redirect()->back()->with('success', 'Terminal configuration updated successfully!');
    }

    /**
     * Create a new Sale (generates invoice ID and saves status as PENDING_CARD_PAYMENT).
     */
    public function storeSale(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'customer_mobile' => 'required|string|min:9|max:15',
        ]);

        // Generate a clean, unique invoice number
        // Format: INV-YYYYMMDD-TIME-RAND (e.g. INV-20260528-161140-54)
        $invoiceNumber = 'INV-' . date('Ymd-His') . '-' . rand(10, 99);

        Sale::create([
            'invoice_number' => $invoiceNumber,
            'amount' => $request->input('amount'),
            'customer_mobile' => $request->input('customer_mobile'),
            'status' => 'PENDING_CARD_PAYMENT',
        ]);

        return redirect()->back()->with('success', "New sale created! Cashier queued Card Payment for Invoice: {$invoiceNumber}");
    }

    /**
     * API: GET Polling endpoint for the C# application.
     */
    public function poll()
    {
        // Find the oldest pending card payment to process in order (FIFO)
        $sale = Sale::where('status', 'PENDING_CARD_PAYMENT')->oldest()->first();

        if ($sale) {
            // Fetch configuration from database, fallback to defaults if empty
            $terminal = Terminal::first() ?? new Terminal([
                'machine_ip' => '192.168.1.150',
                'machine_port' => 8888,
            ]);

            return response()->json([
                'invoice_number'  => $sale->invoice_number,
                'amount'         => (float) $sale->amount,
                'customer_mobile' => $sale->customer_mobile,
                'machine_ip'      => $terminal->machine_ip,
                'machine_port'    => (int) $terminal->machine_port,
            ]);
        }

        // Return empty JSON/null when there is no pending card payment
        return response()->json(null);
    }

    /**
     * API: POST Update endpoint from C# application after payment completion.
     */
    public function update(Request $request)
    {
        Log::info("POS_RESPONSE_RECEIVED: ", $request->all());

        // 1. C# එකෙන් එන විවිධ Casing වලට Fallbacks දීම
        $invoiceNumber = $request->input('invoice_number') ?? 
                         $request->input('invoiceNumber') ?? 
                         $request->input('InvoiceNumber');

        // 🚨 [ලොකුම විසඳුම]: වැරදිලාවත් Invoice Number එක හිස්ව (Null/Empty) ආවොත්, 
        // කිසිම Sale එකක් FAILED කරන්නේ නැහැ. මෙතනින්ම Request එක නවත්වනවා!
        if (empty($invoiceNumber) || $invoiceNumber === null) {
            Log::warning("POS_UPDATE_IGNORED: Received update request with an empty or null invoice number. No sales were modified.");
            return response()->json([
                'status' => 'ignored', 
                'message' => 'Invoice number is required. Order status preserved.'
            ], 200); // C# එකට 200 දෙනවා ලූප් එක නතර වෙන්න.
        }

        // 2. Invoice Number එකක් තිබුනොත් පමණක් ඩේටාබේස් එකේ සොයයි
        $sale = Sale::where('invoice_number', $invoiceNumber)->first();

        if ($sale) {
            // Payment status එක කියවීම
            $statusVal = $request->input('payment_status') ?? 
                         $request->input('paymentStatus') ?? 
                         $request->input('PaymentStatus') ?? 
                         $request->input('status') ?? 
                         $request->input('Status') ?? 
                         'SUCCESS';
                         
            $statusValUpper = strtoupper($statusVal);
            
            if (in_array($statusValUpper, ['SUCCESS', 'PAID', 'APPROVED', 'TRUE'])) {
                $sale->status = 'PAID';
            } elseif (in_array($statusValUpper, ['FAILED', 'DECLINED', 'CANCELLED', 'ERROR', 'FALSE'])) {
                $sale->status = 'FAILED';
            } else {
                $sale->status = 'PAID'; // fallback
            }

            // Debug කරගන්න මුළු response එකම සේව් කරනවා
            $sale->pos_response = json_encode($request->all());
            $sale->save();

            return response()->json(['status' => 'success'], 200);
        }

        Log::error("POS_UPDATE_FAILED: Invoice not found in database. Checked for: " . $invoiceNumber);
        return response()->json(['status' => 'error', 'message' => 'Invoice not found'], 442);
    }

    /**
     * GET: Fetch all sales formatted for the AJAX UI data table.
     */
    public function getSalesData()
    {
        $sales = Sale::latest()->get()->map(function($sale) {
            return [
                'id' => $sale->id,
                'created_at_formatted' => $sale->created_at->format('Y-m-d H:i:s'),
                'invoice_number' => $sale->invoice_number,
                'customer_mobile' => $sale->customer_mobile,
                'amount_formatted' => 'Rs. ' . number_format($sale->amount, 2),
                'status' => $sale->status, // 'PENDING_CARD_PAYMENT', 'PAID', 'FAILED'
                'pos_response_raw' => $sale->pos_response,
            ];
        });

        return response()->json($sales);
    }
}
