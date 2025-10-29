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
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Memahami AJAX & Pilihan API HTTP di Browser</h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">AJAX (Asynchronous JavaScript and XML) adalah pola komunikasi yang memungkinkan halaman web mengirim atau menerima data dari server tanpa melakukan refresh penuh. Saat request berlangsung, JavaScript tetap bisa merespons interaksi pengguna, sehingga pengalaman terasa lebih responsif.</p>
                            <h5 class="font-weight-bold">Kenapa Asynchronous?</h5>
                            <ul class="text-muted pl-3">
                                <li>UI tidak beku ketika request jaringan memerlukan waktu lama.</li>
                                <li>Bisa memuat sebagian data (lazy loading) dan memperbarui DOM sesuai kebutuhan.</li>
                                <li>Memungkinkan chaining antar request (misal setelah POST berhasil, langsung GET ulang).</li>
                            </ul>
                            <h5 class="font-weight-bold">Perbandingan fetch, jQuery.ajax(), dan Axios</h5>
                                            <div class="table-responsive">
                                                    <table class="table table-sm table-striped mb-3">
                                                            <thead class="thead-light">
                                                                    <tr>
                                                                            <th>API</th>
                                                                            <th>Kelebihan</th>
                                                                            <th>Catatan</th>
                                                                    </tr>
                                                            </thead>
                                                            <tbody class="text-muted">
                                                                    <tr>
                                                                            <td><code>fetch()</code> (native)</td>
                                                                            <td>Tersedia di browser modern tanpa dependensi, mendukung Promise, streaming, abort controller.</td>
                                                                            <td>Tidak otomatis me-reject HTTP error (status 4xx/5xx tetap resolved); perlu memanggil <code>response.json()</code> secara eksplisit.</td>
                                                                    </tr>
                                                                    <tr>
                                                                            <td><code>$.ajax()</code> (jQuery)</td>
                                                                            <td>API mature, dukungan ke browser lama, otomatis memasang header CSRF (dengan setup), fungsi helper seperti <code>$.getJSON()</code>.</td>
                                                                            <td>Membutuhkan jQuery; gaya callback lama meski dapat digabung dengan Promise (<code>$.Deferred</code>).</td>
                                                                    </tr>
                                                                    <tr>
                                                                            <td><code>axios</code> (library pihak ketiga)</td>
                                                                            <td>Promise-based, interceptor request/response, parsing JSON otomatis, dukungan Node.js dan browser.</td>
                                                                            <td>Butuh instalasi tambahan; ukuran bundle sedikit lebih besar dibanding fetch native.</td>
                                                                    </tr>
                                                            </tbody>
                                                    </table>
                                            </div>
                                                            <h6 class="font-weight-bold">Contoh Singkat (PATCH stok produk)</h6>
                                                            <div class="row">
                                                                    <div class="col-md-6">
                                                                            <h6 class="text-uppercase text-muted font-weight-bold">fetch()</h6>
                                                                            <pre class="bg-light p-2 rounded"><code>const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                            const payload = { stock: 20 };

                            fetch('/api/demo/products/1', {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                },
                                body: JSON.stringify(payload),
                                credentials: 'same-origin',
                            })
                                .then((response) => {
                                    if (!response.ok) throw new Error('Gagal memperbarui produk');
                                    return response.json();
                                })
                                .then((json) => console.log(json))
                                .catch((error) => console.error(error.message));</code></pre>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                            <h6 class="text-uppercase text-muted font-weight-bold">jQuery $.ajax()</h6>
                                                                            <pre class="bg-light p-2 rounded"><code>var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                            var payload = { stock: 20 };

                            $.ajax({
                                url: '/api/demo/products/1',
                                type: 'PATCH',
                                contentType: 'application/json',
                                dataType: 'json',
                                data: JSON.stringify(payload),
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                },
                                success: function (json) {
                                    console.log(json);
                                },
                                error: function (xhr) {
                                    var message = (xhr.responseJSON && xhr.responseJSON.message) || 'Gagal memperbarui produk';
                                    console.error(message);
                                },
                            });</code></pre>
                                                                    </div>
                                                            </div>
                            <h5 class="font-weight-bold">Parameter Penting pada Request AJAX</h5>
                            <p class="text-muted mb-2">Apa pun tools yang dipakai, pola konfigurasi umumnya melibatkan URL, method, header, dan payload. Berikut parameter yang sering digunakan:</p>
                            <div class="row text-muted">
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold">Fetch API</h6>
                                    <ul class="pl-3">
                                        <li><code>method</code>: HTTP verb seperti <code>GET</code>, <code>POST</code>, <code>PUT</code>, <code>DELETE</code>.</li>
                                        <li><code>headers</code>: objek untuk header tambahan (misal <code>{ 'Content-Type': 'application/json' }</code>).</li>
                                        <li><code>body</code>: isi request, biasanya hasil <code>JSON.stringify()</code> untuk JSON.</li>
                                        <li><code>credentials</code>: kirim cookie (<code>'include'</code> / <code>'same-origin'</code>).</li>
                                        <li><code>mode</code>: pengaturan CORS (<code>'cors'</code>, <code>'no-cors'</code>, <code>'same-origin'</code>).</li>
                                        <li><code>signal</code>: abort request menggunakan <code>AbortController</code>.</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold">jQuery <code>$.ajax()</code></h6>
                                    <ul class="pl-3">
                                        <li><code>url</code>: alamat endpoint.</li>
                                        <li><code>type</code> atau <code>method</code>: HTTP verb.</li>
                                        <li><code>data</code>: payload yang akan dikirim (string, objek, atau FormData).</li>
                                        <li><code>contentType</code>: tipe payload yang dikirim (default <code>application/x-www-form-urlencoded</code>).</li>
                                        <li><code>dataType</code>: format respon yang diharapkan (misal <code>'json'</code> agar otomatis diparse).</li>
                                        <li><code>headers</code>: header tambahan, termasuk <code>X-CSRF-TOKEN</code>.</li>
                                        <li><code>success</code> & <code>error</code>: callback ketika request selesai.</li>
                                        <li><code>beforeSend</code> & <code>complete</code>: hook untuk menyalakan/mematikan loading indicator.</li>
                                    </ul>
                                </div>
                            </div>
                            <p class="text-muted mb-0">Library lain seperti Axios, SuperAgent, atau ky punya nama parameter berbeda, namun konsep dasarnya sama: definisikan endpoint, method, header, payload, serta tangani respon dan error secara eksplisit.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
        <div class="col-lg-12">
                <div class="card card-outline card-primary">
                        <div class="card-header">
                                <h3 class="card-title mb-0">Contoh CRUD Produk Menggunakan AJAX</h3>
                        </div>
                                    <div class="card-body">
                                            <p class="text-muted">Gunakan formulir ini untuk mencoba CRUD langsung terhadap endpoint <code>/api/demo/products</code>. Semua aksi dilakukan via AJAX tanpa refresh halaman.</p>
                                            <div id="ajaxProductAlert" class="alert d-none" role="alert"></div>
                                            <form id="ajaxProductForm" class="border rounded p-3 mb-3 bg-light">
                                                    <input type="hidden" name="product_id" value="">
                                                    <div class="form-row">
                                                            <div class="form-group col-md-4">
                                                                    <label class="small text-muted mb-1" for="ajaxProductName">Nama Produk</label>
                                                                    <input id="ajaxProductName" name="name" class="form-control" placeholder="Contoh: Keyboard" required>
                                                            </div>
                                                            <div class="form-group col-md-2">
                                                                    <label class="small text-muted mb-1" for="ajaxProductPrice">Harga</label>
                                                                    <input id="ajaxProductPrice" name="price" type="number" min="0" step="100" class="form-control" placeholder="450000" required>
                                                            </div>
                                                            <div class="form-group col-md-2">
                                                                    <label class="small text-muted mb-1" for="ajaxProductStock">Stok</label>
                                                                    <input id="ajaxProductStock" name="stock" type="number" min="0" class="form-control" placeholder="10" required>
                                                            </div>
                                                            <div class="form-group col-md-2">
                                                                    <label class="small text-muted mb-1" for="ajaxProductCategory">Kategori ID</label>
                                                                    <input id="ajaxProductCategory" name="category_id" type="number" min="1" class="form-control" placeholder="1" required>
                                                            </div>
                                                            <div class="form-group col-md-2">
                                                                    <label class="small text-muted mb-1" for="ajaxProductSupplier">Supplier ID</label>
                                                                    <input id="ajaxProductSupplier" name="supplier_id" type="number" min="1" class="form-control" placeholder="2" required>
                                                            </div>
                                                    </div>
                                                    <div class="form-row align-items-end">
                                                            <div class="form-group col-md-8">
                                                                    <label class="small text-muted mb-1" for="ajaxProductTags">Tag IDs (opsional, pisahkan dengan koma)</label>
                                                                    <input id="ajaxProductTags" name="tag_ids" class="form-control" placeholder="1,3">
                                                            </div>
                                                            <div class="form-group col-md-4 text-right">
                                                                    <button id="ajaxProductCancel" class="btn btn-outline-secondary mr-2 d-none" type="button">Batal</button>
                                                                    <button id="ajaxProductSubmit" class="btn btn-primary" type="submit">Tambah Produk</button>
                                                            </div>
                                                    </div>
                                            </form>
                                            <div class="table-responsive">
                                                    <table class="table table-striped table-hover" id="ajaxProductTable">
                                                            <thead class="thead-dark">
                                                                    <tr>
                                                                            <th>ID</th>
                                                                            <th>Nama</th>
                                                                            <th>Harga</th>
                                                                            <th>Stok</th>
                                                                            <th>Kategori</th>
                                                                            <th>Supplier</th>
                                                                            <th>Tag</th>
                                                                            <th class="text-right">Aksi</th>
                                                                    </tr>
                                                            </thead>
                                                            <tbody>
                                                                    <tr>
                                                                            <td colspan="8" class="text-center text-muted">Memuat data...</td>
                                                                    </tr>
                                                            </tbody>
                                                    </table>
                                            </div>
                                            <p class="text-muted mb-0">Di produksi, tambahkan loading indicator, validasi lebih kaya, dan feedback visual seperti toast/alert untuk status sukses atau error.</p>
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

            @push('scripts')
            <script>
                $(function () {
                    'use strict';

                    const $form = $('#ajaxProductForm');
                    if (!$form.length) {
                        return;
                    }

                    const apiBase = '/api/demo/products';
                    const $submit = $('#ajaxProductSubmit');
                    const $cancel = $('#ajaxProductCancel');
                    const $alert = $('#ajaxProductAlert');
                    const $tableBody = $('#ajaxProductTable tbody');
                    let mode = 'create';
                    let currencyFormatter;

                    try {
                        currencyFormatter = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            maximumFractionDigits: 0
                        });
                    } catch (error) {
                        currencyFormatter = null;
                    }

                    function escapeHtml(value) {
                        return String(value ?? '')
                            .replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                            .replace(/"/g, '&quot;')
                            .replace(/'/g, '&#039;');
                    }

                    function formatCurrency(value) {
                        const number = Number(value);
                        if (!Number.isFinite(number)) {
                            return value ?? '-';
                        }

                        if (currencyFormatter) {
                            return currencyFormatter.format(number);
                        }

                        return number.toLocaleString('id-ID');
                    }

                    function showMessage(type, message) {
                        $alert
                            .removeClass('d-none alert-success alert-danger alert-info alert-warning')
                            .addClass('alert-' + type)
                            .text(message);
                    }

                    function clearMessage() {
                        $alert
                            .removeClass('alert-success alert-danger alert-info alert-warning')
                            .addClass('d-none')
                            .text('');
                    }

                    function setMode(newMode) {
                        mode = newMode;
                        if (mode === 'update') {
                            $submit.text('Simpan Perubahan');
                            $cancel.removeClass('d-none');
                        } else {
                            $submit.text('Tambah Produk');
                            $cancel.addClass('d-none');
                        }
                    }

                    function resetForm() {
                        $form[0].reset();
                        $form.find('[name="product_id"]').val('');
                        setMode('create');
                    }

                    function parseTagIds(rawValue) {
                        const trimmed = rawValue.trim();
                        if (!trimmed.length) {
                            return [];
                        }

                        return trimmed
                            .split(',')
                            .map(function (item) {
                                return parseInt(item.trim(), 10);
                            })
                            .filter(function (id) {
                                return Number.isInteger(id);
                            });
                    }

                    function buildPayload() {
                        return {
                            name: $form.find('[name="name"]').val().trim(),
                            price: Number($form.find('[name="price"]').val()),
                            stock: Number($form.find('[name="stock"]').val()),
                            category_id: Number($form.find('[name="category_id"]').val()),
                            supplier_id: Number($form.find('[name="supplier_id"]').val()),
                            tag_ids: parseTagIds($form.find('[name="tag_ids"]').val())
                        };
                    }

                    function setFormForUpdate(product) {
                        $form.find('[name="product_id"]').val(product.id);
                        $form.find('[name="name"]').val(product.name);
                        $form.find('[name="price"]').val(product.price);
                        $form.find('[name="stock"]').val(product.stock);
                        $form.find('[name="category_id"]').val(product.category ? product.category.id : '');
                        $form.find('[name="supplier_id"]').val(product.supplier ? product.supplier.id : '');
                        $form.find('[name="tag_ids"]').val((product.tags || []).map(function (tag) {
                            return tag.id;
                        }).join(product.tags && product.tags.length ? ',' : ''));
                        setMode('update');
                    }

                    function setLoadingState() {
                        $tableBody.html('<tr><td colspan="8" class="text-center text-muted">Memuat data...</td></tr>');
                    }

                    function renderProducts(products) {
                        if (!Array.isArray(products) || !products.length) {
                            $tableBody.html('<tr><td colspan="8" class="text-center text-muted">Belum ada data produk. Tambahkan data melalui formulir di atas.</td></tr>');
                            return;
                        }

                        const rows = products.map(function (product) {
                            const categoryLabel = product.category ? escapeHtml(product.category.name) + ' (#' + product.category.id + ')' : '-';
                            const supplierLabel = product.supplier ? escapeHtml(product.supplier.name) + ' (#' + product.supplier.id + ')' : '-';
                            const tagsLabel = product.tags && product.tags.length
                                ? product.tags.map(function (tag) {
                                    return escapeHtml(tag.name) + ' (#' + tag.id + ')';
                                }).join(', ')
                                : '-';

                            return '<tr data-id="' + product.id + '">' +
                                '<td>' + escapeHtml(product.id) + '</td>' +
                                '<td>' + escapeHtml(product.name) + '</td>' +
                                '<td>' + formatCurrency(product.price) + '</td>' +
                                '<td>' + escapeHtml(product.stock) + '</td>' +
                                '<td>' + categoryLabel + '</td>' +
                                '<td>' + supplierLabel + '</td>' +
                                '<td>' + tagsLabel + '</td>' +
                                '<td class="text-right">' +
                                    '<button class="btn btn-sm btn-warning mr-2" type="button" data-action="edit" data-id="' + product.id + '">Edit</button>' +
                                    '<button class="btn btn-sm btn-danger" type="button" data-action="delete" data-id="' + product.id + '">Hapus</button>' +
                                '</td>' +
                            '</tr>';
                        });

                        $tableBody.html(rows.join(''));
                    }

                    function handleAjaxError(xhr, fallbackMessage) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const validationMessages = Object.values(xhr.responseJSON.errors)
                                .reduce(function (carry, messages) {
                                    return carry.concat(messages);
                                }, []);

                            if (validationMessages.length) {
                                showMessage('danger', validationMessages[0]);
                                return;
                            }
                        }

                        const message = (xhr.responseJSON && (xhr.responseJSON.message || xhr.responseJSON.error)) || fallbackMessage;
                        showMessage('danger', message || 'Terjadi kesalahan. Silakan coba lagi.');
                    }

                    function loadProducts() {
                        setLoadingState();

                        $.getJSON(apiBase)
                            .done(function (response) {
                                renderProducts(response.data || []);
                            })
                            .fail(function (xhr) {
                                renderProducts([]);
                                handleAjaxError(xhr, 'Gagal memuat data produk. Pastikan seeder sudah dijalankan.');
                            });
                    }

                    $form.on('submit', function (event) {
                        event.preventDefault();

                        const payload = buildPayload();
                        const productId = $form.find('[name="product_id"]').val();
                        const url = productId ? apiBase + '/' + productId : apiBase;
                        const submittingText = mode === 'update' ? 'Menyimpan...' : 'Menambahkan...';
                        const idleText = mode === 'update' ? 'Simpan Perubahan' : 'Tambah Produk';

                        clearMessage();
                        $submit.prop('disabled', true).text(submittingText);

                        $.ajax({
                            url: url,
                            type: mode === 'update' ? 'PUT' : 'POST',
                            data: JSON.stringify(payload),
                            contentType: 'application/json',
                            dataType: 'json',
                            processData: false
                        })
                            .done(function (response) {
                                const message = (response && response.message) || 'Produk berhasil disimpan.';
                                showMessage('success', message);
                                resetForm();
                                loadProducts();
                            })
                            .fail(function (xhr) {
                                handleAjaxError(xhr, 'Gagal menyimpan produk.');
                            })
                            .always(function () {
                                $submit.prop('disabled', false).text(idleText);
                            });
                    });

                    $cancel.on('click', function () {
                        resetForm();
                        clearMessage();
                    });

                    $tableBody.on('click', 'button[data-action="edit"]', function () {
                        const productId = $(this).data('id');

                        clearMessage();
                        $.getJSON(apiBase + '/' + productId)
                            .done(function (response) {
                                if (!response || !response.data) {
                                    showMessage('danger', 'Data produk tidak ditemukan.');
                                    return;
                                }

                                setFormForUpdate(response.data);
                            })
                            .fail(function (xhr) {
                                handleAjaxError(xhr, 'Gagal mengambil data produk.');
                            });
                    });

                    $tableBody.on('click', 'button[data-action="delete"]', function () {
                        const productId = $(this).data('id');

                        if (!window.confirm('Yakin ingin menghapus produk #' + productId + '?')) {
                            return;
                        }

                        clearMessage();

                        $.ajax({
                            url: apiBase + '/' + productId,
                            type: 'DELETE'
                        })
                            .done(function () {
                                if ($form.find('[name="product_id"]').val() === String(productId)) {
                                    resetForm();
                                }

                                showMessage('success', 'Produk berhasil dihapus.');
                                loadProducts();
                            })
                            .fail(function (xhr) {
                                handleAjaxError(xhr, 'Gagal menghapus produk.');
                            });
                    });

                    loadProducts();
                });
            </script>
            @endpush
