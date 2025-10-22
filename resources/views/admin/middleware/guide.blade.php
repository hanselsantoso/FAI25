@extends('layouts.admin')

@section('title', 'Langkah Middleware')
@section('page-title', 'Implementasi Middleware Multi-Role')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title mb-0">Urutan Langkah</h3>
            </div>
            <div class="card-body">
                <ol class="mb-0 pl-3">
                    <li><strong>Model User</strong> &mdash; gunakan <code>extends Authenticatable</code>, aktifkan <code>HasFactory</code> dan <code>Notifiable</code>, tambahkan kolom <code>role</code> di <code>$fillable</code>.</li>
                    <li><strong>Migrasi role</strong> &mdash; buat file <code>add_role_to_users_table</code>, tambahkan <code>$table-&gt;string('role')-&gt;default('customer')</code>, jalankan <code>php artisan migrate</code>.</li>
                    <li><strong>Seeder role</strong> &mdash; siapkan user contoh di <code>DatabaseSeeder</code>, set nilai <code>role</code> untuk admin, warehouse manager, customer.</li>
                    <li><strong>Buat middleware</strong> &mdash; perintah <code>php artisan make:middleware EnsureUserHasRole</code>, isi logika cek <code>auth($guard)</code> dan validasi <code>$user-&gt;hasAnyRole()</code>.</li>
                    <li><strong>Daftarkan alias</strong> &mdash; di <code>bootstrap/app.php</code> tambahkan <code>'role' =&gt; EnsureUserHasRole::class</code> dalam <code>$middleware-&gt;alias()</code>.</li>
                    <li><strong>Pembagian role</strong> &mdash; definisikan konstanta pilihan role di <code>UserManagementController</code>, gunakan form admin untuk membuat warehouse manager baru.</li>
                    <li><strong>Routing</strong> &mdash; contoh <code>Route::middleware(['auth', 'role:admin|warehouse_manager'])</code> untuk area stok, <code>Route::middleware(['auth', 'role:admin'])</code> untuk menu admin.</li>
                    <li><strong>Pemanggilan auth()</strong> &mdash; akses user aktif via <code>$user = auth()-&gt;user()</code>, dapatkan guard aktif dari <code>request()-&gt;attributes-&gt;get('auth_guard')</code> jika diperlukan.</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-10">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title mb-0">Penjelasan Komponen</h3>
            </div>
            <div class="card-body">
                <h5 class="font-weight-bold">Authenticatable</h5>
                <p class="text-muted">Kelas dasar Laravel untuk model yang dapat login. Memberi fitur hashing password, remember token, serta integrasi langsung dengan guard. <code>User</code> wajib mewarisi <code>Authenticatable</code> agar <code>Auth::attempt()</code> dan <code>auth()</code> bekerja.</p>

                <h5 class="font-weight-bold mt-4">Middleware</h5>
                <p class="text-muted">Lapisan penyaring request. Bisa menolak akses, mengubah request, atau meneruskan ke controller. Alias <code>role</code> menambahkan pengecekan tambahan di atas middleware <code>auth</code>.</p>

                <h5 class="font-weight-bold mt-4">EnsureUserHasRole</h5>
                <p class="text-muted">Middleware kustom yang membaca parameter role, memastikan user sudah login, lalu memanggil <code>hasAnyRole()</code>. Jika role tidak sesuai, request dihentikan dengan status 403.</p>

                <h5 class="font-weight-bold mt-4">Auth Guard</h5>
                <p class="text-muted">Penjaga autentikasi yang menentukan driver (session, token, dll) dan provider user. Guard default dapat dicek dengan <code>config('auth.defaults.guard')</code>, sedangkan guard aktif tersedia di <code>request()-&gt;attributes-&gt;get('auth_guard')</code> setelah melewati middleware.</p>

                <h5 class="font-weight-bold mt-4">Helper hasAnyRole()</h5>
                <p class="text-muted">Metode di model <code>User</code> untuk memeriksa apakah nilai <code>role</code> berada dalam daftar yang diizinkan. Dipakai oleh middleware dan bisa juga dipanggil langsung dari controller atau Blade.</p>

                <h5 class="font-weight-bold mt-4">Menambah / Mendefinisikan Role Baru</h5>
                <ol class="text-muted pl-3">
                    <li>Tentukan slug role (misal <code>customer_support</code>) dan tampilannya (misal “Customer Support”).</li>
                    <li>Tambah slug tersebut ke konstanta <code>ROLE_OPTIONS</code> di <code>UserManagementController</code> agar muncul di form admin.</li>
                    <li>Jika perlu seed awal, tambahkan user baru ke <code>DatabaseSeeder</code> dan profil terkait.</li>
                    <li>Perbarui tempat yang memakai middleware <code>role:</code> agar mengenali role baru (contoh: <code>role:admin|customer_support</code>).</li>
                    <li>Sesuaikan tampilan/menu yang menggunakan pengecekan <code>hasRole()</code>/<code>hasAnyRole()</code> supaya UI mengikuti role baru.</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-10">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title mb-0">Contoh Praktis</h3>
            </div>
            <div class="card-body">
                <h6 class="font-weight-bold">Mengecek Role di Controller</h6>
                <pre class="bg-light p-2 rounded"><code>$user = auth()->user();

if ($user && $user->hasRole('admin')) {
    // akses fitur admin
}</code></pre>

                <h6 class="font-weight-bold">Memakai Guard Tertentu</h6>
                <pre class="bg-light p-2 rounded"><code>$manager = auth('web')->user();
$apiUser = auth('api')->user();</code></pre>

                <h6 class="font-weight-bold">Blade Directive Sederhana</h6>
                <pre class="bg-light p-2 rounded"><code>@auth
    @if(auth()->user()->hasAnyRole(['admin', 'warehouse_manager']))
        &lt;p&gt;Menu stok hanya terlihat oleh admin dan warehouse manager.&lt;/p&gt;
    @endif
@endauth</code></pre>

                <h6 class="font-weight-bold">Routing Dengan Middleware</h6>
                <pre class="bg-light p-2 rounded"><code>Route::middleware(['auth', 'role:admin|warehouse_manager'])
    ->prefix('admin/warehouse')
    ->group(function () {
        Route::get('/products', ...);
    });</code></pre>
            </div>
        </div>
    </div>
</div>
@endsection
