@extends('layouts.admin')

@section('title', 'Update Stok Produk')
@section('page-title', 'Update Stok Produk')

@section('content')
<div class="row">
    <div class="col-12">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>
</div>

<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title">Daftar Produk</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-striped mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Supplier</th>
                    <th>Harga</th>
                    <th>Stok Saat Ini</th>
                    <th width="220">Update Stok</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category?->name ?? '—' }}</td>
                        <td>{{ $product->supplier?->name ?? '—' }}</td>
                        <td>Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                        <td><span class="badge badge-primary">{{ $product->stock }}</span></td>
                        <td>
                            <form action="{{ route('admin.warehouse.products.update', $product) }}" method="POST" class="form-inline">
                                @csrf
                                @method('PUT')
                                <div class="input-group input-group-sm mr-2" style="max-width: 140px;">
                                    <input type="number" name="stock" class="form-control" min="0" value="{{ old('stock', $product->stock) }}" required>
                                </div>
                                <button type="submit" class="btn btn-sm btn-success">Update</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data produk. Tambahkan produk terlebih dahulu.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
