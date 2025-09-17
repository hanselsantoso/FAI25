@extends('layouts.admin')

@section('title', 'Validation Examples - Admin')
@section('page-title', 'Validation Examples')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Validation Examples</h3>
    </div>
    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @if(session('validated'))
                <pre>{{ print_r(session('validated'), true) }}</pre>
            @endif
        @endif
        @if(session('users'))
            <div class="card mt-3">
                <div class="card-header"> List User</div>
                <div class="card-body">
                    <ul>
                        @foreach(session('users') as $u)
                            <li>{{ $u['username'] ?? '(no username)' }} - {{ $u['email'] ?? '' }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <h5>1) Inline validation (controller)</h5>
        <form method="POST" action="{{ route('admin.validation.inline') }}">
            @csrf
            <div class="form-group">
                <label>Name (required)</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Email (required, email)</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Phone (required, regex demo)</label>
                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary">Submit inline</button>
        </form>

        <hr>

        <h5>2) FormRequest validation with many rules and custom messages</h5>
        <form method="POST" action="{{ route('admin.validation.request') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Full name (required)</label>
                    <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}">
                    @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group col-md-6">
                    <label>Username (required, alphanumeric)</label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}">
                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group col-md-6">
                    <label>Password (confirmed)</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    <input type="password" name="password_confirmation" class="form-control mt-1" placeholder="Confirm password">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Age (integer between 18-99)</label>
                    <input type="number" name="age" class="form-control @error('age') is-invalid @enderror" value="{{ old('age') }}">
                    @error('age') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group col-md-4">
                    <label>Phone (regex)</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group col-md-4">
                    <label>Referral code (alpha_num, size 6)</label>
                    <input type="text" name="ref_code" class="form-control @error('ref_code') is-invalid @enderror" value="{{ old('ref_code') }}">
                    @error('ref_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Website (url)</label>
                    <input type="text" name="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website') }}">
                    @error('website') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group col-md-6">
                    <label>Avatar (image, max 2MB)</label>
                    <input type="file" name="avatar" class="form-control-file @error('avatar') is-invalid @enderror">
                    @error('avatar') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>

            <button class="btn btn-success">Submit FormRequest</button>
        </form>

        <hr>
        <h5>Notes</h5>
        <ul>
            <li>Gunakan <code>@@error('field')</code> dan <code>old('field')</code> untuk menampilkan error dan mengisi ulang input.</li>
            <li>Definisikan pesan kustom di <code>messages()</code> pada FormRequest atau lewat argumen kedua pada <code>&#36;request-&gt;validate([...], [...])</code>.</li>
            <li>Gunakan <code>withValidator()</code> di FormRequest untuk validasi kompleks (contoh: username unik di session).</li>
        </ul>
    </div>
</div>
@endsection
