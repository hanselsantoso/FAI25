<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductStockController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['category', 'supplier'])->orderBy('name')->get();

        return view('admin.warehouse.products', compact('products'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $product->update([
            'stock' => $data['stock'],
        ]);

        return redirect()
            ->route('admin.warehouse.products.index')
            ->with('success', "Stok {$product->name} diperbarui menjadi {$data['stock']}.");
    }
}
