@extends('layouts.admin')

@section('title', 'Suppliers - Admin')
@section('page-title', 'Suppliers')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Supplier Management</h3>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @php $editing = isset($edit) ? $edit : null; @endphp
        <form method="POST" action="{{ $editing ? route('admin.suppliers.update', $editing['id']) : route('admin.suppliers.store') }}" class="form-inline mb-3">
            @csrf
            @if($editing)
                @method('PUT')
            @endif
            <div class="form-group mr-2">
                <input type="text" name="name" class="form-control" placeholder="Supplier name" value="{{ $editing['name'] ?? '' }}">
            </div>
            <div class="form-group mr-2">
                <input type="email" name="contact" class="form-control" placeholder="Contact email" value="{{ $editing['contact'] ?? '' }}">
            </div>
            <button class="btn btn-primary">{{ $editing ? 'Update' : 'Add' }}</button>
            @if($editing)
                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary ml-2">Cancel</a>
            @endif
        </form>

        <table class="table table-bordered table-striped">
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
                    <td>{{ $s['id'] }}</td>
                    <td>{{ $s['name'] }}</td>
                    <td>{{ $s['contact'] }}</td>
                    <td>
                        <a href="{{ route('admin.suppliers.edit', $s['id']) }}" class="btn btn-sm btn-info">Edit</a>
                        <form method="POST" action="{{ route('admin.suppliers.destroy', $s['id']) }}" style="display:inline-block">
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
@endsection
