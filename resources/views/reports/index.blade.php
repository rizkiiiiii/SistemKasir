<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Harian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4 text-indigo-500">📊 Ringkasan Omzet (SQL VIEW)</h3>
                    <p class="text-sm text-gray-500 mb-4">Data ini diambil langsung dari <code>view_dashboard_summary</code></p>

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left">Tanggal</th>
                                    <th class="px-4 py-2 text-left">Nama Kasir</th>
                                    <th class="px-4 py-2 text-right">Total Transaksi</th>
                                    <th class="px-4 py-2 text-right">Total Omzet</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($summary as $row)
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="px-4 py-2">{{ $row->tgl }}</td>
                                        <td class="px-4 py-2 font-bold">{{ $row->kasir }}</td>
                                        <td class="px-4 py-2 text-right">{{ $row->total_transaksi }}</td>
                                        <td class="px-4 py-2 text-right font-mono text-green-600">
                                            Rp {{ number_format($row->omzet, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-4 text-center text-gray-500">Belum ada data transaksi completed.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4 text-purple-500">☕ Penjualan Produk Hari Ini (STORED PROCEDURE)</h3>
                    <p class="text-sm text-gray-500 mb-4">Data ini dieksekusi via <code>CALL get_daily_sales_report('{{ date('Y-m-d') }}')</code></p>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($details as $item)
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl flex justify-between items-center">
                                <div>
                                    <h4 class="font-bold text-lg">{{ $item->product_name }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-300">Terjual: {{ $item->total_qty }} pcs</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-400">Total Revenue</p>
                                    <p class="font-bold text-indigo-400">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-10 text-gray-500">
                                Belum ada item terjual hari ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>