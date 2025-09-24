<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;

class SupplierController extends Controller
{
    // protected $sessionKey = 'suppliers'; // OLD session storage (deprecated)

    /**
     * Display a listing of suppliers.
     */
    public function index(Request $request)
    {
        // OLD session
        // $suppliers = session($this->sessionKey, []);

        // ACTIVE Eloquent (with products eager loaded for grouping table)
        $suppliers = Supplier::with('products')->orderByDesc('id')->get();

        /* Query Builder
        // $suppliers = DB::table('suppliers')->orderByDesc('id')->get();
        */

        /* Raw SQL
        // $suppliers = DB::select('SELECT * FROM suppliers ORDER BY id DESC');
        */

        return view('admin.suppliers.index', compact('suppliers'));
    }

    /**
     * Store a newly created supplier.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
        ]);

        Supplier::create($data);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier created.');
    }

    /**
     * Display the specified supplier.
     */
    public function show($id)
    {
        $supplier = Supplier::with('products')->findOrFail($id);
        return view('admin.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the supplier.
     */
    public function edit($id)
    {
        $suppliers = Supplier::with('products')->orderByDesc('id')->get();
        $edit = Supplier::findOrFail($id);
        return view('admin.suppliers.index', compact('suppliers', 'edit'));
    }

    /**
     * Update the specified supplier.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update($data);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier updated.');
    }

    /**
     * Remove the specified supplier.
     */
    public function destroy($id)
    {
        Supplier::findOrFail($id)->delete();
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier deleted.');
    }
}
