<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // 1. Panggil VIEW (Dashboard Summary)
        // Ini nampilin total transaksi & omzet per kasir hari ini
        $summary = DB::select('SELECT * FROM view_dashboard_summary');

        // 2. Panggil STORED PROCEDURE (Daily Sales Report)
        // Kita ambil tanggal hari ini
        $today   = date('Y-m-d');
        $details = DB::select("CALL get_daily_sales_report(?)", [$today]);

        return view('reports.index', compact('summary', 'details'));
    }
}
