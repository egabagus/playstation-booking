<?php

namespace App\Livewire\Guest;

use App\Models\Product;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Services\Transactions\TransactionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Throwable;

class ProductBooking extends Component
{
    public $products;
    public $selectedProduct;
    public $customer_name, $customer_email, $booking_date, $selected_hours = [];
    public $totalPrice = 0;
    public $totalAmount = 0;
    public $totalHours = 0;
    public $surcharge = 0;
    public $snapToken;
    public $isBooked = false;
    public $bookingId;

    public function mount()
    {
        $this->products = Product::all();
        $this->selectedProduct = null;
    }

    public function selectProduct($productId)
    {
        $this->selectedProduct = Product::find($productId);
        $this->selected_hours = []; // Reset pilihan jam saat produk berubah
        $this->calculateTotalPrice();
    }

    public function toggleHour($hour)
    {
        if (in_array($hour, $this->selected_hours)) {
            $this->selected_hours = array_diff($this->selected_hours, [$hour]); // Hapus jika sudah dipilih
        } else {
            $this->selected_hours[] = $hour; // Tambahkan ke daftar pilihan
        }

        $this->calculateTotalPrice();
    }

    public function updatedBookingDate()
    {
        $this->calculateTotalPrice();
    }

    public function calculateTotalPrice()
    {
        if (!$this->selectedProduct || empty($this->selected_hours) || !$this->booking_date) {
            $this->totalPrice = 0;
            return;
        }

        $basePrice = $this->selectedProduct->price;
        $this->totalHours = count($this->selected_hours);
        $this->totalPrice = $basePrice * $this->totalHours;

        // Tambah Rp. 50.000 jika weekend
        if ($this->isWeekend($this->booking_date)) {
            $this->surcharge = 50000;
        }

        $this->totalAmount = $this->totalPrice + $this->surcharge;
    }

    private function isWeekend($date)
    {
        $dayOfWeek = Carbon::parse($date)->dayOfWeek; // 6 = Sabtu, 0 = Minggu
        return in_array($dayOfWeek, [6, 0]);
    }

    public function bookProduct()
    {
        $this->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'booking_date' => 'required|date|after:yesterday',
            'selected_hours' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {

            $tenant = Tenant::create([
                'name' => $this->customer_name,
                'email' => $this->customer_email ?? ''
            ]);

            $booking = Transaction::create([
                'tenant_id' => $tenant->id,
                'date' => $this->booking_date,
                'amount' => $this->totalPrice,
                'total_hour' => $this->totalHours,
                'surcharge' => $this->surcharge,
                'total_amount' => $this->totalAmount
            ]);

            foreach ($this->selected_hours as $hour) {
                $details = $booking->details()->create([
                    'transaction_id' => $booking->id,
                    'product_id' => $this->selectedProduct->id,
                    'time' => Carbon::parse("$this->booking_date $hour:00"),
                    'amount' => $this->selectedProduct->price
                ]);
            }

            $payload = [
                'customer_name' => $this->customer_name,
                'customer_email' => $this->customer_email,
                'product' => $this->selectedProduct,
                'amount' => $this->totalAmount
            ];

            $this->snapToken = TransactionService::pay($booking, $payload);
            $this->bookingId = $booking->id;

            if ($this->snapToken) {
                $this->isBooked = true;
            }

            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function render()
    {
        // return view('livewire.guest.product-booking');

        return view('livewire.guest.product-booking', [
            'snapToken' => $this->snapToken, // Kirim snapToken ke frontend
        ]);
    }
}
