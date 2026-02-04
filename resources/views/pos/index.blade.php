<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Point of Sales') }}
        </h2>
    </x-slot>

    {{-- OTAK APLIKASI ADA DI SINI (x-data) --}}
    <div class="py-6 h-screen overflow-hidden" x-data="posSystem()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="grid grid-cols-12 gap-6 h-full">
                
                <div class="col-span-12 lg:col-span-8 flex flex-col h-full">
                    
                    <div class="mb-4 flex gap-2 overflow-x-auto pb-2">
                        <button @click="filterCategory = 'all'" 
                            :class="filterCategory == 'all' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300'"
                            class="px-4 py-2 rounded-full text-sm font-bold shadow transition">
                            All Items
                        </button>
                        @foreach($categories as $cat)
                            <button @click="filterCategory = '{{ $cat->id }}'"
                                :class="filterCategory == '{{ $cat->id }}' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300'"
                                class="px-4 py-2 rounded-full text-sm font-bold transition whitespace-nowrap">
                                {{ $cat->name }}
                            </button>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 overflow-y-auto pr-2 pb-20 custom-scrollbar">
                        @foreach($products as $product)
                            <div x-show="filterCategory == 'all' || filterCategory == '{{ $product->category_id }}'"
                                 @click="addToCart({{ $product }})"
                                 class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1 cursor-pointer group relative overflow-hidden border border-transparent hover:border-indigo-500">
                                
                                <div class="absolute top-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded backdrop-blur-sm z-10">
                                    Stok: {{ $product->stock }}
                                </div>

                                <div class="h-32 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-4xl font-bold">
                                    {{ substr($product->name, 0, 1) }}
                                </div>

                                <div class="p-4">
                                    <h3 class="font-bold text-gray-800 dark:text-white truncate">{{ $product->name }}</h3>
                                    <p class="text-indigo-600 dark:text-indigo-400 font-extrabold mt-1">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-span-12 lg:col-span-4 h-full">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl h-[80vh] flex flex-col border border-gray-100 dark:border-gray-700">
                        
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-t-2xl flex justify-between">
                            <h3 class="font-bold text-lg text-gray-800 dark:text-white">🛒 Order</h3>
                            <button @click="cart = []" class="text-red-500 text-xs hover:underline" x-show="cart.length > 0">Clear Cart</button>
                        </div>

                        <div class="flex-1 overflow-y-auto p-4 space-y-3">
                            <template x-if="cart.length === 0">
                                <div class="text-center text-gray-400 mt-10">
                                    <p class="text-4xl mb-2">🛍️</p>
                                    <p>Keranjang kosong</p>
                                </div>
                            </template>

                            <template x-for="(item, index) in cart" :key="index">
                                <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-800 dark:text-white text-sm" x-text="item.name"></h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-300">@ Rp <span x-text="formatRupiah(item.price)"></span></p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button @click="updateQty(index, -1)" class="w-6 h-6 bg-gray-200 dark:bg-gray-600 rounded text-gray-600 dark:text-white font-bold hover:bg-red-400">-</button>
                                        <span class="font-bold text-gray-800 dark:text-white w-4 text-center" x-text="item.qty"></span>
                                        <button @click="updateQty(index, 1)" class="w-6 h-6 bg-gray-200 dark:bg-gray-600 rounded text-gray-600 dark:text-white font-bold hover:bg-green-400">+</button>
                                    </div>
                                    <div class="text-right w-20">
                                        <p class="font-bold text-gray-800 dark:text-white text-sm" x-text="formatRupiah(item.price * item.qty)"></p>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="p-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 rounded-b-2xl">
                            <div class="space-y-1 mb-4">
                                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                    <span>Subtotal</span>
                                    <span>Rp <span x-text="formatRupiah(subtotal)"></span></span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                    <span>Tax (11%)</span>
                                    <span>Rp <span x-text="formatRupiah(tax)"></span></span>
                                </div>
                                <div class="flex justify-between text-xl font-bold text-gray-900 dark:text-white">
                                    <span>Total</span>
                                    <span>Rp <span x-text="formatRupiah(total)"></span></span>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-xs text-gray-500">Cash Received</label>
                                <input type="number" x-model="cashReceived" class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded p-2 text-right font-bold text-gray-800 dark:text-white" placeholder="0">
                            </div>

                            <button @click="submitTransaction()" 
                                :disabled="cart.length === 0 || cashReceived < total"
                                :class="cart.length === 0 || cashReceived < total ? 'opacity-50 cursor-not-allowed' : 'hover:bg-indigo-700'"
                                class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl shadow-lg transition transform active:scale-95 flex justify-center items-center gap-2">
                                <span x-show="!isLoading">💳 Process Payment</span>
                                <span x-show="isLoading">Processing...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT ALPINE.JS --}}
    <div id="print-area" class="hidden print:block fixed inset-0 bg-white z-50 p-4">
        <div class="max-w-[58mm] mx-auto text-xs font-mono text-black">
            <div class="text-center mb-4">
                <h1 class="text-lg font-bold">KOZI COFFEE</h1>
                <p>Jl. Dipatiukur No. 1</p>
                <p>Bandung</p>
            </div>
            
            <div class="border-b-2 border-dashed border-black my-2"></div>
            
            <div class="flex justify-between">
                <span>No: <span id="print-invoice"></span></span>
                <span id="print-date"></span>
            </div>
            <div class="flex justify-between mb-2">
                <span>Kasir: {{ Auth::user()->name }}</span>
            </div>

            <div class="border-b-2 border-dashed border-black my-2"></div>

            <div id="print-items" class="space-y-1">
                </div>

            <div class="border-b-2 border-dashed border-black my-2"></div>

            <div class="flex justify-between font-bold">
                <span>TOTAL</span>
                <span id="print-total"></span>
            </div>
            <div class="flex justify-between">
                <span>Cash</span>
                <span id="print-cash"></span>
            </div>
            <div class="flex justify-between">
                <span>Change</span>
                <span id="print-change"></span>
            </div>

            <div class="border-b-2 border-dashed border-black my-2"></div>
            
            <div class="text-center mt-4">
                <p>Terima Kasih</p>
                <p>Silahkan Datang Kembali</p>
                <p class="mt-2 text-[10px]">Layaran teknologi by Kelompok 4</p>
            </div>
        </div>
    </div>


    <script>
        function posSystem() {
            return {
                cart: [],
                filterCategory: 'all',
                cashReceived: '',
                isLoading: false,

                get subtotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                },
                get tax() {
                    return Math.floor(this.subtotal * 0.11); 
                },
                get total() {
                    return Math.ceil(this.subtotal + this.tax);
                },

                addToCart(product) {
                    let existingItem = this.cart.find(item => item.id === product.id);
                    if (existingItem) {
                        if(existingItem.qty < product.stock) {
                            existingItem.qty++;
                        } else {
                            alert('Stok habis bro!');
                        }
                    } else {
                        this.cart.push({
                            id: product.id,
                            name: product.name,
                            price: parseFloat(product.price),
                            qty: 1,
                            stock: product.stock
                        });
                    }
                },

                updateQty(index, amount) {
                    let item = this.cart[index];
                    if (amount > 0 && item.qty >= item.stock) {
                        alert('Stok mentok bos!');
                        return;
                    }
                    item.qty += amount;
                    if (item.qty <= 0) this.cart.splice(index, 1);
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID').format(number);
                },

                // --- BAGIAN INI YANG SAYA PERBAIKI BIAR INVOICE MUNCUL ---
                printReceipt(responseData) {
                    // responseData ini isinya JSON dari Controller tadi
                    
                    // 1. Masukin Data Header
                    // Ambil invoice_code dari response.data.invoice_code
                    document.getElementById('print-invoice').innerText = responseData.data.invoice_code; 
                    document.getElementById('print-date').innerText = responseData.data.date;
                    
                    // 2. Masukin Data Duit
                    document.getElementById('print-total').innerText = 'Rp ' + this.formatRupiah(responseData.data.total);
                    document.getElementById('print-cash').innerText = 'Rp ' + this.formatRupiah(responseData.data.cash);
                    document.getElementById('print-change').innerText = 'Rp ' + this.formatRupiah(responseData.data.change);

                    // 3. Render Item Belanjaan (Looping Cart yang ada di layar)
                    let itemsHtml = '';
                    this.cart.forEach(item => {
                        itemsHtml += `
                            <div class="flex justify-between">
                                <span>${item.name} x${item.qty}</span>
                                <span>${this.formatRupiah(item.price * item.qty)}</span>
                            </div>
                        `;
                    });
                    document.getElementById('print-items').innerHTML = itemsHtml;

                    // 4. Print & Reset
                    setTimeout(() => {
                        window.print();
                        
                        // Opsional: Reset cart setelah print dialog ditutup/selesai
                        // window.location.reload(); 
                    }, 500);
                },

                submitTransaction() {
                    if(!confirm('Proses transaksi ini?')) return;
                    
                    this.isLoading = true;

                    // Kirim data lengkap ke backend
                    let payload = {
                        cart: this.cart,
                        subtotal: this.subtotal,
                        tax: this.tax,
                        total_amount: this.total,
                        cash_paid: this.cashReceived
                    };

                    fetch('{{ route("pos.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.isLoading = false;
                        
                        if(data.status === 'success') {
                            // Panggil fungsi print dengan data dari backend
                            this.printReceipt(data);
                            
                            // Kosongkan cart setelah sukses (biar gak double input kalau user klik lagi)
                            this.cart = [];
                            this.cashReceived = '';
                        } else {
                            alert('Gagal: ' + data.message);
                        }
                    })
                    .catch(error => {
                        this.isLoading = false;
                        console.error('Error:', error);
                        alert('Terjadi kesalahan sistem.');
                    });
                }
            }
        }
    </script>
</x-app-layout>