<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ringkasan Kinerja Toko') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- BARIS 1: KARTU STATISTIK (Aggregate Function & Stored Function) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Pendapatan Hari Ini</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($total_revenue, 0, ',', '.') }}
                    </div>
                    <div class="mt-2 text-xs">
                        <span class="{{ str_contains($sales_growth, '+') ? 'text-green-500' : 'text-red-500' }} font-bold">
                            {{ $sales_growth }}
                        </span> 
                        dibanding Kemarin
                        <span class="block text-[10px] text-gray-400 mt-1 italic">(Sumber Stored Function: `get_sales_growth`)</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Transaksi</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $total_trx }}
                    </div>
                    <div class="mt-2 text-xs text-gray-400">
                        Nota tercetak hari ini
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Menu Aktif</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ \App\Models\Product::count() }}
                    </div>
                    <div class="mt-2 text-xs text-gray-400">
                        Item tersedia di database
                    </div>
                </div>
            </div>

            {{-- BARIS 2: WIDGET DETAIL (Stored Proc & View) --}}
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