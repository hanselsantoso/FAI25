<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => 124,
            'posts' => 37,
            'sales' => 542,
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function products()
    {
        // prices are in Indonesian Rupiah (IDR)
        $products = [
            ['id' => 1, 'name' => 'Product A', 'price' => 12500, 'stock' => 10],
            ['id' => 2, 'name' => 'Product B', 'price' => 8000, 'stock' => 5],
        ];

        return view('admin.products.index', compact('products'));
    }

    public function categories()
    {
        $categories = [
            ['id' => 1, 'name' => 'Electronics'],
            ['id' => 2, 'name' => 'Apparel'],
        ];

        return view('admin.categories.index', compact('categories'));
    }

    public function suppliers()
    {
        $suppliers = [
            ['id' => 1, 'name' => 'Supplier X', 'contact' => 'supplierx@example.com'],
            ['id' => 2, 'name' => 'Supplier Y', 'contact' => 'suppliery@example.com'],
        ];

        return view('admin.suppliers.index', compact('suppliers'));
    }
}
