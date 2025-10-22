@extends('layouts.admin')

@section('title', 'Eloquent & Query Builder Playground')
@section('page-title', 'Eloquent & Query Builder Playground')

@section('content')
<div class="row">
    <div class="col-12">
        @foreach (['success', 'warning', 'error'] as $status)
            @if (session($status))
                <div class="alert alert-{{ $status === 'error' ? 'danger' : $status }} alert-dismissible fade show" role="alert">
                    {{ session($status) }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        @endforeach
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Buat Data Demo (Create)</h3>
            </div>
            <div class="card-body">
                <p class="text-muted mb-2">Memanfaatkan <code>Category::create()</code> dengan mass assignment.</p>
                <form action="{{ route('admin.eloquent.create') }}" method="POST">
                    @csrf
                    <button class="btn btn-primary btn-block" type="submit">Tambah Kategori Demo</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title">Perbarui Data Demo (Update)</h3>
            </div>
            <div class="card-body">
                <p class="text-muted mb-2">Memanfaatkan <code>$model-&gt;update()</code> dan otomatisasi timestamps.</p>
                <form action="{{ route('admin.eloquent.update') }}" method="POST">
                    @csrf
                    <button class="btn btn-warning btn-block" type="submit">Update Kategori Demo Terbaru</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-outline card-danger">
            <div class="card-header">
                <h3 class="card-title">Soft Delete (delete())</h3>
            </div>
            <div class="card-body">
                <p class="text-muted mb-2">Menggunakan <code>$model-&gt;delete()</code>. Record belum hilang, hanya diberi cap <code>deleted_at</code>.</p>
                <form action="{{ route('admin.eloquent.delete') }}" method="POST">
                    @csrf
                    <button class="btn btn-danger btn-block" type="submit">Soft Delete Kategori Demo Terbaru</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title">Restore (restore())</h3>
            </div>
            <div class="card-body">
                <p class="text-muted mb-2">Menggunakan <code>$model-&gt;restore()</code> pada data yang soft delete.</p>
                <form action="{{ route('admin.eloquent.restore') }}" method="POST">
                    @csrf
                    <button class="btn btn-success btn-block" type="submit">Restore Data Demo Terbaru</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-outline card-dark">
            <div class="card-header">
                <h3 class="card-title">Force Delete (forceDelete())</h3>
            </div>
            <div class="card-body">
                <p class="text-muted mb-2">Menghapus permanen record yang berada di trash.</p>
                <form action="{{ route('admin.eloquent.forceDelete') }}" method="POST">
                    @csrf
                    <button class="btn btn-dark btn-block" type="submit">Force Delete Data Demo Terbaru</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">Statistik Soft Delete</h3>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 small">
                    <li><strong>Aktif:</strong> {{ $softDeleteStats['active'] }}</li>
                    <li><strong>Terhapus (trash):</strong> {{ $softDeleteStats['trashed'] }}</li>
                    <li><strong>Total (withTrashed):</strong> {{ $softDeleteStats['withTrashed'] }}</li>
                </ul>
                <p class="text-muted mb-0">Bandingkan dengan <code>Category::count()</code> vs <code>Category::withTrashed()->count()</code>.</p>
            </div>
        </div>
    </div>
</div>

                <div class="row">
                    <div class="col-12">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Relasi Eloquent (One-to-One, One-to-Many, Many-to-Many)</h3>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Gunakan section ini untuk membandingkan pendekatan relasi di Eloquent versus Query Builder. Setiap panel menampilkan kode dan hasil ringkas untuk studi kasus yang sama.</p>
                                <div class="accordion" id="relationshipAccordion">
                                    @foreach ($relationshipExamples as $index => $example)
                                        <div class="card mb-2">
                                            <div class="card-header" id="relationship-heading-{{ $index }}">
                                                <h5 class="mb-0 d-flex justify-content-between align-items-center">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#relationship-collapse-{{ $index }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="relationship-collapse-{{ $index }}">
                                                        {{ $example['title'] }}
                                                    </button>
                                                    <span class="badge badge-primary">Relationship</span>
                                                </h5>
                                            </div>

                                            <div id="relationship-collapse-{{ $index }}" class="collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="relationship-heading-{{ $index }}" data-parent="#relationshipAccordion">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6 border-right">
                                                            <h6 class="text-primary">Eloquent</h6>
                                                            <pre class="bg-light p-2 rounded small mb-2"><code>{{ $example['eloquent_code'] }}</code></pre>
                                                            <p class="text-muted mb-1">Hasil:</p>
                                                            <pre class="bg-dark text-white p-2 rounded small">{{ json_encode($example['eloquent_result'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6 class="text-success">Query Builder</h6>
                                                            <pre class="bg-light p-2 rounded small mb-2"><code>{{ $example['builder_code'] }}</code></pre>
                                                            <p class="text-muted mb-1">Hasil:</p>
                                                            <pre class="bg-dark text-white p-2 rounded small">{{ json_encode($example['builder_result'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                                                        </div>
                                                    </div>
                                                    <p class="mt-3 mb-0"><strong>Catatan:</strong> {{ $example['notes'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Snapshot One-to-One (User Profile)</h3>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Role</th>
                                            <th>Headline</th>
                                            <th>Github</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($userProfiles as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td><span class="badge badge-secondary text-uppercase">{{ $user->role }}</span></td>
                                                <td>{{ $user->profile?->headline ?? '—' }}</td>
                                                <td>{{ $user->profile?->github_handle ?? '—' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">Belum ada profil. Jalankan seeder untuk mengisi data contoh.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <p class="small text-muted mb-0">Akses dari kode: <code>$user-&gt;profile-&gt;headline</code> atau gunakan eager loading <code>User::with('profile')</code>.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card card-outline card-warning">
                            <div class="card-header">
                                <h3 class="card-title">Snapshot One-to-Many (Produk per Kategori)</h3>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Kategori</th>
                                            <th>Jumlah Produk</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($categoryProductStats as $category)
                                            <tr>
                                                <td>{{ $category->name }}</td>
                                                <td><span class="badge badge-info">{{ $category->products_count }}</span></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center text-muted">Data kategori belum tersedia.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <p class="small text-muted mb-0">Contoh akses: <code>$category-&gt;products</code> serta agregasi <code>withCount('products')</code> untuk kebutuhan laporan.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card card-outline card-success">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title mb-0">Snapshot Many-to-Many (Product Tags)</h3>
                                <span class="badge badge-success">Pivot</span>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Kategori</th>
                                            <th>Supplier</th>
                                            <th>Tags</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($productShowcase as $product)
                                            <tr>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->category?->name ?? '—' }}</td>
                                                <td>{{ $product->supplier?->name ?? '—' }}</td>
                                                <td>
                                                    @forelse ($product->tags as $tag)
                                                        <span class="badge badge-pill badge-light text-uppercase">{{ $tag->name }}</span>
                                                    @empty
                                                        <span class="text-muted">Tidak ada</span>
                                                    @endforelse
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">Seeder produk belum dijalankan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <p class="small text-muted mb-0">Gunakan <code>$product-&gt;tags</code> untuk akses koleksi pivot atau <code>attach()</code> / <code>sync()</code> saat mengelola relasi.</p>
                            </div>
                            @if ($tagCloud->isNotEmpty())
                                <div class="card-footer bg-light">
                                    <span class="text-muted small">Tag tersedia:</span>
                                    @foreach ($tagCloud as $tag)
                                        <span class="badge badge-secondary text-uppercase">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-outline card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Auth Helper &amp; Multi-Role Middleware</h3>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Middleware <code>role</code> memanfaatkan helper <code>auth()</code> untuk memverifikasi user dan guard terkait.</p>
                                <pre class="bg-light p-2 rounded small mb-3"><code>Route::middleware(['auth', 'role:admin,warehouse_manager'])
                    -&gt;name('secure.')
                    -&gt;group(function () {
        Route::get('/secure-demo', fn () =&gt; 'Area khusus admin/warehouse manager');
                    });

                if (auth()->check()) {
                    $user = auth()->user();
                    $guard = request()->attributes->get('auth_guard');
                }</code></pre>
                                <ul class="small mb-0">
                                    <li><code>auth()</code> tanpa parameter memakai guard default dari konfigurasi.</li>
                                    <li>Tambahkan <code>guard=api</code> pada middleware bila perlu memaksa guard tertentu.</li>
                                    <li>Middleware otomatis merespons JSON 401/403 untuk request API.</li>
                                </ul>
                                <p class="small text-muted mt-3 mb-0">Lingkari endpoint demo yang sudah tersedia: <code>/admin/secure/demo</code>.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-outline card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Akun Demo &amp; Daftar Guard</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mb-3">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($demoUsers as $user)
                                                <tr>
                                                    <td>{{ $user->name }}</td>
                                                    <td><code>{{ $user->email }}</code></td>
                                                    <td><span class="badge badge-info text-uppercase">{{ \Illuminate\Support\Str::of($user->role)->replace('_', ' ')->title() }}</span></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted">Belum ada akun demo. Jalankan seeder untuk menyiapkan user.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <p class="small text-muted">Password default: <code>password</code>. Gunakan untuk login manual saat demonstrasi.</p>
                                </div>
                                <h6 class="text-muted">Guard terdaftar</h6>
                                <ul class="small mb-0">
                                    @forelse ($authGuards as $guardName => $guardConfig)
                                        <li><code>{{ $guardName }}</code> &mdash; driver <code>{{ $guardConfig['driver'] ?? 'n/a' }}</code>, provider <code>{{ $guardConfig['provider'] ?? 'n/a' }}</code></li>
                                    @empty
                                        <li class="text-muted">Belum ada guard terkonfigurasi.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

<div class="row">
    <div class="col-12">
        <div class="card card-outline card-secondary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Riwayat Data Demo (Aktif)</h3>
                <form action="{{ route('admin.eloquent.reset') }}" method="POST" class="mb-0">
                    @csrf
                    <button class="btn btn-sm btn-outline-secondary" type="submit">Bersihkan Semua Demo</button>
                </form>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Dibuat</th>
                            <th>Diperbarui</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($demoCategories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ optional($category->created_at)->format('Y-m-d H:i') }}</td>
                                <td>{{ optional($category->updated_at)->format('Y-m-d H:i') }}</td>
                                <td><span class="badge badge-success">Aktif</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada data demo. Klik tombol "Tambah" untuk memulai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-outline card-danger">
            <div class="card-header">
                <h3 class="card-title">Trash (onlyTrashed)</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Dihapus Pada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($trashedDemoCategories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ optional($category->deleted_at)->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Trash kosong. Soft delete salah satu data untuk melihat perbedaannya.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">Perbandingan Query Builder vs Eloquent</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">Setiap kartu menampilkan contoh kode dan hasil ringkas (maksimal 5 baris) untuk mempermudah penjelasan di kelas.</p>
                <div class="accordion" id="exampleAccordion">
                    @foreach ($examples as $index => $example)
                        <div class="card mb-2">
                            <div class="card-header" id="heading-{{ $index }}">
                                <h5 class="mb-0 d-flex justify-content-between align-items-center">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse-{{ $index }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse-{{ $index }}">
                                        {{ $example['title'] }}
                                    </button>
                                    <span class="badge badge-light">SQL insight</span>
                                </h5>
                            </div>

                            <div id="collapse-{{ $index }}" class="collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading-{{ $index }}" data-parent="#exampleAccordion">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 border-right">
                                            <h6 class="text-primary">Eloquent</h6>
                                            <pre class="bg-light p-2 rounded small mb-2"><code>{{ $example['eloquent_code'] }}</code></pre>
                                            <p class="text-muted mb-1">Hasil:</p>
                                            <pre class="bg-dark text-white p-2 rounded small">{{ json_encode($example['eloquent_result'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-success">Query Builder</h6>
                                            <pre class="bg-light p-2 rounded small mb-2"><code>{{ $example['builder_code'] }}</code></pre>
                                            <p class="text-muted mb-1">Hasil:</p>
                                            <pre class="bg-dark text-white p-2 rounded small">{{ json_encode($example['builder_result'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0"><strong>Catatan:</strong> {{ $example['notes'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title">LegacyCategory Model (Custom Tabel & Primary Key)</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">Contoh model yang menonaktifkan timestamps, menggunakan primary key string, dan menerapkan scope.</p>
                <ul class="mb-3">
                    <li><code>protected $table = 'legacy_categories';</code></li>
                    <li><code>protected $primaryKey = 'code';</code></li>
                    <li><code>$incrementing = false; $keyType = 'string';</code></li>
                    <li><code>public $timestamps = false;</code></li>
                    <li>Accessor <code>getLabelAttribute()</code> & scope <code>scopeActive()</code>.</li>
                </ul>
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Aktif?</th>
                                <th>Label</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($legacyCategories as $legacy)
                                <tr>
                                    <td><code>{{ $legacy->code }}</code></td>
                                    <td>{{ $legacy->title }}</td>
                                    <td>
                                        @if ($legacy->is_active)
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>{{ $legacy->label }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="small text-muted mb-0">Gunakan <code>LegacyCategory::active()->get()</code> untuk hanya mengambil yang aktif.</p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-outline card-light">
            <div class="card-header">
                <h3 class="card-title">Dokumentasi Laravel</h3>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li><a href="https://laravel.com/docs/eloquent" target="_blank" rel="noopener">Eloquent ORM</a></li>
                    <li><a href="https://laravel.com/docs/eloquent-relationships" target="_blank" rel="noopener">Eloquent Relationships</a></li>
                    <li><a href="https://laravel.com/docs/queries" target="_blank" rel="noopener">Query Builder</a></li>
                    <li><a href="https://laravel.com/docs/eloquent#soft-deleting" target="_blank" rel="noopener">Soft Deleting</a></li>
                    <li><a href="https://laravel.com/docs/middleware" target="_blank" rel="noopener">Middleware</a></li>
                    <li><a href="https://laravel.com/docs/authentication" target="_blank" rel="noopener">Authentication &amp; Guards</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
