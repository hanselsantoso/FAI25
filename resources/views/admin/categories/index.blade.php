@extends('layouts.admin')

@section('title', 'Categories - Admin')
@section('page-title', 'Categories')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Category Management</h3>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @php $editing = isset($edit) ? $edit : null; @endphp
        <form method="POST" action="{{ $editing ? route('admin.categories.update', $editing['id']) : route('admin.categories.store') }}" class="form-inline mb-3">
            @csrf
            @if($editing)
                @method('PUT')
            @endif
            <div class="form-group mr-2">
                <input type="text" name="name" class="form-control" placeholder="Category name" value="{{ $editing['name'] ?? '' }}">
            </div>
            <button class="btn btn-primary">{{ $editing ? 'Update' : 'Add' }}</button>
            @if($editing)
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary ml-2">Cancel</a>
            @endif
        </form>

        <ul class="list-group">
            @foreach($categories as $c)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $c['name'] }}
                <span>
                    <a href="{{ route('admin.categories.edit', $c['id']) }}" class="btn btn-sm btn-info">Edit</a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $c['id']) }}" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">Delete</button>
                    </form>
                </span>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
