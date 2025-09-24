<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategoryController extends Controller
{
    // protected $sessionKey = 'categories'; // OLD session key (deprecated)

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // ACTIVE: Eloquent (recommended) with eager loading products for grouping
        $categories = Category::with('products')->orderByDesc('id')->get();

        /* Query Builder alternative
        $categories = DB::table('categories')->orderByDesc('id')->get();
        */

        /* Raw SQL alternative
        $categories = DB::select('SELECT * FROM categories ORDER BY id DESC');
        */

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'Category created.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $category = Category::with('products')->findOrFail($id);
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $categories = Category::with('products')->orderByDesc('id')->get();
        $edit = Category::findOrFail($id);
        return view('admin.categories.index', compact('categories', 'edit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = Category::findOrFail($id);
        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
    }
}
