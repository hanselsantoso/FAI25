@extends('layouts.admin')

@section('title', 'Suppliers - Admin')
@section('page-title', 'Suppliers')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Supplier Management</h3>
    </div>
    <div class="card-body">
        <form class="form-inline mb-3">
            <div class="form-group mr-2">
                <input type="text" name="name" class="form-control" placeholder="Supplier name">
            </div>
            <div class="form-group mr-2">
                <input type="email" name="contact" class="form-control" placeholder="Contact email">
            </div>
            <button type="button" class="btn btn-primary">Add Supplier</button>
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
