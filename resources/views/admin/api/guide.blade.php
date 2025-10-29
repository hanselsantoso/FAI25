@extends('layouts.admin')

@section('title', 'Panduan API')
@section('page-title', 'Memahami & Mencoba REST API')

@section('content')
<div class="row">
    <div class="col-lg-7">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title mb-0">Apa itu API?</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">Application Programming Interface (API) adalah kontrak komunikasi antar sistem. Alih-alih mengirim HTML seperti Blade, API biasanya bertukar data mentah (JSON) yang lebih mudah dibaca oleh aplikasi lain—termasuk aplikasi mobile, SPA (React/Vue), atau integrasi pihak ketiga.</p>
                <p class="text-muted mb-0">REST API mendefinisikan pola standar menggunakan URL + HTTP Method (GET, POST, PUT, PATCH, DELETE) untuk melakukan operasi CRUD. Fokus kita adalah memahami kapan memakai tiap method dan bagaimana membaca responnya.</p>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title mb-0">Kapan API Dibutuhkan?</h3>
            </div>
            <div class="card-body">
                <ul class="mb-0 pl-3 text-muted">
                    <li>Menjalankan aplikasi mobile atau SPA yang butuh data realtime.</li>
                    <li>Integrasi internal antar sistem (misal Gudang & Marketplace).</li>
                    <li>Automasi lewat skrip/cron job tanpa antarmuka Blade.</li>
                    <li>Berbagi data ke pihak ketiga secara terkontrol.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title mb-0">Endpoint Demo: <code>/api/demo/products</code></h3>
            </div>
            <div class="card-body">
                <p class="text-muted">Untuk latihan, kita menambahkan controller <code>ProductApiController</code> dengan resource routes di <code>routes/api.php</code>. Endpoint ini terbuka tanpa autentikasi agar mudah dicoba dari browser atau tools seperti Postman.</p>
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Method</th>
                                <th>URL</th>
                                <th>Deskripsi</th>
                                <th>Body / Query</th>
                                <th>Respon</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge badge-success">GET</span></td>
                                <td><code>/api/demo/products</code></td>
                                <td>Ambil seluruh produk (termasuk kategori, supplier, tag).</td>
                                <td>Optional: <code>?page=1</code> (future enhancement).</td>
                                <td><code>200</code> + JSON daftar produk.</td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-success">GET</span></td>
                                <td><code>/api/demo/products/{id}</code></td>
                                <td>Ambil detail produk tertentu.</td>
                                <td>-</td>
                                <td><code>200</code> + JSON detail atau <code>404</code>.</td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-primary">POST</span></td>
                                <td><code>/api/demo/products</code></td>
                                <td>Buat produk baru.</td>
                                <td><code>{"name": "Keyboard", "price": 450000, "stock": 10, "category_id": 1, "supplier_id": 2, "tag_ids": [1,3]}</code></td>
                                <td><code>201</code> + JSON produk baru.</td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-warning">PUT</span></td>
                                <td><code>/api/demo/products/{id}</code></td>
                                <td>Update seluruh field produk.</td>
                                <td>Sama seperti POST, semua field wajib diisi.</td>
                                <td><code>200</code> + JSON produk terbaru.</td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-warning">PATCH</span></td>
                                <td><code>/api/demo/products/{id}</code></td>
                                <td>Update sebagian field produk.</td>
                                <td>Misal <code>{"stock": 25}</code>.</td>
                                <td><code>200</code> + JSON produk terbaru.</td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-danger">DELETE</span></td>
                                <td><code>/api/demo/products/{id}</code></td>
                                <td>Hapus produk.</td>
                                <td>-</td>
                                <td><code>204</code> tanpa body.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title mb-0">Latihan Mengirim Request</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="font-weight-bold">Menggunakan cURL (CLI)</h5>
                        <pre class="bg-light p-2 rounded"><code># GET daftar produk
curl http://localhost/api/demo/products

# POST produk baru
curl -X POST http://localhost/api/demo/products \
  -H "Content-Type: application/json" \
  -d '{"name":"Keyboard","price":450000,"stock":10,"category_id":1,"supplier_id":2}'

# PATCH update stok
curl -X PATCH http://localhost/api/demo/products/1 \
  -H "Content-Type: application/json" \
  -d '{"stock":25}'

# DELETE produk
curl -X DELETE http://localhost/api/demo/products/1
</code></pre>
                    </div>
                    <div class="col-md-6">
                        <h5 class="font-weight-bold">Menggunakan JavaScript (Fetch)</h5>
                        <pre class="bg-light p-2 rounded"><code>// GET
fetch('/api/demo/products')
  .then((res) => res.json())
  .then((json) => console.log(json.data));

// POST
fetch('/api/demo/products', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    name: 'Keyboard',
    price: 450000,
    stock: 10,
    category_id: 1,
    supplier_id: 2,
  }),
}).then((res) => res.json()).then(console.log);

// PUT
fetch('/api/demo/products/1', {
  method: 'PUT',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    name: 'Keyboard V2',
    price: 500000,
    stock: 12,
    category_id: 1,
    supplier_id: 2,
  }),
});

// DELETE
fetch('/api/demo/products/1', { method: 'DELETE' });
</code></pre>
                    </div>
                </div>
                <p class="text-muted mb-0">Catatan: Di produksi, API biasanya diamankan dengan token (Laravel Sanctum/Passport). Di materi lanjutan, kita akan menambah autentikasi, rate limiting, dan dokumentasi otomatis (OpenAPI/Swagger).</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title mb-0">Checklist Implementasi API di Proyek</h3>
            </div>
            <div class="card-body">
                <ol class="pl-3 mb-0 text-muted">
                    <li>Buat route di <code>routes/api.php</code> dan daftarkan di <code>bootstrap/app.php</code>.</li>
                    <li>Gunakan controller khusus (folder <code>App\\Http\\Controllers\\Api</code>) agar terpisah dari controller Blade.</li>
                    <li>Validasi input menggunakan <code>$request-&gt;validate()</code> atau <code>FormRequest</code>.</li>
                    <li>Gunakan relasi Eloquent untuk mengirim data terhubung dalam satu respon.</li>
                    <li>Selalu kirim status code yang tepat (200, 201, 204, 404, 422, dll).</li>
                    <li>Siapkan dokumentasi ringkas (tabel/README) agar tim lain tahu cara memanggil API.</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
