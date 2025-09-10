@extends('layouts.admin')

@section('title', 'Categories - Admin')
@section('page-title', 'Categories')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Category Management</h3>
    </div>
    <div class="card-body">
        <form class="form-inline mb-3">
            <div class="form-group mr-2">
                <input type="text" name="name" class="form-control" placeholder="Category name">
            </div>
            <button type="button" class="btn btn-primary">Add Category</button>
        </form>

        <ul class="list-group">
            @foreach($categories as $c)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $c['name'] }}
                <span>
                    <a href="#" class="btn btn-sm btn-info">Edit</a>
                    <a href="#" class="btn btn-sm btn-danger">Delete</a>
                </span>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
