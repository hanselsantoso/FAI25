<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ValidationExampleRequest;

class ValidationDemoController extends Controller
{
    public function show()
    {
        return view('admin.validation_examples');
    }

    public function handleInline(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'age' => ['nullable', 'integer', 'min:1', 'max:120'],
            'phone' => ['required', 'regex:/^[0-9\-\+\s\(\)]+$/'],
            'username' => ['required', 'alpha_num', 'min:3', 'max:20'],
        ],[
            'name.required' => 'Please tell us your name.',
            'phone.regex' => 'Phone must contain only numbers and common symbols (+ - ( ) space).',
        ]);

    session()->push('users', ['username' => $data['username'] ?? null, 'email' => $data['email'] ?? null]);

    return redirect()->route('admin.validation.show')->with('success', 'Inline validation passed and stored in session.')->with('validated', $data);
    }

    public function handleFormRequest(ValidationExampleRequest $request)
    {
        $data = $request->validated();
    session()->push('users', ['username' => $data['username'] ?? null, 'email' => $data['email'] ?? null]);

    return redirect()->route('admin.validation.show')->with('success', 'FormRequest telah valid dan sudah disimpan')->with('validated', $data);
    }
}
