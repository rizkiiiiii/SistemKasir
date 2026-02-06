<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .total { font-weight: bold; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Penjualan</h2>
        <p>Tanggal Cetak: {{ date('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Metode</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $trx)
            <tr>
                <td>{{ $trx->invoice_code }}</td>
                <td>{{ $trx->transaction_date->format('d/m/Y H:i') }}</td>
                <td>{{ $trx->cashier->name ?? 'System' }}</td>
                <td>{{ ucfirst($trx->payment_method) }}</td>
                <td style="text-align: right">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="total">GRAND TOTAL</td>
                <td class="total">Rp {{ number_format($transactions->sum('total_amount'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
