<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        // Ambil semua produk yang stoknya ada, urutkan dari yang terbaru
        $products = Product::with('category')
            ->where('stock', '>', 0)
            ->latest()
            ->get();

        // Ambil semua kategori buat filter nanti
        $categories = Category::all();

        return view('pos.index', compact('products', 'categories'));
    }
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'cart'      => 'required|array',
            'cash_paid' => 'required|numeric',
        ]);

        // Hitung ulang di backend (Jangan percaya frontend 100%)
        $subtotal = 0;
        foreach ($request->cart as $item) {
            $subtotal += $item['price'] * $item['qty'];
        }
        $tax          = $subtotal * 0.11; // 11%
        $total_amount = ceil($subtotal + $tax);
        $change       = $request->cash_paid - $total_amount;

        if ($change < 0) {
            return response()->json(['status' => 'error', 'message' => 'Uang kurang bos!'], 400);
        }

        // 2. Simpan Transaksi (Pake DB Transaction biar aman)
        try {
            DB::beginTransaction();

            // Panggil Stored Function buat generate Invoice Code
            // Note: Kita panggil raw select biar function SQL jalan
            $invoiceCode = DB::select('SELECT generate_invoice_code() as code')[0]->code;

            // Simpan Header
            $trx = Transaction::create([
                'user_id'          => auth()->id(), // Siapa yg login
                'invoice_code'     => $invoiceCode,
                'transaction_date' => now(),
                'subtotal'         => $subtotal,
                'tax'              => $tax,
                'total_amount'     => $total_amount,
                'cash_paid'        => $request->cash_paid,
                'change_returned'  => $change,
                'payment_method'   => $request->payment_method,
                'status'           => 'completed',
            ]);

            // Simpan Detail
            foreach ($request->cart as $item) {
                TransactionDetail::create([
                    'transaction_id' => $trx->id,
                    'product_id'     => $item['id'],
                    'quantity'       => $item['qty'],
                    'unit_price'     => $item['price'],
                    'subtotal'       => $item['price'] * $item['qty'],
                ]);
                // TRIGGER OTOMATIS JALAN DI SINI (Stok berkurang sendiri)
            }

            DB::commit();
            return response()->json(['status' => 'success', 'change' => $change]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
