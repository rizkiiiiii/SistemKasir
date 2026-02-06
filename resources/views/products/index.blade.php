<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Menu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                 <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Daftar Menu</h3>
                 <a href="{{ route('products.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow transition transform hover:-translate-y-0.5">
                    + Tambah Menu Baru
                </a>
            </div>
            
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
                                <th class="px-4 py-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-4 py-3">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" class="w-12 h-12 object-cover rounded-xl shadow-sm">
                                        @else
                                            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-xl flex items-center justify-center text-purple-600 dark:text-purple-300 font-bold shadow-sm">
                                                {{ substr($product->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-mono text-sm text-gray-500 dark:text-gray-400">{{ $product->code }}</td>
                                    <td class="px-4 py-3 font-bold text-gray-800 dark:text-gray-200">{{ $product->name }}</td>
                                    <td class="px-4 py-3">
                                        <span class="bg-purple-50 text-purple-700 dark:bg-purple-900 dark:text-purple-300 px-3 py-1 rounded-full text-xs font-semibold border border-purple-100 dark:border-purple-800">
                                            {{ $product->category->name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            <span class="font-bold {{ $product->stock < 10 ? 'text-red-500' : 'text-gray-700 dark:text-gray-300' }}">
                                                {{ $product->stock }}
                                            </span>
                                            @if($product->stock < 10)
                                                <span class="ml-2 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right font-mono text-gray-700 dark:text-gray-300">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-center flex justify-center gap-2">
                                        <a href="{{ route('products.edit', $product->id) }}" class="text-blue-600 hover:text-blue-800 bg-blue-100 hover:bg-blue-200 p-2 rounded-lg transition" title="Edit">
                                            ✏️
                                        </a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus menu ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 bg-red-100 hover:bg-red-200 p-2 rounded-lg transition" title="Hapus">
                                                🗑️
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>