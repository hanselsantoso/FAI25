<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationExampleRequest extends FormRequest
{
    // return true jika semua user boleh mengakses request ini
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'full_name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'alpha_num', 'min:3', 'max:20'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'age' => ['nullable', 'integer', 'between:18,99'],
            'phone' => ['required', 'regex:/^[0-9\-\+\s\(\)]+$/'],
            'bio' => ['nullable', 'string', 'max:500'],
            'website' => ['nullable', 'url'],
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'ref_code' => ['nullable', 'alpha_num', 'size:6'],
        ];
    }

    public function withValidator($validator)
    {
        // contoh validasi custom error message dengnan user harus unik
        $validator->after(function ($validator) {
            $username = $this->input('username');
            if ($username) {
                $users = session('users', []);
                $normalized = strtolower(trim($username));
                foreach ($users as $u) {
                    if (isset($u['username']) && strtolower(trim($u['username'])) === $normalized) {
                        $validator->errors()->add('username', 'This username is already taken');
                        break;
                    }
                }
            }
        });
    }

    public function messages()
    {
        return [
            'full_name.required' => 'Full name is required.',
            'username.required' => 'Choose a username (letters and numbers only).',
            'username.alpha_num' => 'Username may only contain letters and numbers.',
            'password.confirmed' => 'Password confirmation does not match.',
            'phone.regex' => 'Phone number is invalid. Use digits and optionally + - ( ) and spaces.',
            'avatar.image' => 'Avatar must be an image file (jpeg,png,gif,webp).',
            'ref_code.size' => 'Referral code must be exactly 6 alphanumeric characters.',
        ];
    }
}
