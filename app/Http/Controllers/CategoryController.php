<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $sessionKey = 'categories';

    protected function nextId($items)
    {
        if (empty($items)) return 1;
        return max(array_column($items, 'id')) + 1;
    }

    public function index(Request $request)
    {
    $categories = session($this->sessionKey, []);
    return view('admin.categories.index', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $items = session($this->sessionKey, []);
        $item = [
            'id' => $this->nextId($items),
            'name' => $request->input('name', 'Unnamed'),
        ];
        $items[] = $item;
        session([$this->sessionKey => $items]);
        return redirect()->route('admin.categories.index')->with('success', 'Category created.');
    }

    public function show($id)
    {
        $items = session($this->sessionKey, []);
        foreach ($items as $it) {
            if ($it['id'] == $id) return view('admin.categories.show', ['category' => $it]);
        }
        abort(404);
    }

    public function edit($id)
    {
        $items = session($this->sessionKey, []);
        foreach ($items as $it) {
            if ($it['id'] == $id) return view('admin.categories.index', ['categories' => $items, 'edit' => $it]);
        }
        abort(404);
    }

    public function update(Request $request, $id)
    {
        $items = session($this->sessionKey, []);
        foreach ($items as $idx => $it) {
            if ($it['id'] == $id) {
                $items[$idx]['name'] = $request->input('name', $it['name']);
                session([$this->sessionKey => $items]);
                return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
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
                return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
            }
        }
        abort(404);
    }
}
