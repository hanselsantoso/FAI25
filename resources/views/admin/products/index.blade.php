@extends('layouts.admin')

@section('title', 'Products - Admin')
@section('page-title', 'Products')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Product Management</h3>
    </div>

    <div class="card-body">
        <form class="form-inline mb-3">
            <div class="form-group mr-2">
                <input type="text" name="name" class="form-control" placeholder="Product name">
            </div>
            <div class="form-group mr-2">
                <input type="number" step="0.01" name="price" class="form-control" placeholder="Price">
            </div>
            <div class="form-group mr-2">
                <input type="number" name="stock" class="form-control" placeholder="Stock">
            </div>
            <button type="button" class="btn btn-primary">Add Product</button>
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
                        <a href="#" class="btn btn-sm btn-info">Edit</a>
                        <a href="#" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
