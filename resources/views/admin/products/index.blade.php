@extends('layouts.admin')

@section('title', 'Products - Admin')
@section('page-title', 'Products')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css"/>
@endpush

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Product Management</h3>
        <div>
            <small class="text-muted">Using Eloquent Relationships</small>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php $editing = isset($edit) ? $edit : null; @endphp
        @php
            $hasCat = isset($categories) && $categories->count() > 0;
            $hasSup = isset($suppliers) && $suppliers->count() > 0;
            $canCreate = $hasCat && $hasSup;
        @endphp

        @unless($canCreate)
            <div class="alert alert-warning">
                You must have at least one Category and one Supplier before adding products.
                <div class="mt-1 small">Add them first under Categories / Suppliers menu.</div>
            </div>
        @endunless

        <form method="POST" action="{{ $editing ? route('admin.products.update', $editing->id) : route('admin.products.store') }}" class="mb-3">
            @csrf
            @if($editing)
                @method('PUT')
            @endif
            <div class="form-row">
                <div class="col-md-3 mb-2">
                    <input type="text" name="name" class="form-control" placeholder="Product name" value="{{ old('name', $editing->name ?? '') }}" {{ $canCreate ? '' : 'disabled' }}>
                </div>
                <div class="col-md-2 mb-2">
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" value="{{ old('price', $editing->price ?? '') }}" {{ $canCreate ? '' : 'disabled' }}>
                </div>
                <div class="col-md-2 mb-2">
                    <input type="number" name="stock" class="form-control" placeholder="Stock" value="{{ old('stock', $editing->stock ?? '') }}" {{ $canCreate ? '' : 'disabled' }}>
                </div>
                <div class="col-md-2 mb-2">
                    <select name="category_id" class="form-control" {{ $canCreate ? '' : 'disabled' }}>
                        <option value="">-- Category --</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ (int) old('category_id', $editing->category_id ?? 0) === $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <select name="supplier_id" class="form-control" {{ $canCreate ? '' : 'disabled' }}>
                        <option value="">-- Supplier --</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ (int) old('supplier_id', $editing->supplier_id ?? 0) === $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 mb-2 d-flex">
                    <button type="submit" class="btn btn-primary btn-block" {{ $canCreate ? '' : 'disabled' }}>{{ $editing ? 'Update' : 'Add' }}</button>
                </div>
                @if($editing)
                    <div class="col-md-1 mb-2 d-flex">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-block">Cancel</a>
                    </div>
                @endif
            </div>
        </form>
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap" id="products-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Supplier</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->category->name ?? '-' }}</td>
                    <td>{{ $p->supplier->name ?? '-' }}</td>
                    <td>Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                    <td>{{ $p->stock }}</td>
                    <td>
                        <a href="{{ route('admin.products.edit', $p->id) }}" class="btn btn-sm btn-info">Edit</a>
                        <form method="POST" action="{{ route('admin.products.destroy', $p->id) }}" style="display:inline-block">
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

@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function(){
    $('#products-table').DataTable({
        pageLength: 10,
        order: [[0,'desc']],
        columnDefs: [
            { targets: -1, orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
