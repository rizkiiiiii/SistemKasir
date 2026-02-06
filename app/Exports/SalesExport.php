<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Transaction::with('cashier')->completed()->get();
    }

    public function headings(): array
    {
        return [
            'Invoice Code',
            'Date',
            'Cashier',
            'Subtotal',
            'Tax',
            'Total Amount',
            'Payment Method',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->invoice_code,
            $transaction->transaction_date->format('d/m/Y H:i'),
            $transaction->cashier->name ?? 'System',
            $transaction->subtotal,
            $transaction->tax,
            $transaction->total_amount,
            ucfirst($transaction->payment_method),
        ];
    }
}
