<div class="grid grid-cols-5 gap-5">
    {{-- Daftar Produk --}}
    <div class="product-wrapper col-span-3">
        @foreach ($products as $product)
            <div wire:click="selectProduct({{ $product->id }})"
                 class="cursor-pointer product w-full p-6 border rounded-2xl my-4 flex items-center bg-white hover:shadow-xl transition duration-300 ease-in-out 
                 {{ $selectedProduct && $selectedProduct->id === $product->id ? 'shadow-xl border-blue-500' : '' }}">
                <div>
                    <img class="w-[150px]" src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}">
                </div>
                <div class="ms-5">
                    <div class="text-xl font-bold">{{ $product->name }}</div>
                    <div class="mt-2">Rp. {{ number_format($product->price, 0, ',', '.') }} / jam</div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Form Booking --}}
    <div class="col-span-2">
        {{-- <div wire:loading wire:target="bookProduct" class="w-full bg-gray-200 rounded-full h-2.5">
            <div class="bg-blue-600 h-2.5 rounded-full animate-pulse" style="width: 100%"></div>
        </div> --}}
        <div class="form-wrapper bg-white rounded-2xl mt-4 p-6 border">
            @if ($selectedProduct)
                <h2 class="text-xl font-bold">{{ $selectedProduct->name }}</h2>
                <p class="text-gray-700 mt-2">Harga: Rp. {{ number_format($selectedProduct->price, 0, ',', '.') }} / jam</p>
    
                <form wire:submit.prevent="bookProduct" class="mt-4">
                    {{-- Nama --}}
                    <div class="mt-4">
                        <label class="block text-sm font-medium">Nama</label>
                        <input type="text" wire:model="customer_name" class="w-full p-2 border rounded-lg mt-2">
                        @error('customer_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="mt-4">
                        <label class="block text-sm font-medium">Nomor Handphone</label>
                        <input type="text" wire:model="customer_email" class="w-full p-2 border rounded-lg mt-2">
                        @error('customer_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    {{-- Tanggal Booking --}}
                    <div class="mt-4">
                        <label class="block text-sm font-medium">Tanggal Booking</label>
                        <input type="date" wire:model="booking_date" class="w-full p-2 border rounded-lg mt-2">
                        @error('booking_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    {{-- Pilih Jam --}}
                    <div class="mt-4">
                        <label class="block text-sm font-medium">Pilih Jam</label>
                        <div class="grid grid-cols-4 gap-2 mt-2">
                            @foreach (range(8, 16) as $hour)
                                <button type="button"
                                        wire:click="toggleHour({{ $hour }})"
                                        class="py-2 px-4 border rounded-lg text-center 
                                            {{ in_array($hour, $selected_hours) ? 'bg-blue-600 text-white' : 'bg-gray-100 hover:bg-gray-200' }}">
                                    {{ sprintf('%02d:00', $hour) }}
                                </button>
                            @endforeach
                        </div>
                        @error('selected_hours') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    {{-- Total Harga --}}
                    <div class="mt-4">
                        <p class="text-lg font-semibold">
                            Total Harga: <span class="text-blue-600">Rp. {{ number_format($totalAmount, 0, ',', '.') }}</span>
                        </p>
                        @if ($booking_date && $this->isWeekend($booking_date))
                            <p class="text-sm text-red-500">* Weekend (Sabtu/Minggu): +Rp. 50.000</p>
                        @endif
                    </div>


                    {{-- Submit Button --}}
                    @if (!$isBooked)
                        <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Booking Sekarang
                        </button>
                    @else
                    {{-- @dd($snapToken) --}}
                        <button onclick="payWithMidtrans({{ $bookingId }})" class="mt-4 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Bayar Sekarang</button>
                    @endif
                </form>
            @else
                <p class="text-gray-500">Silakan pilih produk untuk booking.</p>
            @endif
        </div>
    </div>
</div>
@livewireScripts

@push('scripts')
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
    <script>
        // document.addEventListener("livewire:load", function() {
        // window.addEventListener('midtransPayment', event => {
        //     console.log('pay... ' + event.detail)
        //     snap.pay(event.detail.snapToken, {
        //         onSuccess: function(result) {
        //             Livewire.emit('paymentSuccess', result);
        //         },
        //         onPending: function(result) {
        //             alert("Menunggu pembayaran!");
        //         },
        //         onError: function(result) {
        //             alert("Transaksi gagal!");
        //         }
        //     });
        // });
    // });
    function payWithMidtrans(bookingId) {
        fetch(`/get-snap-token/${bookingId}`)
        .then(response => response.json())
        .then(data => {
            if (data.snapToken) {
                console.log("Snap Token:", data.snapToken);

                snap.pay(data.snapToken, {
                    onSuccess: function(result) {
                        Livewire.emit('paymentSuccess', result);
                    },
                    onPending: function(result) {
                        alert("Menunggu pembayaran!");
                    },
                    onError: function(result) {
                        alert("Transaksi gagal!");
                    }
                });
            } else {
                alert("Token pembayaran tidak ditemukan.");
            }
        })
        .catch(error => console.error('Error:', error));
    }
    </script>
@endpush
