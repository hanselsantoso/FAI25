<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected $sessionKey = 'suppliers';

    protected function nextId($items)
    {
        if (empty($items)) return 1;
        return max(array_column($items, 'id')) + 1;
    }

    public function index(Request $request)
    {
    $suppliers = session($this->sessionKey, []);
    return view('admin.suppliers.index', ['suppliers' => $suppliers]);
    }

    public function store(Request $request)
    {
        $items = session($this->sessionKey, []);
        $item = [
            'id' => $this->nextId($items),
            'name' => $request->input('name', 'Unnamed'),
            'contact' => $request->input('contact', ''),
        ];
        $items[] = $item;
        session([$this->sessionKey => $items]);
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier created.');
    }

    public function show($id)
    {
        $items = session($this->sessionKey, []);
        foreach ($items as $it) {
            if ($it['id'] == $id) return view('admin.suppliers.show', ['supplier' => $it]);
        }
        abort(404);
    }

    public function edit($id)
    {
        $items = session($this->sessionKey, []);
        foreach ($items as $it) {
            if ($it['id'] == $id) return view('admin.suppliers.index', ['suppliers' => $items, 'edit' => $it]);
        }
        abort(404);
    }

    public function update(Request $request, $id)
    {
        $items = session($this->sessionKey, []);
        foreach ($items as $idx => $it) {
            if ($it['id'] == $id) {
                $items[$idx] = array_merge($it, $request->only(['name', 'contact']));
                session([$this->sessionKey => $items]);
                return redirect()->route('admin.suppliers.index')->with('success', 'Supplier updated.');
            }
        }
        abort(404);
    }

    public function destroy($id)
    {
        $items = session($this->sessionKey, []);
        foreach ($items as $idx => $it) {
            if ($it['id'] == $id) {
                array_splice($items, $idx, 1);
                session([$this->sessionKey => $items]);
                return redirect()->route('admin.suppliers.index')->with('success', 'Supplier deleted.');
            }
        }
        abort(404);
    }
}
