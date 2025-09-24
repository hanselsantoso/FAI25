@extends('layouts.admin')

@section('title', 'Suppliers - Admin')
@section('page-title', 'Suppliers')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css"/>
@endpush

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Supplier Management</h3>
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
        <form method="POST" action="{{ $editing ? route('admin.suppliers.update', $editing->id) : route('admin.suppliers.store') }}" class="form-inline mb-3">
            @csrf
            @if($editing)
                @method('PUT')
            @endif
            <div class="form-group mr-2 mb-2">
                <input type="text" name="name" class="form-control" placeholder="Supplier name" value="{{ old('name', $editing->name ?? '') }}">
            </div>
            <div class="form-group mr-2 mb-2">
                <input type="email" name="contact" class="form-control" placeholder="Contact email" value="{{ old('contact', $editing->contact ?? '') }}">
            </div>
            <button class="btn btn-primary mb-2">{{ $editing ? 'Update' : 'Add' }}</button>
            @if($editing)
                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary ml-2 mb-2">Cancel</a>
            @endif
        </form>

        <table class="table table-bordered table-striped" id="suppliers-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $s)
                <tr>
                    <td>{{ $s->id }}</td>
                    <td>{{ $s->name }}</td>
                    <td>{{ $s->contact }}</td>
                    <td>
                        <a href="{{ route('admin.suppliers.edit', $s->id) }}" class="btn btn-sm btn-info">Edit</a>
                        <form method="POST" action="{{ route('admin.suppliers.destroy', $s->id) }}" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this supplier?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Products Grouped by Supplier</h3>
        <small class="text-muted">Eager loaded ({{ $suppliers->count() }} suppliers)</small>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="supplier-products-table">
                <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>Product Count</th>
                        <th>Products (Name • Price • Stock)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $s)
                        <tr>
                            <td>{{ $s->name }}</td>
                            <td>{{ $s->products->count() }}</td>
                            <td>
                                @if($s->products->isEmpty())
                                    <span class="text-muted">No products</span>
                                @else
                                    <ul class="list-unstyled mb-0 small">
                                        @foreach($s->products as $p)
                                            <li>
                                                {{ $p->name }} • Rp {{ number_format($p->price,0,',','.') }} • Stock: {{ $p->stock }}
                                            </li>
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
    $('#suppliers-table').DataTable({
        pageLength: 10,
        order: [[0,'desc']],
        columnDefs: [{ targets: -1, orderable: false, searchable: false }]
    });
    $('#supplier-products-table').DataTable({
        pageLength: 10,
        order: [[1,'desc']],
        columns: [
            null,
            { orderable: true },
            { orderable: false }
        ]
    });
});
</script>
@endpush
