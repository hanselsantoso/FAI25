<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\{Product, Category, Supplier};

class ProductController extends Controller
{
    // protected $sessionKey = 'products'; // OLD session storage (deprecated)

    /**
     * Display a listing of products with related category & supplier.
     */
    public function index(Request $request)
    {
        // OLD session
        // $products = session($this->sessionKey, []);

        // ACTIVE Eloquent with relationships eager-loaded
        $products = Product::with(['category', 'supplier'])->orderByDesc('id')->get();

        /* Query Builder (no Eloquent relationships) example
        $products = DB::table('products as p')
            ->leftJoin('categories as c', 'c.id', '=', 'p.category_id')
            ->leftJoin('suppliers as s', 's.id', '=', 'p.supplier_id')
            ->select('p.*', 'c.name as category_name', 's.name as supplier_name')
            ->orderByDesc('p.id')
            ->get();
        */

        /* Raw SQL example
        $products = DB::select('SELECT p.*, c.name as category_name, s.name as supplier_name
            FROM products p
            LEFT JOIN categories c ON c.id = p.category_id
            LEFT JOIN suppliers s ON s.id = p.supplier_id
            ORDER BY p.id DESC');
        */

        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'suppliers'));
    }

    /**
     * Store a new product.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/products', 'public');
        }

        $data['image_path'] = $imagePath;

        Product::create($data);

        /* Query Builder
        DB::table('products')->insert($data + ['created_at' => now(), 'updated_at' => now()]);
        */

        /* Raw SQL
        DB::insert('INSERT INTO products (name, price, stock, category_id, supplier_id, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?)', [
            $data['name'], $data['price'], $data['stock'], $data['category_id'], $data['supplier_id'], now(), now()
        ]);
        */

        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $product = Product::with(['category', 'supplier'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $products = Product::with(['category', 'supplier'])->orderByDesc('id')->get();
        $edit = Product::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        return view('admin.products.index', compact('products', 'edit', 'categories', 'suppliers'));
    }

    /**
     * Update product.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $product = Product::findOrFail($id);

        $imagePath = $product->image_path;

        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            $imagePath = $request->file('image')->store('uploads/products', 'public');
        }

        $data['image_path'] = $imagePath;

        $product->update($data);

        /* Query Builder
        DB::table('products')->where('id', $id)->update($data + ['updated_at' => now()]);
        */

        /* Raw SQL
        DB::update('UPDATE products SET name=?, price=?, stock=?, category_id=?, supplier_id=?, updated_at=? WHERE id=?', [
            $data['name'], $data['price'], $data['stock'], $data['category_id'], $data['supplier_id'], now(), $id
        ]);
        */

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    /**
     * Remove product.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        /* Query Builder
        DB::table('products')->where('id', $id)->delete();
        */

        /* Raw SQL
        DB::delete('DELETE FROM products WHERE id = ?', [$id]);
        */

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }
}
