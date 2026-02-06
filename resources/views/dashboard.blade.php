<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ringkasan Kinerja Toko') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            


            {{-- BARIS 2: WIDGET DETAIL (Stored Proc & View) --}}
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Dashboard
    </h2>

    <!-- Cards -->
    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
        <x-stat-card title="Total Penjualan" value="Rp {{ number_format($total_revenue, 0, ',', '.') }}" icon="fas fa-coins" color="green" />
        <x-stat-card title="Total Transaksi" value="{{ $total_trx }}" icon="fas fa-shopping-cart" color="blue" />
        <x-stat-card title="Kenaikan Omzet" value="{{ $sales_growth }}%" icon="fas fa-chart-line" color="{{ $sales_growth > 0 ? 'green' : 'red' }}" />
        <x-stat-card title="Produk Stok Rendah" value="{{ count($low_stock_items) }} Item" icon="fas fa-exclamation-triangle" color="red" />
    </div>

    <!-- New Table -->
    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
                Transaksi Terbaru
            </h4>
            <!-- Placeholder for chart or table -->
            <div class="p-4 bg-white rounded-lg dark:bg-gray-800 text-gray-500 text-center">
                Belum ada data transaksi hari ini.
            </div>
        </div>
    </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2 border-b pb-2">
                        <span>🏆</span> 5 Produk Terlaris (Bulan Ini)
                    </h3>
                    <div class="space-y-4">
                        @forelse($top_products as $index => $item)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <span class="font-bold text-xl text-indigo-500">#{{ $index + 1 }}</span>
                                    <span class="font-medium text-gray-700 dark:text-gray-200">{{ $item->name }}</span>
                                </div>
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-indigo-900 dark:text-indigo-300">
                                    Terjual: {{ $item->total_sold }}
                                </span>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm text-center py-4">Belum ada data penjualan.</p>
                        @endforelse
                    </div>
                    <p class="text-[10px] text-gray-400 mt-4 text-right italic">Sumber: Stored Procedure `get_top_products_month`</p>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2 border-b pb-2">
                        <span>⚠️</span> Peringatan Stok Menipis (< 10)
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-2">Kode Barang</th>
                                    <th class="px-4 py-2">Nama Menu</th>
                                    <th class="px-4 py-2 text-right">Sisa Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($low_stock_items as $item)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-4 py-2 font-mono">{{ $item->code }}</td>
                                        <td class="px-4 py-2 font-medium text-gray-900 dark:text-white">{{ $item->name }}</td>
                                        <td class="px-4 py-2 text-right font-bold text-red-500 animate-pulse">
                                            {{ $item->stock }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-6 text-center text-green-500 font-bold">
                                            Aman bos! Stok masih banyak.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-4 text-right italic">Sumber: SQL View `view_low_stock`</p>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>