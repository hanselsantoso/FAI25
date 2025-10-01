<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\LegacyCategory;
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
        $legacyCategories = LegacyCategory::orderBy('code')->get();
        $demoCategories = Category::where('name', 'like', 'Demo:%')->orderByDesc('id')->take(10)->get();
        $trashedDemoCategories = Category::onlyTrashed()->where('name', 'like', 'Demo:%')->orderByDesc('deleted_at')->take(10)->get();
        $softDeleteStats = [
            'active' => Category::where('name', 'like', 'Demo:%')->count(),
            'trashed' => Category::onlyTrashed()->where('name', 'like', 'Demo:%')->count(),
            'withTrashed' => Category::withTrashed()->where('name', 'like', 'Demo:%')->count(),
        ];

        return view('admin.eloquent.index', [
            'examples' => $examples,
            'legacyCategories' => $legacyCategories,
            'demoCategories' => $demoCategories,
            'trashedDemoCategories' => $trashedDemoCategories,
            'softDeleteStats' => $softDeleteStats,
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
