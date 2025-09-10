<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $sessionKey = 'products';

    protected function nextId($items)
    {
        if (empty($items)) return 1;
        return max(array_column($items, 'id')) + 1;
    }

    public function index(Request $request)
    {
        $products = session($this->sessionKey, []);
        return view('admin.products.index', ['products' => $products]);
    }

    public function store(Request $request)
    {
        $items = session($this->sessionKey, []);
        $data = $request->only(['name', 'price', 'stock']);
        $item = [
            'id' => $this->nextId($items),
            'name' => $data['name'] ?? 'Unnamed',
            'price' => (float) ($data['price'] ?? 0),
            'stock' => (int) ($data['stock'] ?? 0),
        ];
        $items[] = $item;
        session([$this->sessionKey => $items]);
        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function show($id)
    {
        $items = session($this->sessionKey, []);
        foreach ($items as $it) {
            if ($it['id'] == $id) return view('admin.products.show', ['product' => $it]);
        }
        abort(404);
    }

    public function edit($id)
    {
        $items = session($this->sessionKey, []);
        foreach ($items as $it) {
            if ($it['id'] == $id) return view('admin.products.index', ['products' => $items, 'edit' => $it]);
        }
        abort(404);
    }

    public function update(Request $request, $id)
    {
        $items = session($this->sessionKey, []);
        foreach ($items as $idx => $it) {
            if ($it['id'] == $id) {
                $items[$idx] = array_merge($it, $request->only(['name', 'price', 'stock']));
                session([$this->sessionKey => $items]);
                return redirect()->route('admin.products.index')->with('success', 'Product updated.');
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
                return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
            }
        }
        abort(404);
    }
}
