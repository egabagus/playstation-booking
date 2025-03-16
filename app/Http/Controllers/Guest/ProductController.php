<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function data()
    {
        $products = Product::all()->map(function ($product) {
            return [
                'id' => $product->id,
                'title' => $product->name
            ];
        });

        return response()->json($products);
    }
}
