<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // 1. Panggil VIEW (Dashboard Summary)
        // Ini nampilin total transaksi & omzet per kasir hari ini
        $summary = DB::select('SELECT * FROM view_dashboard_summary');

        // 2. Panggil STORED PROCEDURE (Daily Sales Report)
        // Kita ambil tanggal hari ini
        $today = date('Y-m-d');
        $details = DB::select("CALL get_daily_sales_report(?)", [$today]);

        return view('reports.index', compact('summary', 'details'));
    }

    public function exportExcel()
    {
        return Excel::download(new SalesExport, 'laporan-penjualan-' . date('Y-m-d') . '.xlsx');
    }

    // PDF Export (Using simple DOMPDF view)
    public function exportPdf()
    {
        $transactions = \App\Models\Transaction::with('cashier')->completed()->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf', compact('transactions'));
        return $pdf->download('laporan-penjualan.pdf');
    }
}
