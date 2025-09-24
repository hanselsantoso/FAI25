@extends('layouts.admin')

@section('title', 'Categories - Admin')
@section('page-title', 'Categories')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css"/>
@endpush

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Category Management</h3>
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
        <form method="POST" action="{{ $editing ? route('admin.categories.update', $editing->id) : route('admin.categories.store') }}" class="form-inline mb-3">
            @csrf
            @if($editing)
                @method('PUT')
            @endif
            <div class="form-group mr-2 mb-2">
                <input type="text" name="name" class="form-control" placeholder="Category name" value="{{ old('name', $editing->name ?? '') }}">
            </div>
            <button class="btn btn-primary mb-2">{{ $editing ? 'Update' : 'Add' }}</button>
            @if($editing)
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary ml-2 mb-2">Cancel</a>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="categories-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $c)
                    <tr>
                        <td>{{ $c->id }}</td>
                        <td>{{ $c->name }}</td>
                        <td>
                            <a href="{{ route('admin.categories.edit', $c->id) }}" class="btn btn-sm btn-info">Edit</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $c->id) }}" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Products Grouped by Category</h3>
        <small class="text-muted">Eager loaded ({{ $categories->count() }} categories)</small>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="category-products-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Product Count</th>
                        <th>Products (Name • Price • Stock)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $c)
                        <tr>
                            <td>{{ $c->name }}</td>
                            <td>{{ $c->products->count() }}</td>
                            <td>
                                @if($c->products->isEmpty())
                                    <span class="text-muted">No products</span>
                                @else
                                    <ul class="list-unstyled mb-0 small">
                                        @foreach($c->products as $p)
                                            <li>{{ $p->name }} • Rp {{ number_format($p->price,0,',','.') }} • Stock: {{ $p->stock }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function(){
    $('#categories-table').DataTable({
        pageLength: 10,
        order: [[0,'desc']],
        columnDefs: [ { targets: -1, orderable: false, searchable: false } ]
    });
    $('#category-products-table').DataTable({
        pageLength: 10,
        order: [[1,'desc']],
        columns: [ null, { orderable: true }, { orderable: false } ]
    });
});
</script>
@endpush
