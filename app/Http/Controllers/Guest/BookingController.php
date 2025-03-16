<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BookingController extends Controller
{
    public function index()
    {
        return view('guest.booking');
    }

    public function booking()
    {
        $products = Product::all();
        return view('guest.booking-page', compact('products'));
    }

    public function data()
    {
        $data = Transaction::get();
        return $data;
    }
}
