<?php
namespace App\Http\Controllers;

use App\Models\Category; // <--- WAJIB ADA
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        // 1. BALIKIN LOGIC LAMA (BIAR TIDAK ERROR VARIABLE $CATEGORIES)
        $products = Product::with('category')
            ->where('stock', '>', 0)
            ->latest()
            ->get();

        // INI DIA YANG TADI HILANG
        $categories = Category::all();

        return view('pos.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // --- GENERATE INVOICE ---
            $dateCode = now()->format('Ymd');

            // Cari transaksi terakhir hari ini
            $lastTransaction = Transaction::whereDate('transaction_date', today())
                ->orderBy('id', 'desc')
                ->first();

            if ($lastTransaction) {
                // Format: INV-YYYYMMDD-XXX
                $lastInvoiceCode = $lastTransaction->invoice_code;
                $lastSequence    = (int) substr($lastInvoiceCode, -3);
                $newSequence     = $lastSequence + 1;
            } else {
                $newSequence = 1;
            }
            $invoice_code = 'INV-' . $dateCode . '-' . str_pad($newSequence, 3, '0', STR_PAD_LEFT);
            
            // Simpan Header Transaksi
            $transaction = Transaction::create([
                'user_id'          => auth()->id(),
                'invoice_code'     => $invoice_code,
                'transaction_date' => now(),
                'subtotal'         => $request->subtotal,
                'tax'              => $request->tax,
                'total_amount'     => $request->total_amount,
                'cash_paid'        => $request->cash_paid,
                'change_returned'  => $request->cash_paid - $request->total_amount, // Hitung kembalian di server
                'payment_method'   => 'cash',
                'status'           => 'completed',
            ]);

            // Simpan Detail & Kurangi Stok
            foreach ($request->cart as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['id'],
                    'quantity'       => $item['qty'],
                    'unit_price'     => $item['price'], 
                    'subtotal'       => $item['price'] * $item['qty'],
                ]);

                // Update Stok
                $product = Product::find($item['id']);
                if ($product) {
                    $product->decrement('stock', $item['qty']);
                }
            }

            DB::commit();

            // KIRIM DATA KE FRONTEND 
            return response()->json([
                'status'  => 'success',
                'message' => 'Transaksi Berhasil!',
                'data'    => [
                    'invoice_code' => $transaction->invoice_code,
                    'date'         => $transaction->transaction_date->format('d/m/Y H:i'),
                    'total'        => $transaction->total_amount,
                    'cash'         => $transaction->cash_paid,
                    'change'       => $transaction->change_returned,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
