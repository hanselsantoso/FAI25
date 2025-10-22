<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthLandingController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\MiddlewareGuideController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\EloquentDemoController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\Warehouse\ProductStockController;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthLandingController::class, 'show'])->name('auth.landing');
    Route::get('/login', [AuthLandingController::class, 'show'])->name('login');
    Route::get('/register', function () {
        return redirect()->to(route('auth.landing').'#register');
    })->name('register');
    Route::post('/login', [AuthLandingController::class, 'login'])->name('login.perform');
    Route::post('/register', [AuthLandingController::class, 'register'])->name('register.perform');
});

Route::middleware('auth')->post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('auth.landing');
})->name('logout');

Route::middleware('auth')->get('/dashboard', function () {
    $user = Auth::user();

    $target = match ($user->role) {
        'admin' => route('admin.dashboard'),
        'warehouse_manager' => route('admin.warehouse.products.index'),
        'customer' => route('customer.home'),
        default => route('customer.home'),
    };

    return redirect()->to($target);
})->name('dashboard');

Route::middleware(['auth', 'role:customer'])->get('/customer/home', function () {
    return view('customer.home');
})->name('customer.home');

// Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
// Route::get('/admin/products', [ProductController::class, 'index'])->name('admin.products.index');
// Route::get('/admin/products/create', [ProductController::class, 'index'])->name('admin.products.create');
// Route::post('/admin/products', [ProductController::class, 'store'])->name('admin.products.store');
// Route::get('/admin/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
// Route::match(['put','patch'], '/admin/products/{id}', [ProductController::class, 'update'])->name('admin.products.update');
// Route::delete('/admin/products/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

// Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
// Route::get('/admin/categories/create', [CategoryController::class, 'index'])->name('admin.categories.create');
// Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
// Route::get('/admin/categories/{id}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
// Route::match(['put','patch'], '/admin/categories/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
// Route::delete('/admin/categories/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

// Route::get('/admin/suppliers', [SupplierController::class, 'index'])->name('admin.suppliers.index');
// Route::get('/admin/suppliers/create', [SupplierController::class, 'index'])->name('admin.suppliers.create');
// Route::post('/admin/suppliers', [SupplierController::class, 'store'])->name('admin.suppliers.store');
// Route::get('/admin/suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('admin.suppliers.edit');
// Route::match(['put','patch'], '/admin/suppliers/{id}', [SupplierController::class, 'update'])->name('admin.suppliers.update');
// Route::delete('/admin/suppliers/{id}', [SupplierController::class, 'destroy'])->name('admin.suppliers.destroy');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'index'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::match(['put', 'patch'], '/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'index'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::match(['put', 'patch'], '/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('suppliers')->name('suppliers.')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('index');
        Route::get('/create', [SupplierController::class, 'index'])->name('create');
        Route::post('/', [SupplierController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SupplierController::class, 'edit'])->name('edit');
        Route::match(['put', 'patch'], '/{id}', [SupplierController::class, 'update'])->name('update');
        Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('eloquent')->name('eloquent.')->group(function () {
        Route::get('/', [EloquentDemoController::class, 'index'])->name('index');
        Route::post('/create-demo', [EloquentDemoController::class, 'createDemo'])->name('create');
        Route::post('/update-demo', [EloquentDemoController::class, 'updateDemo'])->name('update');
        Route::post('/delete-demo', [EloquentDemoController::class, 'deleteDemo'])->name('delete');
        Route::post('/restore-demo', [EloquentDemoController::class, 'restoreDemo'])->name('restore');
        Route::post('/force-delete-demo', [EloquentDemoController::class, 'forceDeleteDemo'])->name('forceDelete');
        Route::post('/reset-demo', [EloquentDemoController::class, 'resetDemo'])->name('reset');
    });

    Route::prefix('validation')->name('validation.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ValidationDemoController::class, 'show'])->name('show');
        Route::post('/inline', [\App\Http\Controllers\ValidationDemoController::class, 'handleInline'])->name('inline');
        Route::post('/request', [\App\Http\Controllers\ValidationDemoController::class, 'handleFormRequest'])->name('request');
    });

    Route::prefix('uploads')->name('uploads.')->group(function () {
        Route::get('/', [FileUploadController::class, 'index'])->name('index');
        Route::post('/storage', [FileUploadController::class, 'storeWithStorage'])->name('storage');
        Route::get('/storage/{filename}', [FileUploadController::class, 'showStorage'])
            ->where('filename', '[^/]+')
            ->name('storage.show');
        Route::post('/public', [FileUploadController::class, 'storeWithPublic'])->name('public');
        Route::get('/public/{filename}', [FileUploadController::class, 'showPublic'])
            ->where('filename', '[^/]+')
            ->name('public.show');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/create', [UserManagementController::class, 'create'])->name('create');
        Route::post('/', [UserManagementController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
    });

    Route::get('/middleware-guide', MiddlewareGuideController::class)->name('middleware.guide');
});

Route::middleware(['auth', 'role:admin|warehouse_manager'])->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('warehouse')->name('warehouse.')->group(function () {
        Route::get('/products', [ProductStockController::class, 'index'])->name('products.index');
        Route::put('/products/{product}', [ProductStockController::class, 'update'])->name('products.update');
    });

    Route::middleware('role:admin,warehouse_manager')->prefix('secure')->name('secure.')->group(function () {
        Route::get('/demo', function (Request $request) {
            return response()->json([
                'message' => 'Selamat! Guard ' . ($request->attributes->get('auth_guard') ?? config('auth.defaults.guard')) . ' mengenali Anda memiliki role admin/warehouse_manager.',
                'user' => optional($request->user())->only(['name', 'email', 'role']),
            ]);
        })->name('demo');
    });
});
