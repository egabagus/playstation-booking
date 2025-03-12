<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        return view('dashboard.master.product.index');
    }
}
