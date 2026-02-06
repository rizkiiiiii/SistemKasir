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
        $customers = \App\Models\Customer::orderBy('name')->get();

        return view('pos.index', compact('products', 'categories', 'customers'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // --- GENERATE INVOICE ---
            $dateCode = now()->format('Ymd');
            $lastTransaction = Transaction::whereDate('transaction_date', today())
                ->orderBy('id', 'desc')
                ->first();

            $newSequence = $lastTransaction
                ? (int)substr($lastTransaction->invoice_code, -3) + 1
                : 1;

            $invoice_code = 'INV-' . $dateCode . '-' . str_pad($newSequence, 3, '0', STR_PAD_LEFT);

            // Simpan Header Transaksi
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'customer_id' => $request->customer_id, // NEW: Link to Customer
                'invoice_code' => $invoice_code,
                'transaction_date' => now(),
                'subtotal' => $request->subtotal,
                'tax' => $request->tax,
                'total_amount' => $request->total_amount,
                'cash_paid' => $request->cash_paid,
                'change_returned' => $request->cash_paid - $request->total_amount,
                'payment_method' => $request->payment_method ?? 'cash',
                'status' => 'completed',
            ]);

            // --- LOYALTY POINTS LOGIC ---
            if ($request->customer_id) {
                $customer = \App\Models\Customer::find($request->customer_id);
                if ($customer) {
                    // 1 Point per Rp 10.000
                    $pointsEarned = floor($request->total_amount / 10000);
                    $customer->increment('points', $pointsEarned);
                }
            }

            // Simpan Detail & Kurangi Stok
            foreach ($request->cart as $item) {
                // LOCK PRICE Logic: Ambil modal saat ini
                $product = Product::find($item['id']);

                if (!$product) {
                    throw new \Exception("Produk dengan ID {$item['id']} tidak ditemukan.");
                }

                // Cek Stok (Manual Check for Safety)
                if ($product->stock < $item['qty']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi. Sisa: {$product->stock}");
                }

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'unit_price' => $item['price'],
                    'cost_at_time' => $product->cost_price ?? 0, // <--- CRITICAL: Save COGS
                    'subtotal' => $item['price'] * $item['qty'],
                ]);

                // Update Stok
                $product->decrement('stock', $item['qty']);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi Berhasil!',
                'data' => [
                    'invoice_code' => $transaction->invoice_code,
                    'date' => $transaction->transaction_date->format('d/m/Y H:i'),
                    'total' => $transaction->total_amount,
                    'cash' => $transaction->cash_paid,
                    'change' => $transaction->change_returned,
                ],
            ]);

        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
