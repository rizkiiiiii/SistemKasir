<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Sukses!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Create Category Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4">Tambah Kategori Baru</h3>
                    <form action="{{ route('categories.store') }}" method="POST" class="flex gap-4">
                        @csrf
                        <div class="flex-1">
                            <input type="text" name="name" required placeholder="Nama Kategori (Contoh: Kopi Dingin)"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg shadow transition">
                            Simpan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Categories List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4">Daftar Kategori</h3>
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left">Nama Kategori</th>
                                <th class="px-4 py-2 text-left">Slug</th>
                                <th class="px-4 py-2 text-center">Jumlah Produk</th>
                                <th class="px-4 py-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $cat)
                                <tr x-data="{ isEditing: false, editName: '{{ $cat->name }}' }" class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-3">
                                        <span x-show="!isEditing" class="font-bold">{{ $cat->name }}</span>
                                        <form x-show="isEditing" action="{{ route('categories.update', $cat->id) }}" method="POST" class="flex gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="name" x-model="editName" required class="py-1 px-2 text-sm rounded border-gray-300">
                                            <button type="submit" class="text-green-600 hover:text-green-800">✅</button>
                                            <button type="button" @click="isEditing = false" class="text-gray-500 hover:text-gray-700">❌</button>
                                        </form>
                                    </td>
                                    <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $cat->slug }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs font-bold">{{ $cat->products_count }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center gap-2" x-show="!isEditing">
                                            <button @click="isEditing = true" class="text-blue-600 hover:text-blue-800 bg-blue-100 p-1.5 rounded transition" title="Edit">
                                                ✏️
                                            </button>
                                            <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 bg-red-100 p-1.5 rounded transition" title="Hapus">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
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
