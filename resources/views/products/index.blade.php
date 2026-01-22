<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manajemen Menu') }}
            </h2>
            <a href="{{ route('products.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                + Tambah Menu Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Sukses!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left">Gambar</th>
                                <th class="px-4 py-2 text-left">Kode</th>
                                <th class="px-4 py-2 text-left">Nama Menu</th>
                                <th class="px-4 py-2 text-left">Kategori</th>
                                <th class="px-4 py-2 text-left">Stok</th>
                                <th class="px-4 py-2 text-right">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-4 py-2">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" class="w-12 h-12 object-cover rounded-lg">
                                        @else
                                            <div class="w-12 h-12 bg-indigo-500 rounded-lg flex items-center justify-center text-white font-bold">
                                                {{ substr($product->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 font-mono text-sm">{{ $product->code }}</td>
                                    <td class="px-4 py-2 font-bold">{{ $product->name }}</td>
                                    <td class="px-4 py-2">
                                        <span class="bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded text-xs">
                                            {{ $product->category->name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 {{ $product->stock < 10 ? 'text-red-500 font-bold' : '' }}">
                                        {{ $product->stock }}
                                    </td>
                                    <td class="px-4 py-2 text-right">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>