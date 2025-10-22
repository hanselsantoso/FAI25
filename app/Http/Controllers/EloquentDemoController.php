<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\LegacyCategory;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EloquentDemoController extends Controller
{
    public function index(): View
    {
        $examples = $this->buildMethodExamples();
        $relationshipExamples = $this->buildRelationshipExamples();
        $legacyCategories = LegacyCategory::orderBy('code')->get();
        $demoCategories = Category::where('name', 'like', 'Demo:%')->orderByDesc('id')->take(10)->get();
        $trashedDemoCategories = Category::onlyTrashed()->where('name', 'like', 'Demo:%')->orderByDesc('deleted_at')->take(10)->get();
        $softDeleteStats = [
            'active' => Category::where('name', 'like', 'Demo:%')->count(),
            'trashed' => Category::onlyTrashed()->where('name', 'like', 'Demo:%')->count(),
            'withTrashed' => Category::withTrashed()->where('name', 'like', 'Demo:%')->count(),
        ];
        $userProfiles = User::with('profile')->whereHas('profile')->orderBy('name')->get(['id', 'name', 'email', 'role']);
        $categoryProductStats = Category::withCount('products')->orderByDesc('products_count')->take(5)->get();
        $productShowcase = Product::with(['category', 'supplier', 'tags'])->orderBy('name')->take(5)->get();
    $tagCloud = Tag::orderBy('name')->get();
    $demoUsers = User::orderBy('role')->get(['name', 'email', 'role']);
    $authGuards = config('auth.guards', []);

        return view('admin.eloquent.index', [
            'examples' => $examples,
            'relationshipExamples' => $relationshipExamples,
            'legacyCategories' => $legacyCategories,
            'demoCategories' => $demoCategories,
            'trashedDemoCategories' => $trashedDemoCategories,
            'softDeleteStats' => $softDeleteStats,
            'userProfiles' => $userProfiles,
            'categoryProductStats' => $categoryProductStats,
            'productShowcase' => $productShowcase,
            'tagCloud' => $tagCloud,
            'demoUsers' => $demoUsers,
            'authGuards' => $authGuards,
        ]);
    }

    public function createDemo(Request $request): RedirectResponse
    {
        $name = 'Demo: ' . Str::upper(Str::random(5));
        $category = Category::create(['name' => $name]);

        return redirect()
            ->route('admin.eloquent.index')
            ->with('success', "Kategori demo {$category->name} berhasil dibuat dengan Category::create().");
    }

    public function updateDemo(Request $request): RedirectResponse
    {
        $category = Category::where('name', 'like', 'Demo:%')->orderByDesc('id')->first();

        if (! $category) {
            return redirect()
                ->route('admin.eloquent.index')
                ->with('warning', 'Belum ada data demo untuk diupdate. Buat satu data demo terlebih dahulu.');
        }

        $oldName = $category->name;
        $category->update([
            'name' => $oldName . ' (updated at ' . now()->format('H:i:s') . ')',
        ]);

        return redirect()
            ->route('admin.eloquent.index')
            ->with('success', "Kategori demo {$oldName} berhasil diperbarui menggunakan Eloquent::update().");
    }

    public function deleteDemo(Request $request): RedirectResponse
    {
        $category = Category::where('name', 'like', 'Demo:%')->orderByDesc('id')->first();

        if (! $category) {
            return redirect()
                ->route('admin.eloquent.index')
                ->with('warning', 'Tidak ada data demo untuk dihapus.');
        }

        $deletedName = $category->name;
        $category->delete();

        return redirect()
            ->route('admin.eloquent.index')
            ->with('success', "Kategori demo {$deletedName} dipindahkan ke trash menggunakan soft delete.");
    }

    public function restoreDemo(Request $request): RedirectResponse
    {
        $category = Category::onlyTrashed()->where('name', 'like', 'Demo:%')->orderByDesc('deleted_at')->first();

        if (! $category) {
            return redirect()
                ->route('admin.eloquent.index')
                ->with('warning', 'Tidak ada data demo di trash untuk direstore.');
        }

        $restoredName = $category->name;
        $category->restore();

        return redirect()
            ->route('admin.eloquent.index')
            ->with('success', "Kategori demo {$restoredName} berhasil dikembalikan menggunakan restore().");
    }

    public function forceDeleteDemo(Request $request): RedirectResponse
    {
        $category = Category::onlyTrashed()->where('name', 'like', 'Demo:%')->orderByDesc('deleted_at')->first();

        if (! $category) {
            return redirect()
                ->route('admin.eloquent.index')
                ->with('warning', 'Tidak ada data demo di trash untuk dihapus permanen.');
        }

        $name = $category->name;
        $category->forceDelete();

        return redirect()
            ->route('admin.eloquent.index')
            ->with('success', "Kategori demo {$name} dihapus permanen menggunakan forceDelete().");
    }

    public function resetDemo(Request $request): RedirectResponse
    {
        $count = Category::withTrashed()->where('name', 'like', 'Demo:%')->count();
        Category::withTrashed()->where('name', 'like', 'Demo:%')->forceDelete();

        return redirect()
            ->route('admin.eloquent.index')
            ->with('success', "{$count} kategori demo dibersihkan.");
    }

    protected function buildMethodExamples(): array
    {
        $limit = 5;
        $firstCategory = Category::orderBy('id')->first();
        $firstWithProducts = Category::with('products')->has('products')->first();
        $sampleId = $firstCategory?->id;
        $withTrashed = Category::withTrashed()->orderBy('id')->take($limit)->get();
        $onlyTrashed = Category::onlyTrashed()->orderByDesc('deleted_at')->take($limit)->get();
        $trashedCount = Category::onlyTrashed()->count();
        $activeCount = Category::count();

        return [
            [
                'title' => 'Mengambil seluruh baris',
                'eloquent_code' => 'Category::all();',
                'eloquent_result' => $this->normalize(Category::all()->take($limit)),
                'builder_code' => "DB::table('categories')->get();",
                'builder_result' => $this->normalize(DB::table('categories')->get()->take($limit)),
                'notes' => 'Eloquent mengembalikan koleksi model Category (otomatis menyaring record yang soft delete). Query Builder mengembalikan koleksi stdClass tanpa filter bawaan. Output dibatasi 5 baris untuk contoh.',
            ],
            [
                'title' => 'Memilih kolom tertentu',
                'eloquent_code' => "Category::select('id', 'name')->orderBy('id')->take({$limit})->get();",
                'eloquent_result' => $this->normalize(Category::select('id', 'name')->orderBy('id')->take($limit)->get()),
                'builder_code' => "DB::table('categories')->select('id', 'name')->orderBy('id')->take({$limit})->get();",
                'builder_result' => $this->normalize(DB::table('categories')->select('id', 'name')->orderBy('id')->take($limit)->get()),
                'notes' => 'Keduanya hanya mengambil kolom yang dipilih. Di Eloquent, hasil tetap berupa model dengan atribut terbatas.',
            ],
            [
                'title' => 'Mengambil baris pertama',
                'eloquent_code' => "Category::orderBy('id')->first();",
                'eloquent_result' => $this->normalize($firstCategory),
                'builder_code' => "DB::table('categories')->orderBy('id')->first();",
                'builder_result' => $this->normalize($firstCategory ? DB::table('categories')->orderBy('id')->first() : null),
                'notes' => 'first() selalu mengembalikan satu entitas atau null. Pada Eloquent berupa model, pada Query Builder berupa stdClass.',
            ],
            [
                'title' => 'Mencari berdasarkan primary key',
                'eloquent_code' => '$model = Category::find($id);',
                'eloquent_result' => $this->normalize($sampleId ? Category::find($sampleId) : null),
                'builder_code' => "DB::table('categories')->where('id', \$id)->first();",
                'builder_result' => $this->normalize($sampleId ? DB::table('categories')->where('id', $sampleId)->first() : null),
                'notes' => $sampleId
                    ? 'find() hanya tersedia di Eloquent karena memahami primary key dari model.'
                    : 'Belum ada kategori yang bisa dijadikan contoh.',
            ],
            [
                'title' => 'Mengambil satu nilai kolom saja',
                'eloquent_code' => "Category::whereNotNull('name')->value('name');",
                'eloquent_result' => Category::whereNotNull('name')->value('name'),
                'builder_code' => "DB::table('categories')->value('name');",
                'builder_result' => DB::table('categories')->value('name'),
                'notes' => 'value() mengembalikan scalar. Biasanya dipakai untuk get/set konfigurasi sederhana.',
            ],
            [
                'title' => 'Menyusun pasangan key-value',
                'eloquent_code' => "Category::orderBy('name')->pluck('name', 'id');",
                'eloquent_result' => $this->normalize(Category::orderBy('name')->pluck('name', 'id')),
                'builder_code' => "DB::table('categories')->orderBy('name')->pluck('name', 'id');",
                'builder_result' => $this->normalize(DB::table('categories')->orderBy('name')->pluck('name', 'id')),
                'notes' => 'pluck() mengembalikan koleksi sederhana. Sangat berguna untuk dropdown.',
            ],
            [
                'title' => 'Menghitung jumlah baris',
                'eloquent_code' => 'Category::count();',
                'eloquent_result' => $activeCount,
                'builder_code' => "DB::table('categories')->count();",
                'builder_result' => DB::table('categories')->count(),
                'notes' => 'Pada model soft delete, count() di Eloquent otomatis mengecualikan data terhapus. Query Builder menghitung semua baris termasuk yang bertanda deleted_at.',
            ],
            [
                'title' => 'Eager loading relasi products',
                'eloquent_code' => "Category::with('products')->has('products')->first();",
                'eloquent_result' => $this->normalize($firstWithProducts),
                'builder_code' => "DB::table('categories as c')->leftJoin('products as p', 'p.category_id', '=', 'c.id')->select('c.id', 'c.name', 'p.name as product_name')->take({$limit})->get();",
                'builder_result' => $this->normalize(
                    DB::table('categories as c')
                        ->leftJoin('products as p', 'p.category_id', '=', 'c.id')
                        ->select('c.id', 'c.name', 'p.name as product_name')
                        ->take($limit)
                        ->get()
                ),
                'notes' => 'Eloquent memahami relasi dan mengembalikan nested collection. Query Builder perlu JOIN manual dan hasilnya datar.',
            ],
            [
                'title' => 'Soft delete: withTrashed() & onlyTrashed()',
                'eloquent_code' => "Category::withTrashed()->take({$limit})->get();\nCategory::onlyTrashed()->take({$limit})->get();",
                'eloquent_result' => [
                    'withTrashed' => $this->normalize($withTrashed),
                    'onlyTrashed' => $this->normalize($onlyTrashed),
                    'active_vs_trashed' => [
                        'active' => $activeCount,
                        'trashed' => $trashedCount,
                    ],
                ],
                'builder_code' => "DB::table('categories')->whereNotNull('deleted_at')->take({$limit})->get();",
                'builder_result' => $this->normalize(
                    DB::table('categories')->whereNotNull('deleted_at')->take($limit)->get()
                ),
                'notes' => 'Eloquent menyediakan helper untuk mengikutsertakan atau hanya mengambil data terhapus. Query Builder perlu whereNotNull("deleted_at") secara manual.',
            ],
        ];
    }

    protected function buildRelationshipExamples(): array
    {
        $userWithProfile = User::with('profile')->whereHas('profile')->first();
        $categoryWithProducts = Category::with('products')->has('products')->first();
        $productWithTags = Product::with(['tags', 'supplier'])->has('tags')->first();
        $normalizedUserProfile = $userWithProfile ? $this->normalize($userWithProfile) : null;
        $normalizedCategoryProducts = $categoryWithProducts ? $this->normalize($categoryWithProducts->loadCount('products')) : null;
        $normalizedProductTags = $productWithTags ? $this->normalize($productWithTags) : null;

        return [
            [
                'title' => 'One-To-One: User → Profile',
                'eloquent_code' => "User::with('profile')->first();",
                'eloquent_result' => $normalizedUserProfile,
                'builder_code' => "DB::table('users as u')\n    ->join('profiles as p', 'p.user_id', '=', 'u.id')\n    ->select('u.id', 'u.name', 'u.email', 'p.headline', 'p.website')\n    ->first();",
                'builder_result' => $userWithProfile
                    ? $this->normalize(
                        DB::table('users as u')
                            ->join('profiles as p', 'p.user_id', '=', 'u.id')
                            ->select('u.id', 'u.name', 'u.email', 'p.headline', 'p.website')
                            ->where('u.id', $userWithProfile->id)
                            ->first()
                    )
                    : null,
                'notes' => 'Eloquent relasi one-to-one mempermudah eager loading dan akses data profil via $user->profile. Query Builder mengharuskan join manual dan mapping field secara eksplisit.',
            ],
            [
                'title' => 'One-To-Many: Category → Products',
                'eloquent_code' => "Category::with('products')->withCount('products')->first();",
                'eloquent_result' => $normalizedCategoryProducts,
                'builder_code' => "DB::table('categories as c')\n    ->leftJoin('products as p', 'p.category_id', '=', 'c.id')\n    ->select('c.id', 'c.name', DB::raw('COUNT(p.id) as products_count'))\n    ->groupBy('c.id', 'c.name')\n    ->first();",
                'builder_result' => $categoryWithProducts
                    ? $this->normalize(
                        DB::table('categories as c')
                            ->leftJoin('products as p', 'p.category_id', '=', 'c.id')
                            ->select('c.id', 'c.name', DB::raw('COUNT(p.id) as products_count'))
                            ->where('c.id', $categoryWithProducts->id)
                            ->groupBy('c.id', 'c.name')
                            ->first()
                    )
                    : null,
                'notes' => 'Relasi one-to-many memungkinkan akses $category->products sebagai koleksi model lengkap, termasuk withCount() untuk statistik. Query Builder memerlukan GROUP BY untuk menghitung jumlah produk.',
            ],
            [
                'title' => 'Many-To-Many: Product ↔ Tags',
                'eloquent_code' => "Product::with(['tags', 'supplier'])->first();",
                'eloquent_result' => $normalizedProductTags,
                'builder_code' => "DB::table('products as p')\n    ->join('product_tag as pt', 'pt.product_id', '=', 'p.id')\n    ->join('tags as t', 't.id', '=', 'pt.tag_id')\n    ->select('p.id', 'p.name', 't.name as tag_name')\n    ->first();",
                'builder_result' => $productWithTags
                    ? $this->normalize(
                        DB::table('products as p')
                            ->join('product_tag as pt', 'pt.product_id', '=', 'p.id')
                            ->join('tags as t', 't.id', '=', 'pt.tag_id')
                            ->select('p.id', 'p.name', 't.name as tag_name')
                            ->where('p.id', $productWithTags->id)
                            ->first()
                    )
                    : null,
                'notes' => 'Relasi many-to-many memberikan akses koleksi tag lengkap dan metadata pivot ($product->tags). Query Builder harus mengelola tabel pivot product_tag secara manual.',
            ],
        ];
    }

    protected function normalize(mixed $data): mixed
    {
        if ($data instanceof \Illuminate\Support\Collection) {
            return $data->map(fn ($item) => $this->normalize($item))->all();
        }

        if ($data instanceof \Illuminate\Database\Eloquent\Collection) {
            return $data->map(fn ($item) => $this->normalize($item))->all();
        }

        if ($data instanceof \Illuminate\Database\Eloquent\Model) {
            return $this->normalize($data->attributesToArray());
        }

        if ($data instanceof \stdClass) {
            return $this->normalize((array) $data);
        }

        if ($data instanceof \DateTimeInterface) {
            return $data->format('Y-m-d H:i:s');
        }

        if (is_array($data)) {
            return array_map(fn ($item) => $this->normalize($item), $data);
        }

        return $data;
    }
}
