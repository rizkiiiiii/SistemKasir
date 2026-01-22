<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Menu Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kode Menu (Unik)</label>
                            <input type="text" name="code" required placeholder="Contoh: K005"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Menu</label>
                            <input type="text" name="name" required placeholder="Contoh: Espresso Romano"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                            <select name="category_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga (Rp)</label>
                                <input type="number" name="price" required min="0" placeholder="15000"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stok Awal</label>
                                <input type="number" name="stock" required min="0" placeholder="100"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Foto Menu (Opsional)</label>
                            <input type="file" name="image" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg shadow transition">
                                Simpan Menu
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>