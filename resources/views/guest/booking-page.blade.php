@extends('livewire.layout.guest-layout')

@section('content')
<livewire:guest.product-booking />
    {{-- <div class="text-3xl font-bold">Booking Playstation</div>

    <div>
        <div class="grid grid-cols-5 gap-5">
            <div class="product-wrapper col-span-3">
                @foreach ($products as $product)
                    <div class="product w-full p-6 border rounded-2xl my-4 flex items-center bg-white hover:shadow-xl transition duration-300 ease-in-out">
                        <div class="">
                            <img class="w-[150px]" src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}">
                        </div>
                        <div class="ms-5">
                            <div class="text-xl font-bold">{{$product->name}}</div>
                            <div class="mt-2">Rp. {{ number_format($product->price, 0, ',', '.') }} / jam</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="form-wrapper col-span-2 bg-white rounded-2xl mt-4 p-6">
                FORM
            </div>
        </div>
    </div> --}}
@endsection