<x-app-layout>
    <div class="h-[calc(100vh-65px)] overflow-hidden bg-blue-50 dark:bg-gray-900" x-data="posSystem()">
        <div class="flex h-full">
            
            {{-- LEFT SECTION: PRODUCTS --}}
            <div class="flex-1 flex flex-col h-full overflow-hidden">
                {{-- TOP BAR: Search & Filter --}}
                <div class="px-6 py-4 bg-white dark:bg-gray-800 shadow-sm z-10 flex gap-4 items-center justify-between">
                    <div class="relative w-96">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input x-model="searchQuery" type="text" class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" placeholder="Cari menu...">
                    </div>
                    
                    <div class="flex gap-2 overflow-x-auto no-scrollbar">
                        <button @click="filterCategory = 'all'" 
                            :class="filterCategory === 'all' ? 'bg-purple-600 text-white shadow-purple-200' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'"
                            class="px-4 py-2 rounded-xl text-sm font-semibold shadow-sm transition-all duration-200 whitespace-nowrap">
                            🔹 Semua
                        </button>
                        @foreach($categories as $cat)
                            <button @click="filterCategory = '{{ $cat->id }}'"
                                :class="filterCategory == '{{ $cat->id }}' ? 'bg-purple-600 text-white shadow-purple-200' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'"
                                class="px-4 py-2 rounded-xl text-sm font-semibold shadow-sm transition-all duration-200 whitespace-nowrap">
                                {{ $cat->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- PRODUCT GRID --}}
                <div class="flex-1 overflow-y-auto p-6 scroll-smooth">
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6 pb-20">
                        @foreach($products as $product)
                            <div x-show="(filterCategory === 'all' || filterCategory == '{{ $product->category_id }}') && ('{{ strtolower($product->name) }}'.includes(searchQuery.toLowerCase()))"
                                @click="addToCart({{ $product }})"
                                class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer overflow-hidden border border-gray-100 dark:border-gray-700 relative">
                                
                                {{-- Stock Badge --}}
                                <div class="absolute top-3 right-3 z-10">
                                    <span class="px-2 py-1 text-xs font-bold rounded-lg backdrop-blur-md {{ $product->stock < 10 ? 'bg-red-500/90 text-white' : 'bg-gray-900/60 text-white' }}">
                                        {{ $product->stock }} Left
                                    </span>
                                </div>

                                {{-- Image/Placeholder --}}
                                <div class="h-36 w-full bg-gradient-to-br from-purple-100 to-indigo-50 flex items-center justify-center group-hover:scale-105 transition-transform duration-500">
                                    @if($product->image)
                                        <img src="{{ asset('storage/'.$product->image) }}" class="h-full w-full object-cover">
                                    @else
                                        <span class="text-4xl">☕</span>
                                    @endif
                                </div>

                                <div class="p-4">
                                    <h3 class="font-bold text-gray-800 dark:text-gray-100 text-lg leading-tight line-clamp-1">{{ $product->name }}</h3>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                </div>
                                
                                {{-- Add Overlay --}}
                                <div class="absolute inset-0 bg-purple-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- RIGHT SECTION: CART --}}
            <div class="fixed inset-y-0 right-0 w-96 bg-white dark:bg-gray-800 border-l border-gray-200 dark:border-gray-700 shadow-2xl flex flex-col z-30 transition-transform duration-300 transform"
                 :class="isCartOpen ? 'translate-x-0' : 'translate-x-full'">
                
                {{-- Toggle Handle (Visible when closed) --}}
                <div class="absolute -left-12 top-1/2 cursor-pointer bg-purple-600 text-white p-3 rounded-l-xl shadow-lg hover:bg-purple-700 transition"
                     @click="isCartOpen = !isCartOpen" title="Toggle Cart">
                     <svg x-show="!isCartOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                     <svg x-show="isCartOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>

                {{-- Cart Header --}}
                <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800">
                    <div>
                        <h2 class="font-bold text-xl text-gray-800 dark:text-white">Current Order</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Transaction ID: <span class="font-mono text-purple-600">#NEW</span></p>
                    </div>
                    <button @click="cart = []" x-show="cart.length > 0" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition" title="Clear All">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>

                {{-- Cart Items --}}
                <div class="flex-1 overflow-y-auto p-4 space-y-3 scroll-thin">
                    <template x-if="cart.length === 0">
                        <div class="h-full flex flex-col items-center justify-center text-gray-400 opacity-60">
                            <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <p class="font-medium">No items added yet</p>
                        </div>
                    </template>

                    <template x-for="(item, index) in cart" :key="index">
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 dark:bg-gray-700/50 group hover:bg-white hover:shadow-md transition-all border border-transparent hover:border-gray-100">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-gray-800 dark:text-gray-100 truncate" x-text="item.name"></h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs font-mono text-gray-500">Rp <span x-text="formatRupiah(item.price)"></span></span>
                                </div>
                            </div>
                            
                            {{-- Qty Controls --}}
                            <div class="flex items-center bg-white dark:bg-gray-600 rounded-lg border border-gray-200 dark:border-gray-500 h-8">
                                <button @click="updateQty(index, -1)" class="w-8 h-full flex items-center justify-center text-gray-500 hover:text-red-500 hover:bg-red-50 rounded-l-lg transition">-</button>
                                <span class="w-8 text-center text-sm font-bold text-gray-700 dark:text-white" x-text="item.qty"></span>
                                <button @click="updateQty(index, 1)" class="w-8 h-full flex items-center justify-center text-gray-500 hover:text-green-500 hover:bg-green-50 rounded-r-lg transition">+</button>
                            </div>
                            
                            <div class="text-right w-16">
                                <span class="font-bold text-sm text-gray-800 dark:text-gray-100">Rp <span x-text="formatRupiah(item.price * item.qty, true)"></span></span>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Summary Section --}}
                <div class="p-6 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-30">
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-gray-500 text-sm">
                            <span>Subtotal</span>
                            <span class="font-mono">Rp <span x-text="formatRupiah(subtotal)"></span></span>
                        </div>
                        <div class="flex justify-between text-gray-500 text-sm">
                            <span>Tax (11%)</span>
                            <span class="font-mono">Rp <span x-text="formatRupiah(tax)"></span></span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-dashed border-gray-200">
                            <span class="font-bold text-lg text-gray-800 dark:text-white">Total</span>
                            <span class="font-bold text-2xl text-purple-600">Rp <span x-text="formatRupiah(total)"></span></span>
                        </div>
                    </div>

                    {{-- Customer Select --}}
                    <div class="mb-4">
                        <label class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-1 block">Pilih Member (Opsional)</label>
                        <select x-model="customerId" class="w-full rounded-xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 focus:ring-purple-500">
                            <option value="">-- Tamu (Non-Member) --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->points }} pts)</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Member dapat 1 Poin tiap Rp 10.000</p>
                    </div>

                    {{-- Payment Input --}}
                    <div class="mb-4 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-bold">Rp</span>
                        </div>
                        <input type="number" x-model="cashReceived" 
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:bg-white transition text-lg font-bold text-gray-800"
                            placeholder="Input Cash Amount...">
                    </div>

                    <button @click="submitTransaction()" 
                        :disabled="cart.length === 0 || cashReceived < total || isLoading"
                        :class="(cart.length === 0 || cashReceived < total) ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-purple-600 hover:bg-purple-700 text-white shadow-lg shadow-purple-200 transform hover:-translate-y-0.5'"
                        class="w-full py-4 rounded-xl font-bold text-lg transition-all flex justify-center items-center gap-2">
                        <span x-show="!isLoading">Confirm Payment</span>
                        <span x-show="isLoading" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- HIDDEN PRINT AREA --}}
    <div id="print-area" class="hidden print:block fixed inset-0 bg-white z-50 p-8 flex justify-center">
        <div class="w-[80mm] font-mono text-xs text-black">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-black tracking-widest uppercase mb-1">COZY POS</h1>
                <p class="text-gray-600">Jl. Teknologi No. 404, Cyber City</p>
                <p class="text-gray-600">Telp: 0812-3456-7890</p>
            </div>
            
            <div class="border-b-2 border-black my-4"></div>
            
            <div class="flex justify-between mb-1">
                <span>Invoice</span>
                <span class="font-bold" id="print-invoice">INV-001</span>
            </div>
            <div class="flex justify-between mb-4">
                <span>Date</span>
                <span id="print-date">01/01/2026 12:00</span>
            </div>

            <div class="flex flex-col gap-2 mb-4" id="print-items">
                <!-- Items injected here -->
            </div>

            <div class="border-t border-dashed border-black my-2 pt-2 space-y-1">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span id="print-total">0</span>
                </div>
                <div class="flex justify-between font-bold text-sm mt-2">
                    <span>GRAND TOTAL</span>
                    <span id="print-grand-total">0</span>
                </div>
                <div class="flex justify-between mt-2">
                    <span>Cash</span>
                    <span id="print-cash">0</span>
                </div>
                <div class="flex justify-between">
                    <span>Change</span>
                    <span id="print-change">0</span>
                </div>
            </div>

            <div class="border-t-2 border-black my-6"></div>
            
            <div class="text-center space-y-1">
                <p class="font-bold">THANK YOU FOR VISITING!</p>
                <p>Follow us @cozy.coffee</p>
                <p class="text-[10px] mt-4 text-gray-400">System by Antigravity</p>
            </div>
        </div>
    </div>

    <script>
        function posSystem() {
            return {
                cart: [],
                filterCategory: 'all',
                searchQuery: '',
                cashReceived: '',
                customerId: '', // NEW
                isLoading: false,
                isCartOpen: true, // Default Open

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
                            // Shake animation or toast could go here
                            alert('Stok tidak mencukupi!');
                        }
                    } else {
                        if(product.stock > 0) {
                            this.cart.push({
                                id: product.id,
                                name: product.name,
                                price: parseFloat(product.price),
                                qty: 1,
                                stock: product.stock
                            });
                        } else {
                            alert('Produk Habis!');
                        }
                    }
                },

                updateQty(index, amount) {
                    let item = this.cart[index];
                    if (amount > 0 && item.qty >= item.stock) {
                        return; // Max stock reached
                    }
                    item.qty += amount;
                    if (item.qty <= 0) this.cart.splice(index, 1);
                },

                formatRupiah(number, short = false) {
                    return new Intl.NumberFormat('id-ID').format(number);
                },

                printReceipt(responseData) {
                    document.getElementById('print-invoice').innerText = responseData.data.invoice_code; 
                    document.getElementById('print-date').innerText = responseData.data.date;
                    
                    document.getElementById('print-total').innerText = this.formatRupiah(responseData.data.total); // Using total for subtotal placeholder if needed, or fix logic
                    document.getElementById('print-grand-total').innerText = 'Rp ' + this.formatRupiah(responseData.data.total);
                    document.getElementById('print-cash').innerText = 'Rp ' + this.formatRupiah(responseData.data.cash);
                    document.getElementById('print-change').innerText = 'Rp ' + this.formatRupiah(responseData.data.change);

                    let itemsHtml = '';
                    this.cart.forEach(item => {
                        itemsHtml += `
                            <div class="flex justify-between items-start">
                                <div class="w-2/3">
                                    <span class="font-bold block">${item.name}</span>
                                    <span class="text-[10px] text-gray-500">${item.qty} x ${this.formatRupiah(item.price)}</span>
                                </div>
                                <span class="font-mono">${this.formatRupiah(item.price * item.qty)}</span>
                            </div>
                        `;
                    });
                    document.getElementById('print-items').innerHTML = itemsHtml;

                    setTimeout(() => {
                        window.print();
                        this.cart = [];
                        this.cashReceived = '';
                    }, 300);
                },

                submitTransaction() {
                    if(!confirm('Konfirmasi pembayaran?')) return;
                    
                    this.isLoading = true;

                    let payload = {
                        cart: this.cart,
                        customer_id: this.customerId, // NEW
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
                            this.printReceipt(data);
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