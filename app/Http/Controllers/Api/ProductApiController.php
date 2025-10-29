<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class ProductApiController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::with(['category:id,name', 'supplier:id,name', 'tags:id,name'])
            ->orderByDesc('id')
            ->get()
            ->map(fn (Product $product) => $this->formatProduct($product));

        return response()->json([
            'message' => 'Product list retrieved successfully.',
            'data' => $products,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'supplier_id' => ['required', Rule::exists('suppliers', 'id')],
            'tag_ids' => ['sometimes', 'array'],
            'tag_ids.*' => ['integer', Rule::exists('tags', 'id')],
        ]);

    $product = Product::create(Arr::except($validated, 'tag_ids'));

        if (! empty($validated['tag_ids'])) {
            $product->tags()->sync($validated['tag_ids']);
        }

        $product->load(['category:id,name', 'supplier:id,name', 'tags:id,name']);

        return response()->json([
            'message' => 'Product created.',
            'data' => $this->formatProduct($product),
        ], Response::HTTP_CREATED);
    }

    public function show(Product $product): JsonResponse
    {
        $product->load(['category:id,name', 'supplier:id,name', 'tags:id,name']);

        return response()->json([
            'message' => 'Product detail retrieved successfully.',
            'data' => $this->formatProduct($product),
        ]);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'required', 'integer', 'min:0'],
            'category_id' => ['sometimes', 'required', Rule::exists('categories', 'id')],
            'supplier_id' => ['sometimes', 'required', Rule::exists('suppliers', 'id')],
            'tag_ids' => ['sometimes', 'array'],
            'tag_ids.*' => ['integer', Rule::exists('tags', 'id')],
        ]);

        $product->fill(Arr::except($validated, 'tag_ids'));
        $product->save();

        if (array_key_exists('tag_ids', $validated)) {
            $product->tags()->sync($validated['tag_ids'] ?? []);
        }

        $product->load(['category:id,name', 'supplier:id,name', 'tags:id,name']);

        return response()->json([
            'message' => 'Product updated.',
            'data' => $this->formatProduct($product),
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
    $product->delete();

    return response()->json([], Response::HTTP_NO_CONTENT);
    }

    private function formatProduct(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'stock' => $product->stock,
            'category' => $product->category?->only(['id', 'name']),
            'supplier' => $product->supplier?->only(['id', 'name']),
            'tags' => $product->tags
                ->map(fn ($tag) => $tag->only(['id', 'name']))
                ->values(),
            'image_path' => $product->image_path,
            'image_url' => $product->image_url,
            'created_at' => optional($product->created_at)?->toIso8601String(),
            'updated_at' => optional($product->updated_at)?->toIso8601String(),
        ];
    }
}
