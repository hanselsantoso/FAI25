@extends('layouts.adm\in')

@section('title', 'Products - Admin')
@section('page-title', 'Products')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Product Management</h3>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @php $editing = isset($edit) ? $edit : null; @endphp
        <form method="POST" action="{{ $editing ? route('admin.products.update', $editing['id']) : route('admin.products.store') }}" class="form-inline mb-3">
            @csrf
            @if($editing)
                @method('PUT')
            @endif
            <div class="form-group mr-2">
                <input type="text" name="name" class="form-control" placeholder="Product name" value="{{ $editing['name'] ?? '' }}">
            </div>
            <div class="form-group mr-2">
                <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" value="{{ $editing['price'] ?? '' }}">
            </div>
            <div class="form-group mr-2">
                <input type="number" name="stock" class="form-control" placeholder="Stock" value="{{ $editing['stock'] ?? '' }}">
            </div>
            <button type="submit" class="btn btn-primary">{{ $editing ? 'Update' : 'Add' }}</button>
            @if($editing)
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary ml-2">Cancel</a>
            @endif
        </form>
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $p)
                <tr>
                    <td>{{ $p['id'] }}</td>
                    <td>{{ $p['name'] }}</td>
                    <td>Rp {{ number_format($p['price'], 0, ',', '.') }}</td>
                    <td>{{ $p['stock'] }}</td>
                    <td>
                        <a href="{{ route('admin.products.edit', $p['id']) }}" class="btn btn-sm btn-info">Edit</a>
                        <form method="POST" action="{{ route('admin.products.destroy', $p['id']) }}" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
