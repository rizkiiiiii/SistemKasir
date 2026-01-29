<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data Standard (Eloquent Biasa)
        $total_revenue = Transaction::whereDate('transaction_date', today())->sum('total_amount');
        $total_trx     = Transaction::whereDate('transaction_date', today())->count();

        // 2. Panggil STORED FUNCTION (VERSI INDO)
        // Dulu: get_sales_growth() -> Sekarang: sf_cek_kenaikan_omzet()
        $sales_growth = DB::select("SELECT sf_cek_kenaikan_omzet() as growth")[0]->growth;

        // 3. Panggil VIEW (VERSI INDO)
        // Dulu: view_low_stock -> Sekarang: view_stok_menipis
        $low_stock_items = DB::select("SELECT * FROM view_stok_menipis");

        // 4. Panggil STORED PROCEDURE (VERSI INDO)
        // Dulu: get_top_products_month() -> Sekarang: sp_produk_terlaris()
        $top_products = DB::select("CALL sp_produk_terlaris()");

        return view('dashboard', compact(
            'total_revenue',
            'total_trx',
            'sales_growth',
            'low_stock_items',
            'top_products'
        ));
    }
}
